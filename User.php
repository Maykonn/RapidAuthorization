<?php

/**
 * User
 * É composta pela classe "User" do domínio da aplicação cliente.
 * @author Maykonn Welington Candido<maykonn@outlook.com>
 */

namespace RapidAuthorization;

use \PDO;
use \Exception;
use RapidAuthorization\Database\MySQL;

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
    public static function instance(ClientPreferences $preferences, PDO $pdo)
    {
        return self::$instance = new self($preferences, $pdo);
    }

    public function getRoles($userId)
    {
        if(User::instance($this->preferences, $this->db)->findById($userId)) {
            try {
                $sql = "
                SELECT rol.id, rol.`name`
                FROM rpd_role rol
                RIGHT JOIN rpd_user_has_role usr ON rol.id = usr.id_role
                WHERE usr.id_user = :idUser";

                $stmt = $this->db->prepare($sql);
                $this->id = (int) $userId;
                $stmt->bindParam(':idUser', $this->id, PDO::PARAM_INT);
                $stmt->execute();
                $stmt->setFetchMode(PDO::FETCH_ASSOC);

                return $stmt->fetchAll();
            } catch(\PDOException $e) {
                MySQL::instance()->showException($e);
            }
        }

        return Array();
    }

    public function getTasks($userId)
    {
        if(User::instance($this->preferences, $this->db)->findById($userId)) {
            try {
                $sql = "
                SELECT DISTINCT t.id, t.name, t.description
                FROM rpd_task t
                LEFT JOIN rpd_role_has_task rht ON t.id = rht.id_task
                LEFT JOIN rpd_user_has_role uhr ON rht.id_role = uhr.id_role
                WHERE uhr.id_user = :idUser";

                $stmt = $this->db->prepare($sql);
                $this->id = (int) $userId;
                $stmt->bindParam(':idUser', $this->id, PDO::PARAM_INT);
                $stmt->execute();
                $stmt->setFetchMode(PDO::FETCH_ASSOC);

                return $stmt->fetchAll();
            } catch(PDOException $e) {
                MySQL::instance()->showException($e);
            }
        }

        return Array();
    }

    public function getOperations($userId)
    {
        if(User::instance($this->preferences, $this->db)->findById($userId)) {
            try {
                $sql = "
                SELECT DISTINCT o.id, o.name, o.description
                FROM rpd_operation o
				LEFT JOIN rpd_task_has_operation tho ON o.id = tho.id_operation
                LEFT JOIN rpd_role_has_task rht ON tho.id_task = rht.id_task
                LEFT JOIN rpd_user_has_role uhr ON rht.id_role = uhr.id_role
                WHERE uhr.id_user = :idUser";

                $stmt = $this->db->prepare($sql);
                $this->id = (int) $userId;
                $stmt->bindParam(':idUser', $this->id, PDO::PARAM_INT);
                $stmt->execute();
                $stmt->setFetchMode(PDO::FETCH_ASSOC);

                return $stmt->fetchAll();
            } catch(PDOException $e) {
                MySQL::instance()->showException($e);
            }
        }

        return Array();
    }

    public function attachRole($roleId, $userId)
    {
        if($this->isPossibleToAttachTheRole($roleId, $userId)) {
            try {
                $sql = "INSERT INTO rpd_user_has_role(id_user, id_role) VALUES (:idUser, :idRole)";

                $stmt = $this->db->prepare($sql);
                $stmt->bindParam(':idUser', $userId, PDO::PARAM_INT);
                $stmt->bindParam(':idRole', $roleId, PDO::PARAM_INT);

                return $stmt->execute();
            } catch(PDOException $e) {
                MySQL::instance()->showException($e);
            }
        }

        return false;
    }

    private function isPossibleToAttachTheRole($roleId, $userId)
    {
        return (
            Role::instance($this->preferences, $this->db)->findById($roleId) &&
            !User::instance($this->preferences, $this->db)->hasPermissionsOfTheRole($roleId, $userId)
            );
    }

    public function findById($userId)
    {
        try {
            $sql = "SELECT * FROM :table WHERE :pk = :userId";

            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':table', $this->preferencesList->userTable);
            $stmt->bindParam(':pk', $this->preferencesList->userTablePK);
            $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
            $stmt->execute();
            $stmt->setFetchMode(PDO::FETCH_ASSOC);
            $user = $stmt->fetch();

            if($user) {
                return $user;
            } else {
                throw new Exception('Record #' . $userId . ' not found on `user` table');
            }
        } catch(PDOException $e) {
            MySQL::instance()->showException($e);
        } catch(Exception $e) {
            MySQL::instance()->showException($e);
        }

        return false;
    }

    public function findAll()
    {
        try {
            $sql = "SELECT :pk FROM :table";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':pk', $this->preferencesList->userTablePK);
            $stmt->bindParam(':table', $this->preferencesList->userTable);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_COLUMN);
        } catch(\PDOException $e) {
            MySQL::instance()->showException($e);
        } catch(Exception $e) {
            MySQL::instance()->showException($e);
        }

        return Array();
    }

    public function hasPermissionsOfTheRole($roleId, $userId)
    {
        if(
            Role::instance($this->preferences, $this->db)->findById($roleId) &&
            User::instance($this->preferences, $this->db)->findById($userId)
        ) {
            try {
                $sql = "SELECT id FROM rpd_user_has_role WHERE id_user = :idUser AND id_role = :idRole";

                $stmt = $this->db->prepare($sql);
                $this->id = (int) $userId;
                $stmt->bindParam(':idUser', $this->id, PDO::PARAM_INT);
                $stmt->bindParam(':idRole', $roleId, PDO::PARAM_INT);
                $stmt->execute();
                return ($stmt->fetch() ? true : false);
            } catch(\PDOException $e) {
                MySQL::instance()->showException($e);
            } catch(Exception $e) {
                MySQL::instance()->showException($e);
            }
        }

        return false;
    }

    public function hasAccessToTask($taskId, $userId)
    {
        if(
            Task::instance($this->preferences, $this->db)->findById($taskId) &&
            User::instance($this->preferences, $this->db)->findById($userId)
        ) {
            $rolesThatHasAccessToTask = Task::instance($this->preferences, $this->db)->getRolesThatHasAccess($taskId);
            foreach($rolesThatHasAccessToTask as $role) {
                if($this->hasPermissionsOfTheRole($role['id_role'], $userId)) {
                    return true;
                }
            }
        }

        return false;
    }

    public function hasAccessToOperation($taskId, $operationId, $userId)
    {
        if(
            User::instance($this->preferences, $this->db)->hasAccessToTask($taskId, $userId) &&
            Task::instance($this->preferences, $this->db)->hasOperation($operationId, $taskId)
        ) {
            $tasksThatCanExecuteTheOperation = Operation::instance($this->preferences, $this->db)->getTasksThatCanExecute($operationId);
            foreach($tasksThatCanExecuteTheOperation as $task) {
                if($this->hasAccessToTask($task['id_task'], $userId)) {
                    return true;
                }
            }
        }

        return false;
    }

    public function removeUserFromRole($userId, $roleId)
    {
        if(
            User::instance($this->preferences, $this->db)->findById($userId) &&
            Role::instance($this->preferences, $this->db)->findById($roleId)
        ) {
            try {
                $sql = "DELETE FROM rpd_user_has_role WHERE id_user = :userId AND id_role = :roleId";

                $stmt = $this->db->prepare($sql);
                $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
                $stmt->bindParam(':roleId', $roleId, PDO::PARAM_INT);
                return $stmt->execute();
            } catch(\PDOException $e) {
                MySQL::instance()->showException($e);
            }
        }

        return false;
    }

}
