<?php
/**
 * @author Matt Wolfman
 * @auther Terry Chern
 * @version 2.0
 * @since 4/19/2016
 */
require_once($_SERVER['DOCUMENT_ROOT'] . '\data\db-info.php');

class DatabaseConnection{
	
	protected static $db;
	/**
	 * A singleton function that connects to the database.
	 */
	public static function get_connection()
	{
		if (!self::$db) {
			try {
				$database = 'mysql:dbname='. SCHEMA .';host='. SERVER .';port=3306';
				self::$db = new PDO($database, USERNAME, PASSWD);
				self::$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			} catch (PDOException $e) {
				die("Error: " . $e->getMessage());
			}
		}
		return self::$db;
	}
	/**
	 * Closes the databse connection
	 */	
	public static function close_connection()
	{
		if(!self::$db) {
			return;
		} else {
			self::$db == null;
		}
	}
}
?>