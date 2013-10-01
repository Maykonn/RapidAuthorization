<?php

/**
 * ClientPreferences
 * @author Maykonn Welington Candido<maykonn@outlook.com>
 */

namespace Rapid\Authorization;

class ClientPreferences
{

    /**
     * @var ArrayObject preferences to RapidAuthorization
     */
    private $preferencesList;

    /**
     * @var ClientPreferences
     */
    private static $instance;

    /**
     * @return ClientPreferences
     */
    public static function instance(Array $clientPreferences = Array())
    {
        if(self::$instance instanceof ClientPreferences) {
            return self::$instance;
        } else {
            return self::$instance = new self($clientPreferences);
        }
    }

    private function __construct($clientPreferences = Array())
    {
        $this->setPreferencesList($clientPreferences);
        $this->execute();
    }

    /**
     * Get client preferences list
     * @return ArrayObject
     */
    public function getPreferencesList()
    {
        return $this->preferencesList;
    }

    private function setPreferencesList(Array $clientPreferences)
    {
        $this->preferencesList = AvaiblePreferences::instance()->getList();
        $this->applyValuesFromClientPreferences($clientPreferences);
    }

    private function applyValuesFromClientPreferences(Array $clientPreferences)
    {
        foreach($clientPreferences as $property => $value) {
            try {
                if($this->preferencesList->offsetExists($property)) {
                    $this->preferencesList->offsetSet($property, $value);
                    $this->preferencesList->$property = $value;
                    $this->$property = $value;
                } else {
                    throw new Exception($property . " isn't a valid option");
                }
            } catch(Exception $e) {
                echo '<pre>';
                echo '<b>' . $e->getMessage() . '</b><br/><br/>';
                echo $e->getTraceAsString();
                echo '</pre>';
            }
        }
    }

    private function execute()
    {
        $this->prepareUserClassName();
        $this->initAutoload();
        // execute another prerences...
    }

    private function initAutoload()
    {
        if($this->preferencesList->useRapidAuthorizationAutoload) {
            Autoload::instance()->init();
        }

        return;
    }

    /**
     * <p>Remove .php from class name to Autoload</p>
     */
    private function prepareUserClassName()
    {
        $newUserClassName = str_replace('.php', null, $this->preferencesList->userClass);
        $this->preferencesList->userClass = $newUserClassName;
        $this->preferencesList->offsetSet('userClass', $newUserClassName);
        $this->userClass = $newUserClassName;
        return;
    }

}

?>
