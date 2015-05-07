<?php

/**
 * Role
 * @author Maykonn Welington Candido<maykonn@outlook.com>
 */

namespace RapidAuthorization;

use \PDO;
use \Exception;
use RapidAuthorization\Database\MySQL;

class Role extends Entity
{
    public function delete($id)
    {
        if($this->findById($id)) {
            $this->id = $id;

            try {
                $sql = "DELETE FROM rpd_role WHERE id = :id";

                $stmt = $this->db->prepare($sql);
                $stmt->bindParam(':id', $this->id, PDO::PARAM_INT);
                return $stmt->execute();
            } catch(\PDOException $e) {
                MySQL::instance()->showException($e);
            }
        }

        return false;
    }

    public function attachTask($taskId, $roleId)
    {
        if($this->isPossibleToAttachTheTask($taskId, $roleId)) {
            try {
                $sql = "INSERT INTO rpd_role_has_task(id_role, id_task) VALUES (:idRole, :idTask)";

                $stmt = $this->db->prepare($sql);
                $stmt->bindParam(':idRole', $roleId, PDO::PARAM_INT);
                $stmt->bindParam(':idTask', $taskId, PDO::PARAM_INT);
                return $stmt->execute();
            } catch(\PDOException $e) {
                MySQL::instance()->showException($e);
            }
        }

        return false;
    }

    private function isPossibleToAttachTheTask($taskId, $roleId)
    {
        return (
            Task::instance($this->preferences, $this->db)->findById($taskId) &&
            !Role::instance($this->preferences, $this->db)->hasAccessToTask($taskId, $roleId)
            );
    }

    /**
     * <p>Populate object with values from record on database</p>
     */
    private function populateById($roleId)
    {
        $role = $this->findById($roleId);

        if($role) {
            $this->id = (int) $role['id'];
            $this->name = $role['name'];
            $this->business_name = $role['business_name'];
            $this->description = $role['description'];
            return true;
        }

        return false;
    }

    public function findById($roleId)
    {
        try {
            $sql = "SELECT id, name, business_name, description FROM rpd_role WHERE id = :roleId";

            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':roleId', $roleId, PDO::PARAM_INT);
            $stmt->execute();
            $stmt->setFetchMode(PDO::FETCH_ASSOC);
            $role = $stmt->fetch();

            if($role) {
                return $role;
            } else {
                throw new Exception('Record #' . $roleId . ' not found on `role` table');
            }
        } catch(\PDOException $e) {
            MySQL::instance()->showException($e);
        } catch(Exception $e) {
            MySQL::instance()->showException($e);
        }

