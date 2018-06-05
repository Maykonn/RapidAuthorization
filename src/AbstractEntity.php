<?php

/**
 * Entity
 *
 * @author Maykonn Welington Candido<maykonn@outlook.com>
 */

namespace RapidAuthorization;

use Doctrine\DBAL\Driver\Connection;
use \PDO;
use \ArrayObject;

abstract class AbstractEntity implements IEntity
{
    /**
     * @var ClientPreferences
     */
    protected $preferences;

    /**
     * @var ArrayObject
     */
    protected $preferencesList;

    /**
     * @var $this
     */
    protected static $instance;


    /**
     * @var Connection
     */
    protected $db;

    public $id = 0;
    public $name = '';
    public $business_name = '';
    public $description = null;

    protected function __construct(ClientPreferences $preferences, Connection $pdo)
    {
        $this->preferences = $preferences;
        $this->preferencesList = $this->preferences->getPreferencesList();
        $this->db = $pdo;
    }

    public static function getInstance()
    {
        return self::$instance;
    }

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
        if ($this->populateById($id)) {
            $this->id = $id;
            $this->business_name = $businessName;

            if ($name !== null) {
                $this->name = $name;
            }

            if ($description !== null) {
                $this->description = $description;
            }

            return $this->save();
        }

        return 0;
    }

    /**
     * <p>Populate object with values from record on database</p>
     */
    private function populateById($roleId)
    {
        $task = $this->findById($roleId);

        if ($task) {
            $this->id = (int) $task['id'];
            $this->name = $task['name'];
            $this->business_name = $task['business_name'];
            $this->description = $task['description'];

            return true;
        }

        return false;
    }

    protected function saveFromSQL($sql)
    {
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id', $this->id, PDO::PARAM_INT);
        $stmt->bindParam(':businessName', $this->business_name, PDO::PARAM_STR);

        $name = ($this->name ? $this->name : null);
        $stmt->bindParam(':name', $name);

        $description = ($this->description ? $this->description : null);
        $stmt->bindParam(':description', $description);

        $stmt->execute();

        if ( ! $this->id) {
            $this->id = (int) $this->db->lastInsertId();
        }

        return $this->id = (int) $this->id;
    }

}
