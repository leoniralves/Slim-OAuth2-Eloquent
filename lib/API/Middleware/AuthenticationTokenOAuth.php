<?php
/**
 * Created by PhpStorm.
 * User: leoniralves
 * Date: 20/01/15
 * Time: 13:32
 */

namespace API\Middleware;

//use League\OAuth2\Server;

class AuthenticationTokenOAuth extends \Slim\Middleware {

    private $server;

    function __construct($server) {
        if (!isset($this->app)) {
            $this->app = \Slim\Slim::getInstance();
        }

        $this->server = $server;
    }

    /**
     * Call
     *
     * Perform actions specific to this middleware and optionally
     * call the next downstream middleware.
     */
    public function call()
    {
        $res = $this->app->request->params();
        if(empty($res["grant_type"])) {
            $this->verifyToken();
        }
        else {
            $this->next->call();
        }
    }

    public function verifyToken() {
        $res = $this->app->response();
        try {
            $this->server->isValidRequest(false);
            $this->next->call();
        } catch(\League\OAuth2\Server\Exception\OAuthException $e) {
            $res->status(401);
            $res->write(
                json_encode([
                    'error'     =>  $e->errorType,
                    'message'   =>  $e->getMessage(),
                ]),
                $e->httpStatusCode,
                $e->getHttpHeaders()
            );
            $res->headers->set('Content-Type', 'application/json');
            return true;
        } catch(\Exception $e) {
            echo 't';
        }
    }

}