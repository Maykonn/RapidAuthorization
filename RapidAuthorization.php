<?php

/**
 * Authorization
 * @author Maykonn Welington Candido<maykonn@outlook.com>
 */

namespace Rapid\Authorization;

// Necessárias antes do Autoload
require_once 'ClientPreferences.php';
require_once 'AvaiblePreferences.php';
require_once 'Autoload.php';

use \PDO;
use Rapid\Authorization\Database\MySQL;
use Rapid\Authorization\Database\MySQLSchemaHandler;

class RapidAuthorization
{

    /**
     * @var ClientPreferences
     */
    private $preferences;

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
        $this->preferences = ClientPreferences::instance($preferences)->getPreferencesList();
    }

    private function initMySqlHandler()
    {
        $this->mysql = MySQL::instance();
        $this->mysql->connect(Array(
            'host' => $this->preferences->mysqlHost,
            'port' => $this->preferences->mysqlPort,
            'user' => $this->preferences->mysqlUser,
            'pass' => $this->preferences->mysqlPass,
            'dbName' => $this->preferences->dbName,
            'dbCharset' => $this->preferences->dbCharset
        ));

        if($this->preferences->autoGenerateTables) {
            $schema = MySQLSchemaHandler::instance($this->mysql->getHandler());
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

    // USER ----------------------------------------------------------------------------------------
    public function userGetRoles($userId, $pdoFetchMode = PDO::FETCH_ASSOC)
    {
        $user = User::instance($this->mysql->getHandler());
        $user->id = (int) $userId;
        return $user->getRoles($pdoFetchMode);
    }

    public function userAttachRole($roleId, $userId)
    {
        $role = Role::instance($this->mysql->getHandler());
        $role->id = (int) $roleId;

        $user = User::instance($this->mysql->getHandler());
        $user->id = (int) $userId;

        return $user->attachRole($role);
    }

    // TASK ----------------------------------------------------------------------------------------
    /**
     * <p>A task can be, e.g. Manage Products or Manage Customers</p>
     */
    public function taskCreate()
    {

    }

    // OPERATION -----------------------------------------------------------------------------------
}

?>
