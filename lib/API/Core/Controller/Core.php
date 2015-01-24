<?php
/**
 * Created by PhpStorm.
 * User: leoniralves
 * Date: 07/01/15
 * Time: 23:56
 */

namespace API\Core\Controller;

class Core {
    public $app;

    public function __construct() {
        if (!isset($this->app)) {
            $this->app = \Slim\Slim::getInstance();
        }
    }
}