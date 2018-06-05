<?php
/**
 * DB
 * @author Maykonn Welington Candido<maykonn@outlook.com>
 */


namespace RapidAuthorization\Database;


use Doctrine\DBAL\Configuration;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DriverManager;

class DB
{
    private static $connection;
    private static $connectionParams;

    public static function connect(\ArrayObject $params)
    {
        if (self::$connection instanceof Connection) {
            return self::$connection;
        }

        try {
            return self::$connection = DriverManager::getConnection(
                self::mountConnectionParams($params),
                new Configuration()
            );
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
    }

    private static function mountConnectionParams(\ArrayObject $params)
    {
        self::$connectionParams = Array(
            'driver' => $params['dbDriver'],
            'host' => $params['dbHost'],
            'port' => (int) $params['dbPort'],
            'user' => $params['dbUser'],
            'password' => $params['dbPassword'],
            'dbname' => $params['dbName'],
            'charset' => $params['dbConnCharset'],
        );

        if ( ! empty($params['pdoInstance'])) {
            self::$connectionParams['pdo'] = $params['pdoInstance'];
        }

        return self::$connectionParams;
    }
}
