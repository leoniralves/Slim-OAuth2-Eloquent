<?php
/**
 * Created by PhpStorm.
 * User: leoniralves
 * Date: 13/02/15
 * Time: 00:24
 */

// API Métodos
$app->group('/api', function() use ($app, $log) {
    $app->group('/v1', function() use ($app, $log) {
        // Users
        $app->group('/users', function() use ($app, $log) {
            $app->get('/', '\API\Core\Controller\Users:select');
            $app->post('/', '\API\Core\Controller\Users:insert');

        });
    });
});
