<?php

/**
 * User
 * É composta pela classe "User" do domínio da aplicação cliente.
 * @author Maykonn Welington Candido<maykonn@outlook.com>
 */

namespace Rapid\Authorization;

use \PDO;
use Rapid\Authorization\Database\MySQL;

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

    public function getRoles($fetchMode)
    {
        try {
            $sql = "
            SELECT rol.id, rol.`name`
            FROM role rol
            RIGHT JOIN user_has_role usr ON rol.id = usr.id_role
            WHERE usr.id_user = :id";

            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':id', $this->id, PDO::PARAM_INT);
            $stmt->execute();
            $stmt->setFetchMode($fetchMode);
            return $stmt->fetchAll();
        } catch(PDOException $e) {
            MySQL::showException($e);
        }
    }

    public function attachRole(Role $role)
    {
        try {
            $sql = "INSERT INTO user_has_role(id_user, id_role) VALUES (:idUser, :idRole)";

            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':idUser', $this->id, PDO::PARAM_INT);
            $stmt->bindParam(':idRole', $role->id, PDO::PARAM_INT);
            return $stmt->execute();
        } catch(PDOException $e) {
            MySQL::showException($e);
        }
    }

}

?>
