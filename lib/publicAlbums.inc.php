<?php

/**
 * <h1> Albums </h1>
 *
 * @version 0.4
 * @since 2014-08-22
 */

class publicAlbums{


	public function contents(){

		global $db;

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

					$image='/assets/img/no-image.jpg';
					if(!empty($a['photo'])){
						$image='/photos/'.$a['uid'].'/t_'.$a['photo'];
					}
					$result.='
					<div class="album-block">
						<a href="/album/'.$a['aid'].'/" class="aimgi">
							<img src="'.$image.'" alt="'.$a['name'].'"/>
						</a>
						<div class="albums-inf">
							<strong>'.$a['name'].'</strong>
						</div>
					</div>
					';

				}

			$result.='
				</div>
			</div>';

		}


		return $result;

	}


}