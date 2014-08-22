<?php

/**
 * <h1>Class SQL</h1>
 * This is a special class designed to provide
 * access to the database. Currently uses the
 * new MySQLi / PHP driver to connect the
 * MySQL server.
 *
 * @version 0.2
 * @since 2014-08-17
 */
class SQL
{

	public $conn=null;
	public $error=null;


	/**
	 * @param $dbases Array MySQL connection db credentials
	 */

	public function __construct($dbases)
	{

		$this->conn=mysqli_connect($dbases['host'], $dbases['dbuser'], $dbases['dbpass']);

		if($this->conn){
			if(mysqli_select_db($this->conn, $dbases['database'])){
				mysqli_query($this->conn, "SET NAMES UTF8");
			}
		}

		return true;
	}


	/**
	 * Executes MySQL query
	 *
	 * @param $query String MySQL query string
	 * @param bool $dump Boolean If - true , prints the query for debug
	 * @return bool|mysqli_result
	 */

	public function query($query, $dump=false)
	{
		if($dump==true)
			print $query;

		$q=mysqli_query($this->conn, $query);
		if($q!==false){
			return $q;
		}else{
			$this->error=mysqli_error($this->conn);
			return false;
		}
	}

	/**
	 * Returns the number of rows found after MySQL query
	 *
	 * @param $query MySQLi_result MySQL Query Resource
	 * @return int
	 */

	public function num_rows($query)
	{
		if($query==false)
			return 0;
		$q=mysqli_num_rows($query);
		if($q!==false){
			return $q;
		}else{
			$this->error=mysqli_error($this->conn);
			return 0;
		}
	}


	/**
	 * Fetches MySQL result from query
	 *
	 * @param $query MySQLi_result MySQL Query Resource
	 * @return array|bool|null
	 */

	public function fetch_array($query)
	{
		$q=mysqli_fetch_array($query);
		if($query!==false){
			if($q!==false){
				return $q;
			}else{
				$this->error=mysqli_error($this->conn);
				return false;
			}
		}else{
			$this->error=mysqli_error($this->conn);
			return false;
		}
	}


	/**
	 * Returns the last inserted ID
	 * @return int|string
	 */

	public function insert_id()
	{
		return mysqli_insert_id($this->conn);
	}


	/**
	 * Closes MySQL connection
	 */

	public function __destruct()
	{
		return mysqli_close($this->conn);
	}
}