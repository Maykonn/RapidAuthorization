<?php

/**
 * Role
 * @author Maykonn Welington Candido<maykonn@outlook.com>
 */

namespace Rapid\Authorization;

use \PDO;

class Role extends Entity
{

    public $id = 0;
    public $name = '';

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

    public function save()
    {
        try {
            $sql = "
            INSERT INTO role(
                id, name
            ) VALUES (
                :id, :name
            ) ON DUPLICATE KEY UPDATE
                name = :name";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':id', $this->id, PDO::PARAM_INT);
            $stmt->bindParam(':name', $this->name, PDO::PARAM_STR);
            $stmt->execute();

            if(!$this->id) {
                $this->id = (int) $this->db->lastInsertId();
            }

            $this->id = (int) $this->id;
            return $this->id;
        } catch(PDOException $e) {
            echo '<pre>';
            echo '<b>' . $e->getMessage() . '</b><br/><br/>';
            echo $e->getTraceAsString();
            echo '</pre>';
        }
    }

}

?>
