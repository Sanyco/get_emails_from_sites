<?php
/**
 * Created by PhpStorm.
 * User: Sanyco
 * Date: 08.01.2019
 * Time: 13:05
 */

/**
 * Class Controller
 */
class Controller
{

    /**
     * @var String
     */
    private $load_type;

    /**
     * @var View
     */
    protected $view;

    /**
     * @var Input
     */
    protected $input;

    public function __construct()
    {
        $this->view = new View();
        $this->input = new Input();
    }

    /**
     * @return Controller
     */
    public function model(){
        $this->load_type = 'model';
        return $this;
    }

    public function load($class){
        switch ($this->load_type){
            case "model":
                $model_key = strtolower($class);
                if(!isset($this->$model_key)){
                    $file = dirname(__DIR__) . "/application/models/".$class.".php";
                    if (file_exists($file)) {
                        require $file;
                        $this->$model_key = new $class();
                    } else {
                        throw new \Exception("$file not found");
                    }
                }
                break;
        }
    }
}