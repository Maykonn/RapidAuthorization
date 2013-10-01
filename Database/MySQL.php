<?php

/**
 * MySQL
 * @author Maykonn Welington Candido<maykonn@outlook.com>
 */

namespace Rapid\Authorization\Database;

use \PDO;

class MySQL
{

    private $con;

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
            self::$instance = new PDO(
                "mysql:host=" . $connection['host'] .
                ';port=' . $connection['port'] .
                ';dbname=' . $connection['dbName'], $connection['user'], $connection['pass']
            );
            self::$instance->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_SILENT);
            self::$instance->query('SET NAMES ' . $connection['dbCharset']);
            self::$instance->query('SET CHARACTER SET ' . $connection['dbCharset']);
        } catch(PDOException $e) {
            echo $e->getMessage();
        }
    }

}

?>
