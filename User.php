<?php

/**
 * User
 * É composta pela classe "User" do domínio da aplicação cliente.
 * @author Maykonn Welington Candido<maykonn@outlook.com>
 */

namespace Rapid\Authorization;

use \PDO;

class User
{

    public $id;
    public $username;

    /**
     * @var PDO
     */
    private $db;

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

    private function __construct(PDO $pdo)
    {
        $this->db = $pdo;
    }

    public function save()
    {

    }

}

?>
