<?php

/**
 * Entity
 *
 * @author Maykonn Welington Candido<maykonn@outlook.com>
 */

namespace RapidAuthorization;

use \PDO;
use \ArrayObject;

class Entity
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
     * @var this
     */
    private static $instance;


    /**
     * @var PDO
     */
    protected $db;

    public $id = 0;
    public $name = '';
    public $business_name = '';
    public $description = null;

    protected function __construct(ClientPreferences $preferences, PDO $pdo)
    {
        $this->preferences = $preferences;
        $this->preferencesList = $this->preferences->getPreferencesList();
        $this->db = $pdo;
    }

    /**
     * @return this
     */
    public static function instance(ClientPreferences $preferences, PDO $pdo)
    {
        return self::$instance = new self($preferences, $pdo);
    }

    public static function getInstance() {
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

}
