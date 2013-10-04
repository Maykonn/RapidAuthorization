<?php

/**
 * Task
 * @author Maykonn Welington Candido<maykonn@outlook.com>
 */

namespace Rapid\Authorization;

use \PDO;
use \Exception;
use Rapid\Authorization\Database\MySQL;

class Task extends Entity
{

    public $id = 0;
    public $name = '';
    public $description = null;

    /**
     * @var Task
     */
    private static $instance;

    /**
     * @return Task
     */
    public static function instance(PDO $pdo)
    {
        return self::$instance = new self($pdo);
    }

    /**
     * <p>A Task can be, e.g. Manage Products or Manage Customers</p>
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
                $sql = "DELETE FROM task WHERE id = :id";

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
                $sql = "INSERT INTO task_has_operation(id_task, id_operation) VALUES (:idTask, :idOperation)";

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
            Operation::instance($this->db)->findById($operationId) and
            Task::instance($this->db)->findById($taskId)
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
            $this->description = $task['description'];
            return true;
        }

        return false;
    }

    public function findById($taskId)
    {
        try {
            $sql = "SELECT id, name, description FROM task WHERE id = :taskId";

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

    private function save()
    {
        try {
            $sql = "
                INSERT INTO task(
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

    public function getRolesThatHasAccess($taskId)
    {
        if(Task::instance($this->db)->findById($taskId)) {
            try {
                $sql = "SELECT id_role FROM role_has_task WHERE id_task = :idTask";
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

}

?>
