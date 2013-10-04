<?php

/**
 * Autoload
 * Você deve utilizar o autoload do seu projeto. Esse aqui é fornecido apenas para testes.
 * @author Maykonn Welington Candido<maykonn@outlook.com>
 */

namespace Rapid\Authorization;

class Autoload
{

    /**
     * @var Autoload
     */
    private static $instance;

    /**
     * @return Autoload
     */
    public static function instance()
    {
        if(self::$instance instanceof Autoload) {
            return self::$instance;
        } else {
            return self::$instance = new self();
        }
    }

    private function __construct()
    {

    }

    public function init()
    {
        spl_autoload_register(array($this, 'loader'));
    }

    private function loader($className)
    {
        require $dir = dirname(dirname(__DIR__)) . DIRECTORY_SEPARATOR . $className . '.php';
    }

}

?>
