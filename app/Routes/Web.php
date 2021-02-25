<?php
use Middlewares\{KeyMiddleware, AtdbMiddleware};
use Exceptions\HandlerException;

$app = new \Slim\App([
    'settings' => [
        'displayErrorDetails' => true
    ]
]);
$c = $app->getContainer();
$c['errorHandler'] = function ($c) {
    return new HandlerException();
};

// REDIRECCION DE LA RAIZ / //
$app->redirect('/', '/api/v1');
$app->redirect('/api', '/api/v1');

// RUTAS //
$app->group('/api/v1', function () use ($app) {

    $app->get('', function () {
        echo json_encode([
            "crm" => "NATIVA CRM API",
            "home" => "Bienvenido",
            "version" => "1.0.1"
        ]);
    });

    $app->get('/channels', '\App\Controllers\ChannelsController:index');
    $app->post('/channels', '\App\Controllers\ChannelsController:store');

})->add(new KeyMiddleware());

// DATA //
$app->get('/tables', '\App\Controllers\TablesController:tables')->add(new AtdbMiddleware());

// START AP //
$app->run();