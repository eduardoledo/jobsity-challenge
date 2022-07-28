<?php

declare(strict_types=1);

use App\Auth\DoctrineAuthenticator;
use DI\Container;
use Doctrine\ORM\EntityManager;
use Slim\App;
use Slim\Exception\HttpUnauthorizedException;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Psr\Http\Server\RequestHandlerInterface;
use Tuupola\Http\Factory\ResponseFactory;
use Tuupola\Middleware\HttpBasicAuthentication;

return function (App $app) {
    /** @var Container $container */
    $container = $app->getContainer();
    $em = $container->get(EntityManager::class);

    $app->addBodyParsingMiddleware();

    // 1st middleware to configure basic authentication
    $app->add(new HttpBasicAuthentication([
        "path" => ["/bye", "/history", "/stock"], // protected routes
        "authenticator" => new DoctrineAuthenticator($em),
        "error" => function ($response) {
            return $response->withStatus(401);
        }
    ]));

    // 2nd middleware to throw 401 with correct slim exception
    // Reformat when lin updates to v4, see: https://github.com/tuupola/slim-basic-auth/issues/95
    $app->add(function (Request $request, RequestHandler $handler) {
        $response = $handler->handle($request);
        $statusCode = $response->getStatusCode();
        $headers = $request->getHeaders();

        // if ($statusCode == 401) {
        //     throw new HttpUnauthorizedException($request);
        // }

        return $response;
    });
};
