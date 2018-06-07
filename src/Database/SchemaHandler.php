<?php

/**
 * SchemaHandler
 * @author Maykonn Welington Candido<maykonn@outlook.com>
 */

namespace RapidAuthorization\Database;

use Doctrine\DBAL\Connection;
use \PDO;
use RapidAuthorization\ClientPreferences;

class SchemaHandler
{

    private $userTable = '';
    private $userTablePK = '';
    private $dbConnCharset = '';

    /**
     * @var PDO
     */
    private $db;

    /**
     * @var SchemaHandler
     */
    private static $instance;

    /**
     * @param ClientPreferences $preferences
     * @param Connection $dbConn
     *
     * @return SchemaHandler
     */
    public static function instance(ClientPreferences $preferences, Connection $dbConn)
    {
        if (self::$instance instanceof SchemaHandler) {
            return self::$instance;
        }

        return self::$instance = new self($preferences, $dbConn);
    }

    private function __construct(ClientPreferences $preferences, Connection $dbConn)
    {
        $preferencesList = $preferences->getPreferencesList();
        $this->userTable = $preferencesList->userTable;
        $this->userTablePK = $preferencesList->userTablePK;
        $this->dbConnCharset = $preferencesList->dbConnCharset;
        $this->db = $dbConn->getWrappedConnection();
    }

    public function createDefaultSchema()
    {
        $stmt = $this->db->prepare($this->getAuthorizationTablesStmt());

        return $stmt->execute();
    }

    private function getAuthorizationTablesStmt()
    {
        $dir = dirname(__FILE__) . DIRECTORY_SEPARATOR;
        $contentDefault = file_get_contents($dir . 'schema.sql');

        // replace user table and PK
        $userTableDefault = 'CREATE TABLE IF NOT EXISTS `user` (
  `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`)';
        $userTable = 'CREATE TABLE IF NOT EXISTS `' . $this->userTable . '` (
  `' . $this->userTablePK . '` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`' . $this->userTablePK . '`)';
        $contentUserTable = str_replace($userTableDefault, $userTable, $contentDefault);

        // tables collation
        $collationDefault = 'utf8';
        $collation = $this->dbConnCharset;
        $content = str_replace($collationDefault, $collation, $contentUserTable);

        return $content;
    }

}