        return false;
    }

    public function findByName($name)
    {
        try {
            $sql = "SELECT id, name, business_name, description FROM rpd_role WHERE name = :name";

            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':name', $name, PDO::PARAM_INT);
            $stmt->execute();
            $stmt->setFetchMode(PDO::FETCH_ASSOC);
            $role = $stmt->fetch();

            if($role) {
                return $role;
            } else {
                throw new Exception('Record with name: ' . $name . ' not found on `role` table');
            }
        } catch(\PDOException $e) {
            MySQL::instance()->showException($e);
        } catch(Exception $e) {
            MySQL::instance()->showException($e);
        }

        return false;
    }

    public function findAll()
    {
        try {
            $sql = "SELECT id, name, business_name, description FROM rpd_role";
            $stmt = $this->db->query($sql);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch(\PDOException $e) {
            MySQL::instance()->showException($e);
        } catch(Exception $e) {
            MySQL::instance()->showException($e);
        }

        return Array();
    }

    public function save()
    {
        try {
            $sql = "
                INSERT INTO rpd_role(
                    id, name, business_name, description
                ) VALUES (
                    :id, :name, :businessName, :description
                ) ON DUPLICATE KEY UPDATE name = :name, business_name = :businessName, description = :description";

            return $this->saveFromSQL($sql);
        } catch(\PDOException $e) {
            MySQL::instance()->showException($e);
        }
    }

    public function getTasks($roleId)
    {
        if(Role::instance($this->preferences, $this->db)->findById($roleId)) {
            try {
                $sql = "
                SELECT t.id, t.name, t.business_name, t.description
                FROM rpd_task t INNER JOIN rpd_role_has_task rht ON t.id = rht.id_task
                WHERE rht.id_role = :idRole";

                $stmt = $this->db->prepare($sql);
                $this->id = (int) $roleId;
                $stmt->bindParam(':idRole', $this->id, PDO::PARAM_INT);
                $stmt->execute();
                return $stmt->fetchAll(PDO::FETCH_ASSOC);
            } catch(\PDOException $e) {
                MySQL::instance()->showException($e);
            } catch(Exception $e) {
                MySQL::instance()->showException($e);
            }
        }

        return Array();
    }

    public function hasAccessToTask($taskId, $roleId)
    {
        if(
            Task::instance($this->preferences, $this->db)->findById($taskId) &&
            Role::instance($this->preferences, $this->db)->findById($roleId)
        ) {
            try {
                $sql = "SELECT id FROM rpd_role_has_task WHERE id_role = :idRole AND id_task = :idTask";
                $stmt = $this->db->prepare($sql);
                $this->id = (int) $roleId;
                $stmt->bindParam(':idRole', $this->id, PDO::PARAM_INT);
                $stmt->bindParam(':idTask', $taskId, PDO::PARAM_INT);
                $stmt->execute();
                return ($stmt->fetch() ? true : false);
            } catch(\PDOException $e) {
                MySQL::instance()->showException($e);
            }
        }

        return false;
    }

    public function getOperations($roleId)
    {
        if(Role::instance($this->preferences, $this->db)->findById($roleId)) {
            try {
                $sql = "
                SELECT o.id, o.`name`, o.business_name, o.description
                FROM rpd_operation o
                LEFT JOIN rpd_task_has_operation tho ON o.id = tho.id_operation
                LEFT JOIN rpd_role_has_task rht ON tho.id_task = rht.id_task
                WHERE rht.id_role = :idRole";

                $stmt = $this->db->prepare($sql);
                $this->id = (int) $roleId;
                $stmt->bindParam(':idRole', $this->id, PDO::PARAM_INT);
                $stmt->execute();
                return $stmt->fetchAll(PDO::FETCH_ASSOC);
            } catch(\PDOException $e) {
                MySQL::instance()->showException($e);
            } catch(Exception $e) {
                MySQL::instance()->showException($e);
            }
        }

        return Array();
    }

    public function hasAccessToOperation($operationId, $roleId)
    {
        if(
            Operation::instance($this->preferences, $this->db)->findById($operationId) &&
            Role::instance($this->preferences, $this->db)->findById($roleId)
        ) {
            $tasksThatCanExecuteTheOperation = Operation::instance($this->preferences, $this->db)->getTasksThatCanExecute($operationId);
            foreach($tasksThatCanExecuteTheOperation as $task) {
                if($this->hasAccessToTask($task['id_task'], $roleId)) {
                    return true;
                }
            }
        }

        return false;
    }

    public function getUsersThatHasPermission($roleId)
    {
        if(Role::instance($this->preferences, $this->db)->findById($roleId)) {
            try {
                $sql = "SELECT id_user FROM rpd_user_has_role WHERE id_role = :idRole";
                $stmt = $this->db->prepare($sql);
                $this->id = (int) $roleId;
                $stmt->bindParam(':idRole', $this->id, PDO::PARAM_INT);
                $stmt->execute();
                return $stmt->fetchAll(PDO::FETCH_COLUMN);
            } catch(\PDOException $e) {
                MySQL::instance()->showException($e);
            }
        }

        return false;
    }

    public function removeUsersFromRole($roleId)
    {
        if(Role::instance($this->preferences, $this->db)->findById($roleId)) {
            try {
                $sql = "DELETE FROM rpd_user_has_role WHERE id_role = :idRole";
                $stmt = $this->db->prepare($sql);
                $this->id = (int) $roleId;
                $stmt->bindParam(':idRole', $this->id, PDO::PARAM_INT);
                return $stmt->execute();
            } catch(\PDOException $e) {
                MySQL::instance()->showException($e);
            }
        }

        return false;
    }

}

