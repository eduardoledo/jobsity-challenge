<?php

declare(strict_types=1);

use App\Controller\ApiController;
use App\HelloController;
use Slim\App;

return function (App $app) {
    // unprotected routes
    $app->get('/hello/{name}', HelloController::class . ':hello');

    $app->post('/signIn', ApiController::class . ':signIn');

    // protected routes
    $app->get('/bye/{name}', HelloController::class . ':bye');

    $app->get('/stock', ApiController::class . ':getStockQuote');

    $app->get('/history', ApiController::class . ':getQueriesHistory');
};
