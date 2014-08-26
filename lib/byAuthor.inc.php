<?php

/**
 * <h1> By Author </h1>
 * View albums by author
 *
 * @since 2014-08-24
 * @version 0.6
 */
class byAuthor
{


	public function contents()
	{

		global $db, $ws;

		$result='';

		$q=$db->query("SELECT * FROM `users` ORDER BY `fname` ASC ");

		if($db->num_rows($q)>0){
			while($a=$db->fetch_array($q)){

				$qin=$db->query("SELECT *,
				             IFNULL(
				                (SELECT `filename` FROM `photos` WHERE `photos`.`aid`=`albums`.`aid`
				                    ORDER BY `phid` DESC LIMIT 1), '') AS `photo`
								FROM `albums` WHERE `uid`='".$a['uid']."' ORDER BY `aid` DESC");

				if($db->num_rows($qin)>0){

					$result.='
					<div id="albums-blk">
						<h2>'.$a['fname'].' '.$a['lname'].'&lsquo;s albums:</h2>
						<div id="albums-blk-items">';

					while($b=$db->fetch_array($qin)){

						$result.=$ws->albumPreview(0, $b['aid'], $b['uid'], $b['photo'], $b['name'], $b['created']);

					}

					$result.='
						</div>
					</div>';

				}

			}
		}


		return $result;

	}
}