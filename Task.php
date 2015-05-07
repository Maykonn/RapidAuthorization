<?php

/**
 * Task
 * @author Maykonn Welington Candido<maykonn@outlook.com>
 */

namespace RapidAuthorization;

use \PDO;
use \Exception;
use RapidAuthorization\Database\MySQL;

class Task extends Entity
{

    public $id = 0;
    public $name = '';
    public $business_name = '';
    public $description = null;

    /**
     * @var Task
     */
    private static $instance;

    /**
     * @return Task
     */
    public static function instance(ClientPreferences $preferences, PDO $pdo)
    {
        return self::$instance = new self($preferences, $pdo);
    }

    /**
     * <p>A Task can be, e.g. Manage Products or Manage Customers</p>
     */
    public function create($businessName, $name = null, $description = null)
    {
        $this->name = $name;
        $this->business_name = $businessName;
        $this->description = $description;
        return $this->save();
    }

    /**
     * <p>Set '' to $description to set NULL on database</p>
     */
    public function update($id, $businessName, $name = null, $description = null)
    {
        if($this->populateById($id)) {
            $this->id = $id;
            $this->business_name = $businessName;

            if($name !== null) {
                $this->name = $name;
            }

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
                $sql = "DELETE FROM rpd_task WHERE id = :id";

                $stmt = $this->db->prepare($sql);
                $stmt->bindParam(':id', $this->id, PDO::PARAM_INT);
                return $stmt->execute();
            } catch(PDOException $e) {
                MySQL::instance()->showException($e);
            }
        }

        return false;
    }

    public function attachOperation($operationId, $taskId)
    {
        if($this->isPossibleToAttachTheOperation($operationId, $taskId)) {
            try {
                $sql = "INSERT INTO rpd_task_has_operation(id_task, id_operation) VALUES (:idTask, :idOperation)";

                $stmt = $this->db->prepare($sql);
                $stmt->bindParam(':idTask', $taskId, PDO::PARAM_INT);
                $stmt->bindParam(':idOperation', $operationId, PDO::PARAM_INT);
                return $stmt->execute();
            } catch(PDOException $e) {
                MySQL::instance()->showException($e);
            }
        }

        return false;
    }

    private function isPossibleToAttachTheOperation($operationId, $taskId)
    {
        return (
            Operation::instance($this->preferences, $this->db)->findById($operationId) &&
            !Task::instance($this->preferences, $this->db)->hasOperation($operationId, $taskId)
            );
    }

    /**
     * <p>Populate object with values from record on database</p>
     */
    private function populateById($roleId)
    {
        $task = $this->findById($roleId);

        if($task) {
            $this->id = (int) $task['id'];
            $this->name = $task['name'];
            $this->business_name = $task['business_name'];
            $this->description = $task['description'];
            return true;
        }

        return false;
    }

    public function findById($taskId)
    {
        try {
            $sql = "SELECT id, name, business_name, description FROM rpd_task WHERE id = :taskId";

            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':taskId', $taskId, PDO::PARAM_INT);
            $stmt->execute();
            $stmt->setFetchMode(PDO::FETCH_ASSOC);
            $task = $stmt->fetch();

            if($task) {
                return $task;
            } else {
                throw new Exception('Record #' . $taskId . ' not found on `task` table');
            }
        } catch(PDOException $e) {
            MySQL::instance()->showException($e);
        } catch(Exception $e) {
            MySQL::instance()->showException($e);
        }

        return false;
    }

    public function findByName($name)
    {
        try {
            $sql = "SELECT id, name, business_name, description FROM rpd_task WHERE name = :name";

            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':name', $name, PDO::PARAM_INT);
            $stmt->execute();
            $stmt->setFetchMode(PDO::FETCH_ASSOC);
            $task = $stmt->fetch();

            if($task) {
                return $task;
            } else {
                throw new Exception('Record with name: ' . $name . ' not found on `task` table');
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
            $sql = "SELECT id, name, business_name, description FROM rpd_task";
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
                INSERT INTO rpd_task(
                    id, name, business_name, description
                ) VALUES (
                    :id, :name, :businessName, :description
                ) ON DUPLICATE KEY UPDATE name = :name, business_name = :businessName, description = :description";

            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':id', $this->id, PDO::PARAM_INT);
            $stmt->bindParam(':businessName', $this->business_name, PDO::PARAM_STR);

            $name = ($this->name ? $this->name : null);
            $stmt->bindParam(':name', $name);

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

    public function getRolesThatHasAccess($taskId)
    {
        if(Task::instance($this->preferences, $this->db)->findById($taskId)) {
            try {
                $sql = "SELECT id_role FROM rpd_role_has_task WHERE id_task = :idTask";
                $stmt = $this->db->prepare($sql);
                $this->id = (int) $taskId;
                $stmt->bindParam(':idTask', $this->id, PDO::PARAM_INT);
                $stmt->execute();
                $stmt->setFetchMode(PDO::FETCH_ASSOC);
                return $stmt->fetchAll();
            } catch(PDOException $e) {
                MySQL::instance()->showException($e);
            }
        }

        return false;
    }

    public function getOperations($taskId)
    {
        if(Task::instance($this->preferences, $this->db)->findById($taskId)) {
            try {
                $sql = "
                SELECT o.id, o.name, o.business_name, o.description
                FROM rpd_operation o INNER JOIN rpd_task_has_operation tho ON o.id = tho.id_operation
                WHERE tho.id_task = :idTask";

                $stmt = $this->db->prepare($sql);
                $this->id = (int) $taskId;
                $stmt->bindParam(':idTask', $this->id, PDO::PARAM_INT);
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

    public function hasOperation($operationId, $taskId)
    {
        if(
            Operation::instance($this->preferences, $this->db)->findById($operationId) &&
            Task::instance($this->preferences, $this->db)->findById($taskId)
        ) {
            $operation = Operation::instance($this->preferences, $this->db);
            $tasksThatCanExecuteTheOperation = $operation->getTasksThatCanExecute($operationId);
            foreach($tasksThatCanExecuteTheOperation as $task) {
                if($task['id_task'] == $taskId) {
                    return true;
                }
            }
        }

        return false;
    }

    public function removeTaskFromRole($taskId, $roleId)
    {
        if(
            Role::instance($this->preferences, $this->db)->findById($roleId) &&
            Task::instance($this->preferences, $this->db)->findById($taskId)
        ) {
            try {
                $sql = "DELETE FROM rpd_role_has_task WHERE id_role = :roleId AND id_task = :taskId";

                $stmt = $this->db->prepare($sql);
                $stmt->bindParam(':roleId', $roleId, PDO::PARAM_INT);
                $stmt->bindParam(':taskId', $taskId, PDO::PARAM_INT);
                return $stmt->execute();
            } catch(\PDOException $e) {
                MySQL::instance()->showException($e);
            }
        }

        return false;
    }

}
