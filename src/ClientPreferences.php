<?php

/**
 * ClientPreferences
 * @author Maykonn Welington Candido<maykonn@outlook.com>
 */

namespace RapidAuthorization;

class ClientPreferences
{

    /**
     * @var \ArrayObject preferences to RapidAuthorization
     */
    private $preferencesList;

    /**
     * @var ClientPreferences
     */
    private static $instance;

    /**
     * @param array $clientPreferences
     *
     * @return ClientPreferences
     */
    public static function instance(Array $clientPreferences = Array())
    {
        if (self::$instance instanceof ClientPreferences) {
            return self::$instance;
        }

        return self::$instance = new self($clientPreferences);
    }

    private function __construct($clientPreferences = Array())
    {
        $this->setPreferencesList($clientPreferences);
        /*if ($this->preferencesList->useRapidAuthorizationAutoload) {
			Autoload::instance()->init();
		}*/
    }

    /**
     * Get client preferences list
     * @return \ArrayObject
     */
    public function getPreferencesList()
    {
        return $this->preferencesList;
    }

    private function setPreferencesList(Array $clientPreferences)
    {
        $this->preferencesList = AvailablePreferences::instance()->getList();
        $this->applyValuesFromClientPreferences($clientPreferences);
    }

    private function applyValuesFromClientPreferences(Array $clientPreferences)
    {
        foreach ($clientPreferences as $property => $value) {
            if ($this->preferencesList->offsetExists($property)) {
                $this->preferencesList->offsetSet($property, $value);
                $this->preferencesList->$property = $value;
                $this->$property = $value;
            }
        }
    }

}
