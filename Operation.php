<?php

/**
 * Operation
 * @author Maykonn Welington Candido<maykonn@outlook.com>
 */

namespace RapidAuthorization;

use \PDO;
use \Exception;
use RapidAuthorization\Database\MySQL;

class Operation extends Entity
{

    public $id = 0;
    public $name = '';
    public $description = null;

    /**
     * @var Operation
     */
    private static $instance;

    /**
     * @return Operation
     */
    public static function instance(ClientPreferences $preferences, PDO $pdo)
    {
        return self::$instance = new self($preferences, $pdo);
    }

    /**
     * <p>An Operation can be, e.g. Create Product or Edit Customer</p>
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
                $sql = "DELETE FROM operation WHERE id = :id";

                $stmt = $this->db->prepare($sql);
                $stmt->bindParam(':id', $this->id, PDO::PARAM_INT);
                return $stmt->execute();
            } catch(PDOException $e) {
                MySQL::instance()->showException($e);
            }
        }

        return false;
    }

    /**
     * <p>Populate object with values from record on database</p>
     */
    private function populateById($operationId)
    {
        $operation = $this->findById($operationId);

        if($operation) {
            $this->id = (int) $operation['id'];
            $this->name = $operation['name'];
            $this->description = $operation['description'];
            return true;
        }

        return false;
    }

    public function findById($operationId)
    {
        try {
            $sql = "SELECT id, name, description FROM operation WHERE id = :operationId";

            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':operationId', $operationId, PDO::PARAM_INT);
            $stmt->execute();
            $stmt->setFetchMode(PDO::FETCH_ASSOC);
            $operation = $stmt->fetch();

            if($operation) {
                return $operation;
            } else {
                throw new Exception('Record #' . $operationId . ' not found on `operation` table');
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
            $sql = "SELECT id, name, description FROM operation";
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
                INSERT INTO operation(
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

    public function getTasksThatCanExecute($operationId)
    {
        if(Operation::instance($this->preferences, $this->db)->findById($operationId)) {
            try {
                $sql = "SELECT id_task FROM task_has_operation WHERE id_operation = :idOperation";
                $stmt = $this->db->prepare($sql);
                $this->id = (int) $operationId;
                $stmt->bindParam(':idOperation', $this->id, PDO::PARAM_INT);
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
