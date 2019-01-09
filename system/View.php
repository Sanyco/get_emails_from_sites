<?php
/**
 * Created by PhpStorm.
 * User: Sanyco
 * Date: 08.01.2019
 * Time: 12:53
 */

/**
 * Class View
 */
class View
{


    /**
     * @param string $view
     * @param array $args
     * @throws Exception
     */
    public function load(string $view, array $args = [])
    {
        extract($args, EXTR_SKIP);
        $file = dirname(__DIR__) . "/application/views/".$view.".php";
        if (file_exists($file)) {
            require $file;
        } else {
            throw new Exception("$file not found");
        }
    }
}