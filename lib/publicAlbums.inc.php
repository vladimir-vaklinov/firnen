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
		$page=0;
		$resultperpage=8;

		if(isset($_GET['task_1']))
			$page=intval($_GET['task_1']);

		$qt=$db->query("SELECT * FROM `albums` ");
		$total = $db->num_rows($qt);

		$q=$db->query("SELECT *,
		             IFNULL(
		                (SELECT `filename` FROM `photos` WHERE `photos`.`aid`=`albums`.`aid`
		                    ORDER BY `phid` DESC LIMIT 1), '') AS `photo`,
		             IFNULL(
		                (SELECT SUM(`likes`) as `alllikes` FROM `votes` WHERE `votes`.`aid`=`albums`.`aid`),'0')
		                	AS `alllikes`,
		             IFNULL(
		                (SELECT SUM(`dislikes`) as `alldislikes` FROM `votes` WHERE `votes`.`aid`=`albums`.`aid`),'0')
		                	AS `alldislikes`,
		             IFNULL(
		                (SELECT COUNT(`cid`) as `comments` FROM `comments` WHERE `comments`.`aid`=`albums`.`aid`),'0')
		                	AS `comments`
						FROM `albums` ORDER BY `alllikes` DESC,`alldislikes` ASC,`aid` DESC LIMIT ".$page.",".$resultperpage);

		if($db->num_rows($q)>0){

			$result.='
			<div id="albums-blk">
				<h2>Albums:</h2>
				<div id="albums-blk-items">';

			while($a=$db->fetch_array($q)){

				$result.=
				$ws->albumPreview(0,
					$a['aid'],
					$a['uid'],
					$a['photo'], $a['name'], $a['created'],$a['alllikes'],$a['alldislikes'],$a['comments']);

			}

			$result.='
				</div>
			</div>';

		}

		if($total>$resultperpage){

			$result.='
			<div id="paging">';

			if($page>0){
				$backpage=($page-$resultperpage);
				$result.='<a href="/albums/'.$backpage.'/" class="prev">&laquo;</a>';
			}

			$a=0;
			$b=0;

			while($a<$total){
				$a=$a+$resultperpage;
				if(($b<(($page/$resultperpage)+10))&&($b>(($page/$resultperpage)-10))){
					$result.='<a href="/albums/'.($b*$resultperpage).'/"';
					if($page==($b*$resultperpage))
						$result.=' class="sel"';
					$result.='>'.($b+1).'</a>';
				}
				$b++;
			}
			if($page<($total-$resultperpage)){
				$nextpage=($page+$resultperpage);
				$result.='<a href="/albums/'.$nextpage.'/" class="next">&raquo;</a>';
			}
			$result.='
			</div>';
		}


		return $result;

	}


}