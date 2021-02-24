<?php
$app = new \Slim\App([
    'settings' => [
        'displayErrorDetails' => true
    ]
]);

// REDIRECCION DE LA RAIZ / //
$app->redirect('/', '/api/v1');
$app->redirect('/api', '/api/v1');

// RUTAS //
$app->group('/api/v1', function () use ($app) {

    $app->get('', function () {
        echo 'INDEX CRM API';
    });

    $app->get('/channels', '\App\Controllers\ChannelsController:index');
    $app->post('/channels', '\App\Controllers\ChannelsController:store');

});

// DATA //
$app->get('/tables', '\App\Controllers\TablesController:tables');

// START AP //
$app->run();