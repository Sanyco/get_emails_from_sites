<?php
/**
 * Created by PhpStorm.
 * User: Sanyco
 * Date: 08.01.2019
 * Time: 12:53
 */

require_once 'Controller.php';
require_once 'Model.php';
require_once 'View.php';
require_once 'Input.php';

class Core
{
    private $controller;

    public function init(){
        $data = (explode('/',trim($_SERVER['REQUEST_URI'],'/')));
        $controller_name = ucfirst($data[0]) ?? '';
        $method = $data[1] ?? '';
        $params = (array_slice($data,2));
        $controller_path = dirname(__DIR__) . "/application/controllers/".$controller_name.".php";
        if(file_exists($controller_path)){
            require_once $controller_path;
            $this->controller =  new $controller_name();
            if(empty($method) && method_exists( $this->controller ,'index')){
                $method = 'index';
            }
            if(method_exists( $this->controller ,$method)){
                call_user_func_array(array(&$this->controller, $method), $params);
            }else{
                throw new Exception("Method $method in controller $controller_name not found");
            }
        }else{
            throw new Exception("Controller $controller_name not found");
        }
    }
}