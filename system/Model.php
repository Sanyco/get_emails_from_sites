<?php
/**
 * Created by PhpStorm.
 * User: Sanyco
 * Date: 08.01.2019
 * Time: 13:54
 */

class Model
{
    protected $db;

    public function __construct()
    {
        include dirname(__DIR__) . "/application/config/database.php";
        if (isset($database['user']) && !empty($database['user'])) {
            $this->db = new MongoDB\Client("mongodb://${database['user']}:${database['pass']}@${database['host']}:${database['port']}");
        } else {
            $this->db = new MongoDB\Client("mongodb://${database['host']}:${database['port']}");
        }
        $db_name = $database['db_name'];
        $this->db = $this->db->$db_name;
    }
}