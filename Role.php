<?php

/**
 * Role
 * @author Maykonn Welington Candido<maykonn@outlook.com>
 */

namespace Rapid\Authorization;

use \PDO;
use \Exception;
use Rapid\Authorization\Database\MySQL;

class Role extends Entity
{

    public $id = 0;
    public $name = '';
    public $description = null;

    /**
     * @var Role
     */
    private static $instance;

    /**
     * @return Role
     */
    public static function instance(ClientPreferences $preferences, PDO $pdo)
    {
        return self::$instance = new self($preferences, $pdo);
    }

    /**
     * <p>A Role can be, e.g. Admin, Seller, etc.</p>
     */
    public function create($name, $description = null)
    {
        $this->name = $name;
        $this->description = $description;
        return $this->save();
    }

    /**
     * <p>Set '' to $description to set NULL on database</p>
     */
    public function update($id, $name, $description = null)
    {
        if($this->populateById($id)) {
            $this->id = $id;
            $this->name = $name;

            if($description !== null) {
                $this->description = $description;
            }

            return $this->save();
        }

        return 0;
    }

    public function delete($id)
    {
        if($this->findById($id)) {
            $this->id = $id;

            try {
                $sql = "DELETE FROM role WHERE id = :id";

                $stmt = $this->db->prepare($sql);
                $stmt->bindParam(':id', $this->id, PDO::PARAM_INT);
                return $stmt->execute();
            } catch(PDOException $e) {
                MySQL::instance()->showException($e);
            }
        }

        return false;
    }

    public function attachTask($taskId, $roleId)
    {
        if($this->isPossibleToAttachTheTask($taskId, $roleId)) {
            try {
                $sql = "INSERT INTO role_has_task(id_role, id_task) VALUES (:idRole, :idTask)";

                $stmt = $this->db->prepare($sql);
                $stmt->bindParam(':idRole', $roleId, PDO::PARAM_INT);
                $stmt->bindParam(':idTask', $taskId, PDO::PARAM_INT);
                return $stmt->execute();
            } catch(PDOException $e) {
                MySQL::instance()->showException($e);
            }
        }

        return false;
    }

    private function isPossibleToAttachTheTask($taskId, $roleId)
    {
        return (
            Task::instance($this->preferences, $this->db)->findById($taskId) and
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
            $this->description = $role['description'];
            return true;
        }

        return false;
    }

    public function findById($roleId)
    {
        try {
            $sql = "SELECT id, name, description FROM role WHERE id = :roleId";

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
            $sql = "SELECT id, name, description FROM role";
            $stmt = $this->db->query($sql);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            MySQL::instance()->showException($e);
        } catch(Exception $e) {
            MySQL::instance()->showException($e);
        }

        return Array();
    }

    private function save()
    {
        try {
            $sql = "
                INSERT INTO role(
                    id, name, description
                ) VALUES (
                    :id, :name, :description
                ) ON DUPLICATE KEY UPDATE name = :name, description = :description";

            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':id', $this->id, PDO::PARAM_INT);
            $stmt->bindParam(':name', $this->name, PDO::PARAM_STR);
            $description = ($this->description ? $this->description : null);
            $stmt->bindParam(':description', $description);
            $stmt->execute();

            if(!$this->id) {
                $this->id = (int) $this->db->lastInsertId();
            }

            $this->id = (int) $this->id;
            return $this->id;
        } catch(PDOException $e) {
            MySQL::instance()->showException($e);
        }
    }

    public function getTasks($roleId)
    {
        if(Role::instance($this->preferences, $this->db)->findById($roleId)) {
            try {
                $sql = "
                SELECT t.id, t.name, t.description
                FROM task t INNER JOIN role_has_task rht ON t.id = rht.id_task
                WHERE rht.id_role = :idRole";

                $stmt = $this->db->prepare($sql);
                $this->id = (int) $roleId;
                $stmt->bindParam(':idRole', $this->id, PDO::PARAM_INT);
                $stmt->execute();
                return $stmt->fetchAll(PDO::FETCH_ASSOC);
            } catch(PDOException $e) {
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
            Task::instance($this->preferences, $this->db)->findById($taskId) and
            Role::instance($this->preferences, $this->db)->findById($roleId)
        ) {
            try {
                $sql = "SELECT id FROM role_has_task WHERE id_role = :idRole AND id_task = :idTask";
                $stmt = $this->db->prepare($sql);
                $this->id = (int) $roleId;
                $stmt->bindParam(':idRole', $this->id, PDO::PARAM_INT);
                $stmt->bindParam(':idTask', $taskId, PDO::PARAM_INT);
                $stmt->execute();
                return ($stmt->fetch() ? true : false);
            } catch(PDOException $e) {
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
                SELECT o.id, o.`name`, o.description
                FROM operation o
                LEFT JOIN task_has_operation tho ON o.id = tho.id_operation
                LEFT JOIN role_has_task rht ON tho.id_task = rht.id_task
                WHERE rht.id_role = :idRole";

                $stmt = $this->db->prepare($sql);
                $this->id = (int) $roleId;
                $stmt->bindParam(':idRole', $this->id, PDO::PARAM_INT);
                $stmt->execute();
                return $stmt->fetchAll(PDO::FETCH_ASSOC);
            } catch(PDOException $e) {
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
            Operation::instance($this->preferences, $this->db)->findById($operationId) and
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
                $sql = "SELECT id_user FROM user_has_role WHERE id_role = :idRole";
                $stmt = $this->db->prepare($sql);
                $this->id = (int) $roleId;
                $stmt->bindParam(':idRole', $this->id, PDO::PARAM_INT);
                $stmt->execute();
                return $stmt->fetchAll(PDO::FETCH_COLUMN);
            } catch(PDOException $e) {
                MySQL::instance()->showException($e);
            }
        }

        return false;
    }

}

?>
