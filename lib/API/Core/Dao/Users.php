<?php
/**
 * Created by PhpStorm.
 * User: leoniralves
 * Date: 11/01/15
 * Time: 00:38
 */

namespace API\Core\Dao;

use \Illuminate\Database\Capsule\Manager as Capsule;

class Users extends Dao {

    private $user;
    protected $table = "users";
    protected $primaryKey = "id";

    private static $instance;

    function __construct() {
        parent::__construct();
        $this->user = new \API\Core\Model\Users();
    }

    public static function getInstance() {
        if (!isset(self::$instance)) {
            $class = __CLASS__;
            self::$instance = new $class;
        }

        return self::$instance;
    }

    /**
     * Seleciona e retorna todas as colunas da tabela
     * @return mixed array()
     */
    public function select_db_all()
    {
        $query = Capsule::table($this->table)
                    ->select(['username', 'name', 'email', 'photo']);
        $result = $query->get();
        return $result;
    }

    /**
     * Seleciona e rotorna uma coluna específica da tabela
     * @param Object|string $object
     * @return mixed
     */
    public function select_db_item($object = "")
    {
        $this->user = $object;

        $query = Capsule::table($this->table)
                    ->select(['username', 'password', 'name', 'email', 'photo'])
                    ->where('username', '=', $this->user->getUsername());

        $result = $query->get();
        return $result;

    }

    /**
     * Adiciona um novo registro a tabela
     * @param Object|string $object
     * @return mixed
     */
    public function insert_db($object = "")
    {
        $this->user = $object;
        Capsule::table($this->table)->insert([
           'username' => $this->user->getUsername(),
           'password' => password_hash($this->user->getPassword(), PASSWORD_DEFAULT),
           'name'     => $this->user->getName(),
           'email'    => $this->user->getEmail(),
           'photo'    => $this->user->getPhoto()
        ]);
    }

    /**
     * Atualiza um registro existente na tabela
     * @param Object|string $object
     * @return mixed
     */
    public function update_db($object = "")
    {
        // TODO: Implement update_db() method.
    }

    /**
     * Remove um registro existente na tabela
     * @param Object|string $object
     * @return mixed
     */
    public function delete_db($object = "")
    {
        // TODO: Implement delete_db() method.
    }
}