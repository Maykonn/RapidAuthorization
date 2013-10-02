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

    private $id = 0;
    private $name = '';
    private $description = null;

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
     * <p>Set null to $description to set NULL on database</p>
     */
    public function update($id, $name, $description = null)
    {
        if($this->find($id)) {
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
        if($this->find($id)) {
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

    /**
     * <p>Populate object with values from record on database</p>
     */
    private function find($id)
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

                return $this;
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
