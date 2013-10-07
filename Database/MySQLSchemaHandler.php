<?php

/**
 * MySQLSchemaHandler
 * @author Maykonn Welington Candido<maykonn@outlook.com>
 */

namespace Rapid\Authorization\Database;

use \PDO;
use Rapid\Authorization\ClientPreferences;

class MySQLSchemaHandler
{

    private $userTable = '';
    private $userTablePK = '';

    /**
     * @var PDO
     */
    private $db;

    /**
     * @var MySQLSchemaHandler
     */
    private static $instance;

    /**
     * @return MySQLSchemaHandler
     */
    public static function instance(ClientPreferences $preferences, PDO $pdo)
    {
        if(self::$instance instanceof MySQLSchemaHandler) {
            return self::$instance;
        } else {
            return self::$instance = new self($preferences, $pdo);
        }
    }

    private function __construct(ClientPreferences $preferences, PDO $pdo)
    {
        $preferencesList = $preferences->getPreferencesList();
        $this->userTable = $preferencesList->userTable;
        $this->userTablePK = $preferencesList->userTablePK;
        $this->db = $pdo;
    }

    public function createDefaultSchema()
    {
        try {
            $stmt = $this->db->prepare($this->getAuthorizationTablesStmt());
            $stmt->execute();
        } catch(PDOException $e) {
            echo '<pre>';
            echo '<b>' . $e->getMessage() . '</b><br/><br/>';
            echo $e->getTraceAsString();
            echo '</pre>';
        }
    }

    private function getAuthorizationTablesStmt()
    {
        $dir = dirname(__FILE__) . DIRECTORY_SEPARATOR;
        return file_get_contents($dir . 'schema.sql');
    }

}

?>
