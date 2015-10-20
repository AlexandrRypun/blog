<?php

namespace Framework\Model;


use Blog\Model\Post;
use Blog\Model\User;
use Framework\DI\Service;

abstract class ActiveRecord{

    protected static $table;
    protected $key = 'id';
    public $id = null;


    public function save(){
        $db = Service::get('db');
        $table = static::getTable();
        $this->password = md5($this->password);

        $fields = get_object_vars($this);

        $sth = $db->prepare('SHOW COLUMNS FROM '.$table);
        $sth->setFetchMode(\PDO::FETCH_ASSOC);
        $sth->execute();
        $colums = array();
        while($row = $sth->fetch()) {
            $colums[] = $row['Field'];
        }


        $query = "INSERT INTO ".$table." SET ";
        foreach($fields as $key => $value){
            if(array_search($key, $colums)){
               $query_parts[] = sprintf("`%s`='%s'", $key, $fields[$key]);
            }
        }
        $query_part = implode(', ', $query_parts);
        $query .= $query_part;

        $sth = $db->prepare($query);
        $sth->execute();
    }


    public static function find($key){

        $db = Service::get('db');
        $table = static::getTable();
        $db->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

        if ($key == 'all'){
            $posts = array();
            $stmt = $db->prepare("SELECT * FROM ".$table." ORDER BY `id` DESC");
            $stmt->execute();
            $posts = $stmt->fetchAll(\PDO::FETCH_OBJ);

            return $posts;
        }else{
            $id = (int)$key;
            $post = null;

            $stmt = $db->prepare("SELECT * FROM ".$table." WHERE `id` = '".$id."'");
            $stmt->execute();

            $post = $stmt->fetchAll(\PDO::FETCH_OBJ);

            return (!is_null($post))?$post[0]:false;
            }


    }

    public static function findByEmail($email){
        $db = Service::get('db');
        $table = static::getTable();
        $email = (string)$email;
        $user = null;

        $stmt = $db->prepare("SELECT * FROM ".$table." WHERE `email` = '".$email."'");
        $stmt->execute();
        $user = $stmt->fetchAll(\PDO::FETCH_CLASS, 'Blog\Model\User');

        return (!is_null($user))?$user[0]:false;
    }



}