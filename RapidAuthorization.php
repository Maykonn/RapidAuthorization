<?php

/**
 * Authorization
 * @author Maykonn Welington Candido<maykonn@outlook.com>
 */

namespace Rapid\Authorization;

// Necessárias antes do Autoload
require_once 'ClientPreferences.php';
require_once 'AvailablePreferences.php';
require_once 'Autoload.php';

use Rapid\Authorization\Database\MySQL;
use Rapid\Authorization\Database\MySQLSchemaHandler;

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
        return Role::instance($this->mysql->getHandler());
    }

    /**
     * @return User
     */
    public function user()
    {
        return User::instance($this->mysql->getHandler());
    }

    /**
     * @return Task
     */
    public function task()
    {
        return Task::instance($this->mysql->getHandler());
    }

    /**
     * @return Operation
     */
    public function operation()
    {
        return Operation::instance($this->mysql->getHandler());
    }

}

?>
