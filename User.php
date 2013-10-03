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
        return self::$instance = new self($pdo);
    }

    public function getRoles($id, $pdoFetchMode = PDO::FETCH_ASSOC)
    {
        try {
            $this->id = (int) $id;

            $sql = "
            SELECT rol.id, rol.`name`
            FROM role rol
            RIGHT JOIN user_has_role usr ON rol.id = usr.id_role
            WHERE usr.id_user = :id";

            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':id', $this->id, PDO::PARAM_INT);
            $stmt->execute();
            $stmt->setFetchMode($pdoFetchMode);
            return $stmt->fetchAll();
        } catch(PDOException $e) {
            MySQL::showException($e);
        }
    }

    public function attachRole($roleId, $idUser)
    {
        try {
            $role = Role::instance($this->db);
            $role->id = (int) $roleId;
            $this->id = (int) $idUser;

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
