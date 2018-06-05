<?php

/**
 * AvailablePreferences
 * @author Maykonn Welington Candido<maykonn@outlook.com>
 */

namespace RapidAuthorization;

use \ArrayObject;

class AvailablePreferences
{

	private $preferences = Array(
		'dbDriver' => 'pdo_mysql',
		'dbHost' => 'localhost',
		'dbPort' => 3306,
		'dbUser' => '',
		'dbPassword' => '',
		'dbName' => '',
		'dbConnCharset' => 'utf8',
		'userTable' => 'user',
		'userTablePK' => 'id',
		'autoGenerateTables' => false,
		'pdoInstance' => null, // pass your own pdo instance to doctrine/dbal
		//'useRapidAuthorizationAutoload' => false,
	);

	/**
	 * @var ArrayObject
	 */
	private $list = Array();

	/**
	 * @var AvailablePreferences
	 */
	private static $instance;

	/**
	 * @return AvailablePreferences
	 */
	public static function instance()
	{
		if (self::$instance instanceof AvailablePreferences) {
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
		if (count($this->list) === 0) {
			$this->list = new ArrayObject();

			foreach ($this->preferences as $property => $value) {
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
