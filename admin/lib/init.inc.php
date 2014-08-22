<?php

class initAdmin
{

	public function __construct()
	{

		/**
		 * Initiation for administrator login state
		 */
		if(!isset($_SESSION['firnen_admin_login']))
			$_SESSION['firnen_admin_login']=0;

	}


}