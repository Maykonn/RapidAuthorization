<?php

/**
 * MySQL
 * @author Maykonn Welington Candido<maykonn@outlook.com>
 */

namespace Rapid\Authorization\Database;

class MySQL
{

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

}

?>
