<?php
/**
 * NiceDog framework
 *
 * LocalDB:
 *   Postgre class
 * @author Elber Ribeiro
 * @version 0.1
 * @created 02-abr-2009 16:49
 */

class LocalDB extends PgDB
{
	function __construct($query = "")
	{
		$this->host = "localhost";
		$this->user = "postgres";
		$this->password = "postgres";
		$this->port = "5432";
		$this->database = "database";

		parent::__construct($query);
	}
}

