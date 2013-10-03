<?php

/**
 * User
 * É composta pela classe "User" do domínio da aplicação cliente.
 * @author Maykonn Welington Candido<maykonn@outlook.com>
 */

namespace Rapid\Authorization;

use \PDO;
use \Exception;
use Rapid\Authorization\Database\MySQL;

class User extends Entity
{

    public $id;

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

    public function attachRole($roleId, $userId)
    {
        if($this->isPossibleToAttachTheRole($roleId, $userId)) {
            try {
                $sql = "INSERT INTO user_has_role(id_user, id_role) VALUES (:idUser, :idRole)";

                $stmt = $this->db->prepare($sql);
                $stmt->bindParam(':idUser', $userId, PDO::PARAM_INT);
                $stmt->bindParam(':idRole', $roleId, PDO::PARAM_INT);
                return $stmt->execute();
            } catch(PDOException $e) {
                MySQL::showException($e);
            }
        }

        return false;
    }

    private function isPossibleToAttachTheRole($roleId, $userId)
    {
        return (
            Role::instance($this->db)->findById($roleId) and
            User::instance($this->db)->findById($userId)
            );
    }

    public function findById($userId)
    {
        try {
            // use * here because we don't know the fields from "User" table
            $sql = "SELECT * FROM user WHERE id = :userId";

            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':userId', $userId);
            $stmt->execute();
            $stmt->setFetchMode(PDO::FETCH_ASSOC);
            $user = $stmt->fetch();

            if($user) {
                return $user;
            } else {
                throw new Exception('Record #' . $userId . ' not found on `user` table');
            }
        } catch(PDOException $e) {
            MySQL::showException($e);
        } catch(Exception $e) {
            MySQL::showException($e);
        }

        return false;
    }

}

?>
