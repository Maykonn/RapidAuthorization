<?php

/**
 * Authorization
 * @author Maykonn Welington Candido<maykonn@outlook.com>
 */

namespace RapidAuthorization;

use Doctrine\DBAL\Connection;
use RapidAuthorization\Database\DB;
use RapidAuthorization\Database\MySQL;
use RapidAuthorization\Database\SchemaHandler;

class RapidAuthorization
{

    /**
     * @var ClientPreferences
     */
    private $preferences;

    /**
     * @var \ArrayObject
     */
    private $preferencesList;

    /**
     * @var Connection
     */
    private $dbConn;

    public function __construct($preferences = Array())
    {
        $this->initPreferences($preferences);
        $this->initDatabase();
    }

    private function initPreferences(Array $preferences)
    {
        $this->preferences = ClientPreferences::instance($preferences);
        $this->preferencesList = $this->preferences->getPreferencesList();
    }

    private function initDatabase()
    {
        $this->dbConn = DB::connect($this->preferencesList);

        if ($this->preferencesList->autoGenerateTables) {
            $schema = SchemaHandler::instance($this->preferences, $this->dbConn->getWrappedConnection());
            $schema->createDefaultSchema();
        }
    }

    public function role()
    {
        return Role::instance($this->preferences, $this->dbConn->getWrappedConnection());
    }

    public function user()
    {
        return User::instance($this->preferences, $this->dbConn->getWrappedConnection());
    }

    public function task()
    {
        return Task::instance($this->preferences, $this->dbConn->getWrappedConnection());
    }

    public function operation()
    {
        return Operation::instance($this->preferences, $this->dbConn->getWrappedConnection());
    }

}

