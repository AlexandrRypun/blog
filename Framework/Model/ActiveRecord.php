<?php

namespace Framework\Model;


use Blog\Model\Post;
use Blog\Model\User;

abstract class ActiveRecord{

    protected $table;
    protected $key = 'id';

    public function save(){
        $fields = get_object_vars($this);


    }

    public static function find($key){
        $post1 = new Post();
        $post1->id = 1;
        $post1->title = 'title1';
        $post1->content = 'text1';
        $post1->date = '9-10-2015';
        $post1->name = 'user1';

        $post2 = new Post();
        $post2->id = 2;
        $post2->title = 'title2';
        $post2->content = 'text2';
        $post2->date = '10-10-2015';
        $post2->name = 'user2';

        $posts = array($post1, $post2);

        if ($key == 'all'){
            $table = Post::getTable();
            $query = 'SELECT * FROM'.$table;

            return $posts;
        }else{
              foreach ($posts as $post){
                if ($post->id == $key) {
                    $right_post = $post;
                    break;
                }
            }

            return $right_post;
        }




    }

    public function findByEmail($email){
        $user = new User();
        $user->id = 1;
        $user->email = $email;
        $user->password = 111;
        $user->role = 'ROLE_USER';

        return $user;
    }



}