<?php
/**
 * IEntity
 *
 * @author Maykonn Welington Candido<maykonn@outlook.com>
 */

namespace RapidAuthorization;

interface IEntity
{
	public static function instance(ClientPreferences $preferences, \PDO $pdo);
}