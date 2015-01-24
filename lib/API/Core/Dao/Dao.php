<?php
/**
 * Created by PhpStorm.
 * User: leoniralves
 * Date: 30/12/14
 * Time: 13:43
 */

namespace API\Core\Dao;

use \Illuminate\Database\Eloquent\Model as Eloquent;

abstract class Dao extends Eloquent {
    public $timestamps = false;

    /**
     * Seleciona e retorna todas as colunas da tabela
     * @return mixed array()
     */
    public abstract function select_db_all();

    /**
     * Seleciona e rotorna uma coluna específica da tabela
     * @param Object|string $object
     * @return mixed
     */
    public abstract function select_db_item($object = "");

    /**
     * Adiciona um novo registro a tabela
     * @param Object|string $object
     * @return mixed
     */
    public abstract function insert_db($object = "");

    /**
     * Atualiza um registro existente na tabela
     * @param Object|string $object
     * @return mixed
     */
    public abstract function update_db($object = "");

    /**
     * Remove um registro existente na tabela
     * @param Object|string $object
     * @return mixed
     */
    public abstract function delete_db($object = "");
}