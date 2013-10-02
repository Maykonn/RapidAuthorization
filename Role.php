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
     * <p>Populate object with values from record on database</p>
     */
    public function find($id)
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
            } else {
                throw new Exception('Record #' . $id . ' not found on `role` table');
            }
        } catch(PDOException $e) {
            MySQL::showException($e);
        } catch(Exception $e) {
            MySQL::showException($e);
        }
    }

    public function save()
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

    public function delete()
    {
        try {
            $sql = "DELETE FROM role WHERE id = :id";

            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':id', $this->id, PDO::PARAM_INT);
            return $stmt->execute();
        } catch(PDOException $e) {
            MySQL::showException($e);
        }
    }

}

?>
