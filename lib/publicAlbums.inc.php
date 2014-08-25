<?php

/**
 * <h1> Albums </h1>
 *
 * @version 0.4
 * @since 2014-08-22
 */
class publicAlbums
{


	public function contents()
	{

		global $db, $ws;

		$result='';

		$q=$db->query("SELECT *,
		             IFNULL(
		                (SELECT `filename` FROM `photos` WHERE `photos`.`aid`=`albums`.`aid`
		                    ORDER BY `phid` DESC LIMIT 1), '') AS `photo`
						FROM `albums` ORDER BY `aid` DESC");

		if($db->num_rows($q)>0){

			$result.='
			<div id="albums-blk">
				<h2>Albums:</h2>
				<div id="albums-blk-items">';

			while($a=$db->fetch_array($q)){

				$result.=$ws->albumPreview(0, $a['aid'], $a['uid'], $a['photo'], $a['name'], $a['created']);

			}

			$result.='
				</div>
			</div>';

		}


		return $result;

	}


}