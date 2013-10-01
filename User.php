<?php

/**
 * User
 * É composta pela classe "User" do domínio da aplicação cliente.
 * @author Maykonn Welington Candido<maykonn@outlook.com>
 */

namespace Rapid\Authorization;

use \PDO;

class User extends Entity
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
    public static function instance(PDO $pdo)
    {
        if(self::$instance instanceof User) {
            return self::$instance;
        } else {
            return self::$instance = new self($pdo);
        }
    }

    public function save()
    {

    }

}

?>
