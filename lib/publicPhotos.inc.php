<?php

/**
 * <h1>Public Photos</h1>
 * Photos visible to all users.
 *
 * @version 0.3
 * @since 2014-08-21
 */
class publicPhotos
{


	public function showAll()
	{
		global $db, $ws;

		$page=0;
		$resultperpage=8;

		if(isset($_GET['task_1']))
			$page=intval($_GET['task_1']);


		$qt=$db->query("SELECT * FROM `photos` ");
		$total=$db->num_rows($qt);

		$result='
		<div id="publicphotos">
			<h1>Latest Uploads</h1>
			<div id="p-albums-items">';

		$q=$db->query("SELECT * FROM `photos` ORDER BY `phid` DESC LIMIT ".$page.",".$resultperpage);
		if($db->num_rows($q)>0){
			while($a=$db->fetch_array($q)){

				$result.=$ws->photoPreview(0, $a['phid'], $a['uid'], $a['filename'], $a['name'], $a['created']);

			}
		}

		$result.='
			</div>
		</div>';


		if($total>$resultperpage){

			$result.='
			<div id="paging">';

			if($page>0){
				$backpage=($page-$resultperpage);
				$result.='<a href="/gallery/'.$backpage.'/" class="prev">&laquo;</a>';
			}

			$a=0;
			$b=0;

			while($a<$total){
				$a=$a+$resultperpage;
				if(($b<(($page/$resultperpage)+10))&&($b>(($page/$resultperpage)-10))){
					$result.='<a href="/gallery/'.($b*$resultperpage).'/"';
					if($page==($b*$resultperpage))
						$result.=' class="sel"';
					$result.='>'.($b+1).'</a>';
				}
				$b++;
			}
			if($page<($total-$resultperpage)){
				$nextpage=($page+$resultperpage);
				$result.='<a href="/gallery/'.$nextpage.'/" class="next">&raquo;</a>';
			}
			$result.='
			</div>';
		}

		return $result;

	}


}