<?php

/**
 * User
 * É composta pela classe "User" do domínio da aplicação cliente.
 * @author Maykonn Welington Candido<maykonn@outlook.com>
 */

namespace Rapid\Authorization;

class User
{

    /**
     * <p>Instance of user class on client application domain</p>
     * @var Object
     */
    private $userClassInstance;

    /**
     * @var User
     */
    private static $instance;

    /**
     * @return User
     */
    public static function instance($userClass = '')
    {
        if(self::$instance instanceof User) {
            return self::$instance;
        } else {
            return self::$instance = new self($userClass);
        }
    }

    private function __construct($userClass)
    {
        $this->userClassInstance = $userClass;
    }

}

?>
