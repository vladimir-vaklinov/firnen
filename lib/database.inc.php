<?php

/**
 * <h1>Class SQL</h1>
 * This is a special class designed to provide
 * access to the database. Currently uses the
 * new MySQLi / PHP driver to connect the
 * MySQL server.
 *
 * @author Vasil Tsintsev
 * @version 0.2
 * @since 2014-08-17
 */

class SQL{

	public $conn=null;

	public function __construct($dbases){

		$this->conn=mysqli_connect($dbases['host'],$dbases['dbuser'],$dbases['dbpass']);

		if($this->conn){
			if(mysqli_select_db($this->conn,$dbases['database'])){
				mysqli_query($this->conn,"SET NAMES UTF8");
			}
		}

		return true;
	}

	public function query($query,$dump=0){
		if($dump == 1)echo $query;

		$q=mysqli_query($this->conn,$query);
		if($q !== false){
			return $q;
		}else{
			print mysqli_error($this->conn);
			return false;
		}
	}

	public function num_rows($query){
		if($query == false)return 0;
		$q=mysqli_num_rows($query);
		if($q !== false){
			return $q;
		}else{
			print mysqli_error($this->conn);
			return 0;
		}
	}

	public function fetch_array($query){
		$q=mysqli_fetch_array($query);
		if($query !== false){
			if($q !== false){
				return $q;
			}else{
				print 'Query returns false';
				print mysqli_error($this->conn);
				return false;
			}
		}else{
			print 'Query returns false';
			print mysqli_error($this->conn);
			return false;
		}
	}

	public function insert_id(){
		return mysqli_insert_id($this->conn);
	}

	public function real_escape_string($str){
		return mysqli_real_escape_string($this->conn,$str);
	}

	public function __destruct(){
		return mysqli_close($this->conn);
	}
}