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

class RapidAuthorization
{

    /**
     * @var User
     */
    private $user;

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
        $this->initUserHandler();
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
    }

    private function initUserHandler()
    {
        $this->user = User::instance($this->preferences->userClassInstance);
    }

}

?>
