<?php

/**
 * User
 * É composta pela classe "User" do domínio da aplicação cliente.
 * @author Maykonn Welington Candido<maykonn@outlook.com>
 */

namespace Rapid\Authorization;

class User
{

    public $id;
    public $username;

    /**
     * @var User
     */
    private static $instance;

    /**
     * @return User
     */
    public static function instance()
    {
        if(self::$instance instanceof User) {
            return self::$instance;
        } else {
            return self::$instance = new self();
        }
    }

    private function __construct()
    {

    }

    public function save() {
        
    }

}

?>
