<?php

/**
 * <h1> Search the website </h1>
 * Search function on the website
 *
 * @since 2014-08-25
 * @version 0.6
 */
class search
{


	/**
	 * <h1>Website search</h1>
	 * Performs database search for photos and albums
	 *
	 * @return string
	 */
	public function contents()
	{
		global $db, $ws;

		$result='';
		$albumsfound='';
		$photosfound='';

		if(!isset($_POST['search'])||(empty($_POST['search']))){
			return '<h1 class="">Enter keyword to search, and try again.</h1>';
		}

		$search=filter_var($_POST['search'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);


		$q=$db->query("SELECT *,
		             IFNULL(
		                (SELECT `filename` FROM `photos` WHERE `photos`.`aid`=`albums`.`aid`
		                    ORDER BY `phid` DESC LIMIT 1), '') AS `photo`
		               FROM `albums` WHERE (`name` LIKE '".$search."%' OR `name` LIKE '%".$search."%') ");
		if($db->num_rows($q)>0){
			while($a=$db->fetch_array($q)){
				$albumsfound.=$ws->albumPreview(0, $a['aid'], $a['uid'], $a['photo'], $a['name'], $a['created']);
			}
		}else{
			$albumsfound.='<h2 class="errormsg">We didn&lsquo;t find any albums with that keyword</h2>';
		}

		$q=$db->query("SELECT * FROM `photos` WHERE (`name` LIKE '".$search."%' OR `name` LIKE '%".$search."%') ");
		if($db->num_rows($q)>0){
			while($a=$db->fetch_array($q)){
				$photosfound.=$ws->photoPreview(0, $a['phid'], $a['uid'], $a['filename'], $a['name'], $a['created']);
			}
		}else{
			$photosfound.='<h2 class="errormsg">We didn&lsquo;t find any photos with that keyword</h2>';
		}

		$result.='
		<div id="search-results">
			<div id="search-results-albums">
				<h1 class="search-results-title">Found albums:</h1>
				<div class="search-results-items">
					'.$albumsfound.'
				</div>
			</div>
			<div id="search-results-photos">
				<h1 class="search-results-title">Found photos:</h1>
				<div class="search-results-items">
					'.$photosfound.'
				</div>
			</div>
		</div>';


		return $result;
	}

}