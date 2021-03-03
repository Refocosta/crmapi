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
    // CHANNELS //
    $app->group('/channels', function () use ($app) {
        $app->get('', '\App\Controllers\ChannelsController:index');
        $app->post('', '\App\Controllers\ChannelsController:store');
        $app->get('/{id}', '\App\Controllers\ChannelsController:show');
        $app->put('/{id}', '\App\Controllers\ChannelsController:update');
        $app->delete('/{id}', '\App\Controllers\ChannelsController:destroy');
        $app->patch('/{id}', '\App\Controllers\ChannelsController:delete');
    });
    // CONTACTS //
    $app->group('/contacts', function () use ($app) {
        $app->get('', '\App\Controllers\ContactsController:index');
        $app->post('', '\App\Controllers\ContactsController:store');
        $app->get('/{id}', '\App\Controllers\ContactsController:show');
        $app->put('/{id}', '\App\Controllers\ContactsController:update');
        $app->delete('/{id}', '\App\Controllers\ContactsController:destroy');
        $app->patch('/{id}', '\App\Controllers\ContactsController:delete');
    });
})->add(new KeyMiddleware());

// DATA //
$app->get('/tables', '\App\Controllers\TablesController:tables')->add(new AtdbMiddleware());
$app->get('/down', '\App\Controllers\TablesController:down')->add(new AtdbMiddleware());

// START AP //
$app->run();