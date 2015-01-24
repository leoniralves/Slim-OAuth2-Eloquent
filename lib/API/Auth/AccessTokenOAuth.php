<?php
/**
 * Created by PhpStorm.
 * User: leoniralves
 * Date: 14/01/15
 * Time: 00:04
 */

namespace API\Auth;

use API\Storage;
use Slim\Slim;

class AccessTokenOAuth {

    private $app;
    private $server;

    function __construct() {
        // Slim
        $this->app = Slim::getInstance();

        // OAuth
        $this->server = new \League\OAuth2\Server\AuthorizationServer();
        $this->server->setSessionStorage(new Storage\SessionStorage());
        $this->server->setAccessTokenStorage(new Storage\AccessTokenStorage());
        $this->server->setRefreshTokenStorage(new Storage\RefreshTokenStorage());
        $this->server->setClientStorage(new Storage\ClientStorage());
        $this->server->setScopeStorage(new Storage\ScopeStorage());
        $this->server->setAuthCodeStorage(new Storage\AuthCodeStorage());

        $authCodeGrant = new \League\OAuth2\Server\Grant\AuthCodeGrant();
        $this->server->addGrantType($authCodeGrant);

        $refrehTokenGrant = new \League\OAuth2\Server\Grant\RefreshTokenGrant();
        $this->server->addGrantType($refrehTokenGrant);

        $passwordGrant = new \League\OAuth2\Server\Grant\PasswordGrant();
        $passwordGrant->setVerifyCredentialsCallback(function ($username, $password) {
            $user = new \API\Core\Model\Users();
            $user->setUsername($username);
            $user->setPassword($password);
            $results = \API\Core\Dao\Users::getInstance()->select_db_item($user);

            if(count($results) !== 1) {
                return false;
            }

            if(password_verify($password, $results[0]['password'])) {
                return $username;
            }
        });
        $this->server->addGrantType($passwordGrant);
    }

    function AccessToken() {
        try {
            $response = $this->server->issueAccessToken();
            $res = $this->app->response();
            $res->status(401);
            $res->write(json_encode($response));
            $res->headers->set('Content-Type', 'application/json');
            return $res->finalize();

        } catch (\League\OAuth2\Server\Exception\OAuthException $e) {
            $res = $this->app->response();
            $res->write(
                json_encode([
                    'error'     =>  $e->errorType,
                    'message'   =>  $e->getMessage(),
                ]),
                $e->httpStatusCode,
                $e->getHttpHeaders()
            );
            $res->headers->set('Content-Type', 'application/json');
        }

    }

}