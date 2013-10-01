<?php

/**
 * AvaiblePreferences
 * @author Maykonn Welington Candido<maykonn@outlook.com>
 */

namespace Rapid\Authorization;

use \ArrayObject;

class AvaiblePreferences
{

    private $mysqlHost = 'localhost';
    private $mysqlPort = 3306;
    private $mysqlUser = 'root';
    private $mysqlPass = '';
    private $dbName = '';
    private $dbCharset = 'utf8';

    /**
     * <p>Auto generate, or not, the necessary tables on database</p>
     * @var boolean
     */
    private $autoGenerateTables = true;

    /**
     * <p>Use, or not, the autoload provided by RapidAuthorization.</p>
     * <p>Set to false to use autoload provided by client application.<br/>
     * Client application must set to true if needed.</p>
     *
     * @var boolean
     */
    private $useRapidAuthorizationAutoload = false;

    /**
     * @var AvaiblePreferences
     */
    private static $instance;

    /**
     * @var ArrayObject
     */
    private $list = Array();

    /**
     * @return AvaiblePreferences
     */
    public static function instance()
    {
        if(self::$instance instanceof AvaiblePreferences) {
            return self::$instance;
        } else {
            return self::$instance = new self();
        }
    }

    private function __construct()
    {
        $this->createList();
    }

    private function createList()
    {
        if(count($this->list) === 0) {
            $this->list = new ArrayObject();
            $list = get_class_vars(get_class($this));
            unset($list['instance']);
            unset($list['list']);

            foreach($list as $property => $value) {
                $this->list->offsetSet($property, $value);
                $this->list->$property = $value;
                $this->$property = $value;
            }
        }
    }

    public function getList()
    {
        return $this->list;
    }

}

?>
