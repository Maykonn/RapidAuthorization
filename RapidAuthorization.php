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

    // ROLE ----------------------------------------------------------------------------------------
    public function createRole($name)
    {
        $role = Role::instance($this->mysql->getHandler());
        $role->name = $name;
        return $role->save();
    }

    public function updateRole($id, $name)
    {
        $role = Role::instance($this->mysql->getHandler());
        $role->id = $id;
        $role->name = $name;
        return $role->save();
    }

    public function deleteRole($id)
    {
        $role = Role::instance($this->mysql->getHandler());
        $role->id = $id;
        return $role->delete();
    }

    // USER ----------------------------------------------------------------------------------------
    public function attachRoleInUser($roleId, $userId)
    {
        $role = Role::instance($this->mysql->getHandler());
        $role->id = (int) $roleId;

        $user = User::instance($this->mysql->getHandler());
        $user->id = (int) $userId;

        return $user->attachRole($role);
    }

}

?>
