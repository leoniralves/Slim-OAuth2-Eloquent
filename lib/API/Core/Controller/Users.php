<?php
/**
 * Created by PhpStorm.
 * User: leoniralves
 * Date: 11/01/15
 * Time: 01:43
 */

namespace API\Core\Controller;


class Users extends Core {
    public $usersModel;
    public $usersDao;

    public function __construct() {
        parent::__construct();
        $this->usersModel = new \API\Core\Model\Users();
        $this->usersDao = new \API\Core\Dao\Users();
    }

    public function select() {
        $output[] = $this->usersDao->getInstance()->select_db_all();
        echo json_encode($output, JSON_PRETTY_PRINT);
    }

    public function insert() {
        $body = $this->app->request->getBody();

        $this->usersModel->setUsername($body["username"]);
        $this->usersModel->setName($body["name"]);
        $this->usersModel->setEmail($body["email"]);
        $this->usersModel->setPassword($body["password"]);
        $this->usersModel->setPhoto($body["photo"]);

        echo $this->usersDao->getInstance()->insert_db($this->usersModel);
    }
}