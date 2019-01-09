<?php
/**
 * Created by PhpStorm.
 * User: Sanyco
 * Date: 08.01.2019
 * Time: 15:17
 */

class Parser_model extends Model
{

    /**
     * @param $data
     */
    function add($data)
    {
        if (empty($this->db->email->find(['email' => $data['email']])->toArray()))
            $this->db->email->insertOne($data);
    }


    function getCount()
    {
        $ops = array(
            array(
                '$group' => array(
                    "_id" => '$site',
                    "count" => array('$sum' => 1),
                )
            )
        );
        return $this->db->email->aggregate($ops)->toArray();
    }


    function getSiteInfo($site)
    {
        return $this->db->email->find(['site' => $site], ['sort' => ['level' => 1, 'link' => -1]])->toArray();
    }
}