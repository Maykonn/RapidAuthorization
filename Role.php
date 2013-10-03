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
    public static function instance(PDO $pdo)
    {
        return self::$instance = new self($pdo);
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
        if($this->populateById($id)) {
            $this->id = $id;

            try {
                $sql = "DELETE FROM role WHERE id = :id";

                $stmt = $this->db->prepare($sql);
                $stmt->bindParam(':id', $this->id, PDO::PARAM_INT);
                return $stmt->execute();
            } catch(PDOException $e) {
                MySQL::showException($e);
            }
        }

        return false;
    }

    public function attachTask($taskId, $idRole)
    {
        try {
            $task = Task::instance($this->db);
            $task->id = (int) $taskId;
            $this->id = (int) $idRole;

            $sql = "INSERT INTO role_has_task(id_role, id_task) VALUES (:idRole, :idTask)";

            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':idRole', $this->id, PDO::PARAM_INT);
            $stmt->bindParam(':idTask', $task->id, PDO::PARAM_INT);
            return $stmt->execute();
        } catch(PDOException $e) {
            MySQL::showException($e);
        }
    }

    /**
     * <p>Populate object with values from record on database</p>
     */
    private function populateById($id)
    {
        try {
            $sql = "SELECT id, name, description FROM role WHERE id = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            $stmt->setFetchMode(PDO::FETCH_OBJ);

            /* @var $role Role */
            $role = $stmt->fetch();

            if($role) {
                $this->id = (int) $role->id;
                $this->name = $role->name;
                $this->description = $role->description;

                return true;
            } else {
                throw new Exception('Record #' . $id . ' not found on `role` table');
            }
        } catch(PDOException $e) {
            MySQL::showException($e);
        } catch(Exception $e) {
            MySQL::showException($e);
        }
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
            MySQL::showException($e);
        }
    }

}

?>
