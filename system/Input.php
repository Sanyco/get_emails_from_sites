<?php
/**
 * Created by PhpStorm.
 * User: Sanyco
 * Date: 08.01.2019
 * Time: 15:54
 */

class Input
{

    /**
     * @param $key
     * @return mixed
     */
    public function post($key)
    {
        return $_POST[$key] ?? NULL;
    }

    /**
     * @param $key
     * @return mixed
     */
    public function get($key)
    {
        return $_GET[$key] ?? NULL;
    }
}