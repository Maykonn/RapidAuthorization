<?php

/**
 * MySQL
 * @author Maykonn Welington Candido<maykonn@outlook.com>
 */

namespace RapidAuthorization\Database;

use \PDO;

class MySQL
{

    /**
     * @var PDO
     */
    private $conn;

    /**
     * @return PDO
     */
    public function getHandler()
    {
        return $this->conn;
    }

    /**
     * @var MySQL
     */
    private static $instance;

    /**
     * @return MySQL
     */
    public static function instance()
    {
        if(self::$instance instanceof MySQL) {
            return self::$instance;
        } else {
            return self::$instance = new self();
        }
    }

    private function __construct()
    {

    }

    public function connect(Array $connection)
    {
        try {
            $this->conn = new PDO(
                "mysql:host=" . $connection['host'] .
                ";port=" . $connection['port'] .
                ";dbname=" . $connection['dbName'], $connection['user'], $connection['pass']
            );
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->conn->query('SET NAMES ' . $connection['dbCharset']);
            $this->conn->query('SET CHARACTER SET ' . $connection['dbCharset']);
        } catch(PDOException $e) {
            self::showException($e);
        }
    }

    public static function showException($e)
    {
        echo '<pre>';
        echo '<b>' . $e->getMessage() . '</b><br/><br/>';
        echo $e->getTraceAsString();
        echo '</pre>';
    }

}

?>
