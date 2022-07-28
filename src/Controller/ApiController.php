<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Query;
use App\Service\QueryService;
use App\Service\UserService;
use DateTime;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Exception\HttpBadRequestException;
use Swift_Mailer;
use Swift_Message;

class ApiController
{
    private QueryService $queryService;
    private $userService;
    private Swift_Mailer $mailer;

    public function __construct(
        QueryService $queryService,
        UserService $userService,
        Swift_Mailer $mailer
    ) {
        $this->queryService = $queryService;
        $this->userService = $userService;
        $this->mailer = $mailer;
    }

    public function getStockQuote(Request $request, Response $response, array $args): Response
    {
        $user = $this->getUser($request);
        $query = $request->getQueryParams()['q'];

        if (is_null($query)) {
            throw new HttpBadRequestException($request, "No query provided");
        } else {
            $data = file_get_contents("https://stooq.com/q/l/?s={$query}&f=sd2t2ohlcvn&h&e=json");
            try {
                $json = json_decode($data, true);
                $symbol = array_shift($json['symbols']);
                $symbol['date'] = new DateTime("{$symbol['date']}T{$symbol['time']}+0200");

                $query = $this->queryService->save(Query::fromAssocArray($symbol, $user));

                $assoc = $query->toAssocArray();

                $msgBody = join(
                    "\n",
                    array_map(function ($k, $v) {
                        return "{$k} = {$v}";
                    }, array_keys($assoc), array_values($assoc))
                );
                $message = (new Swift_Message('Hello from PHP Challenge'))
                    ->setFrom(['phpchallenge@jobsity.io' => 'PHP Challenge'])
                    ->setTo([$user->getEmail()])
                    ->setBody($msgBody);

                // Later just do the actual email sending.
                $this->mailer->send($message);

                unset($assoc['date']);


                $response->getBody()->write(json_encode($assoc));
            } catch (\Throwable $th) {
                throw $th;
            }
        }

        return $response;
    }

    public function getQueriesHistory(Request $request, Response $response, array $args): Response
    {
        $user = $this->getUser($request);

        $queries = $this->queryService->list($user);
        $queriesArray = array_map(function (Query $query) {
            return $query->toAssocArray();
        }, $queries);

        $json = json_encode($queriesArray);

        $response->getBody()->write($json);

        return $response;
    }

    public function signIn(Request $request, Response $response, array $args): Response
    {
        $data = $request->getParsedBody();
        try {
            $user = $this->userService
                ->signIn($data['email'], $data['password']);
        } catch (\Throwable $th) {
            // throw $th;
            throw new HttpBadRequestException($request, $th->getMessage());
        }

        return $response;
    }

    private function getUser(Request $request)
    {
        $username = $request->getServerParams()['PHP_AUTH_USER'];
        return $this->userService->findByEmail($username);
    }
}
