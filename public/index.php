<?php

require_once dirname(__FILE__) . '/../bootstrap.php';

// Authorization App
$app->group('/oauth', function() use ($app, $log) {
    $app->post('/token', '\API\Auth\AccessTokenOAuth:AccessToken');
});

// Public home page
$app->get(
    '/',
    function () use ($app, $log) {
        echo "<h1>Hello, Interface Pública</h1>";
    }
);

// JSON friendly errors
// NOTE: $app['debug'] deve ser false ou o modelo default será exibido
$app->error(function (\Exception $e) use ($app, $log) {

    $mediaType = $app->request->getMediaType();

    $isAPI = (bool) preg_match('/\/api\/v.*$/', $app->request->getPath());

    // Dados de exceção padrão
    $error = array(
        'code' => $e->getCode(),
        'message' => $e->getMessage(),
        'file' => $e->getFile(),
        'line' => $e->getLine(),
    );

    // Output de erros no modo de produção
    if (!in_array(get_class($e), array('API\\Exception', 'API\\Exception\ValidationException')) && 'production' === $app->config('mode')) {
        $error['message'] = 'There was an internal error';
        unset($error['file'], $error['line']);
    }

    // Custom error data (e.g. Validations)
    if (method_exists($e, 'getData')) {
        $errors = $e->getData();
    }

    if (!empty($errors)) {
        $error['errors'] = $errors;
    }

    $log->error($e->getMessage());
    if ('application/json' === $mediaType || true === $isAPI) {
        $app->response->headers->set(
            'Content-Type',
            'application/json'
        );
        echo json_encode($error, JSON_PRETTY_PRINT);
    } else {
        echo '<html>
                <head><title>Error</title></head>
                <body>
                    <h1>Error: ' . $error['code'] . '</h1>
                    <p>' . $error['message'] .'</p>
                </body>
              </html>';
    }

});

// Custom 404 error
$app->notFound(function () use ($app) {

    $mediaType = $app->request->getMediaType();

    $isAPI = (bool) preg_match('/\/api\/v.*$/', $app->request->getPath());

    if ('application/json' === $mediaType || true === $isAPI) {

        $app->response->headers->set(
            'Content-Type',
            'application/json'
        );

        echo json_encode(
            array(
                'code' => 404,
                'message' => 'Not found'
            ),
            JSON_PRETTY_PRINT
        );

    } else {
        echo '<html>
                <head><title>404 Page Not Found</title></head>
                <body>
                    <h1>404 Page Not Found</h1>
                    <p>The page you are looking for could not be found.</p>
                </body>
              </html>';
    }
});

require_once('routes.php');
$app->run();