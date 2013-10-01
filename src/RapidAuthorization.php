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
        $this->preferences = ClientPreferences::instance($preferences);
    }

    private function initMySQL()
    {
        $this->mysql = MySQL::instance();
        $this->mysql->connect(Array(
            'host' => $this->preferences->mysqlHost,
            'user' => $this->preferences->mysqlUser,
            'pass' => $this->preferences->mysqlPass
        ));

        $this->mysql->selectDb(Array(
            'dbCharset' => $this->preferences->dbCharset,
            'dbName' => $this->preferences->dbName
        ));
    }

    private function initUserHandler()
    {
        $this->user = User::instance($this->preferences->userClassInstance);
    }

}

?>
