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

    public function attachRole(Role $role)
    {
        $sql = "INSERT INTO user_has_role(id_user, id_role) VALUES (:userId, :roleId)";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':userId', $this->id, PDO::PARAM_INT);
        $stmt->bindParam(':roleId', $role->id, PDO::PARAM_INT);
        return $stmt->execute();
    }

}

?>
