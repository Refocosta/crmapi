<?php
$app = new \Slim\App();

// REDIRECCION DE LA RAIZ / //
$app->redirect('/', '/api/v1');
$app->redirect('/api', '/api/v1');

// RUTAS //
$app->group('/api/v1', function () use ($app) {

    $app->get('', function () {
        echo 'INDEX CRM API';
    });

    $app->get('/channels', '\App\Controllers\ChannelsController:index');

});

// START AP //
$app->run();