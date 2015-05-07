<?php

/**
 * Authorization
 * @author Maykonn Welington Candido<maykonn@outlook.com>
 */

namespace RapidAuthorization;

// Necessárias antes do Autoload
require_once 'ClientPreferences.php';
require_once 'AvailablePreferences.php';
require_once 'Autoload.php';

use RapidAuthorization\Database\MySQL;
use RapidAuthorization\Database\MySQLSchemaHandler;

class RapidAuthorization
{

    /**
     * @var ClientPreferences
     */
    private $preferences;

    /**
     * @var ArrayObject
     */
    private $preferencesList;

    /**
     * @var MySQL
     */
    private $mysql;

    public function __construct($preferences = Array())
    {
        $this->initPreferences($preferences);
        $this->initMySqlHandler();
    }

    private function initPreferences(Array $preferences)
    {
        $this->preferences = ClientPreferences::instance($preferences);
        $this->preferencesList = $this->preferences->getPreferencesList();
    }

    private function initMySqlHandler()
    {
        $this->mysql = MySQL::instance();
        $this->mysql->connect(Array(
            'pdoInstance' => $this->preferencesList->pdoInstance,
            'host' => $this->preferencesList->mysqlHost,
            'port' => $this->preferencesList->mysqlPort,
            'user' => $this->preferencesList->mysqlUser,
            'pass' => $this->preferencesList->mysqlPass,
            'dbName' => $this->preferencesList->dbName,
            'dbCharset' => $this->preferencesList->dbCharset
        ));

        if($this->preferencesList->autoGenerateTables) {
            $schema = MySQLSchemaHandler::instance($this->preferences, $this->mysql->getHandler());
            $schema->createDefaultSchema();
        }
    }

    /**
     * @return Role
     */
    public function role()
    {
        return Role::instance($this->preferences, $this->mysql->getHandler());
    }

    /**
     * @return User
     */
    public function user()
    {
        return User::instance($this->preferences, $this->mysql->getHandler());
    }

    /**
     * @return Task
     */
    public function task()
    {
        return Task::instance($this->preferences, $this->mysql->getHandler());
    }

    /**
     * @return Operation
     */
    public function operation()
    {
        return Operation::instance($this->preferences, $this->mysql->getHandler());
    }

}
