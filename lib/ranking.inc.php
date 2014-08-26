<?php

/**
 * <h1> Comments functionality</h1>
 * This class provides ranking system
 *
 * @version 0.8
 * @since 2014-08-25
 */
class votes
{

	public $aid=0; // Album ID by default - 0


	/**
	 * Save the vote to database.
	 */
	public function actionSaveVote()
	{

		global $db;


		if(isset($_POST['action'])&&($_POST['action']=='like'||$_POST['action']=='dislike')){
			$likes=1;
			$dislikes=0;
			if($_POST['action']=='like'){
				$likes=1;
				$dislikes=0;
			}
			if($_POST['action']=='dislike'){
				$likes=0;
				$dislikes=1;
			}
			$db->query("INSERT IGNORE INTO `votes` (`aid`,`ip`,`likes`,`dislikes`)
				VALUES ('".$this->aid."','".ip2long($_SERVER['REMOTE_ADDR'])."','".$likes."','".$dislikes."') ");
		}

	}

	/**
	 * Display votes per album
	 */
	public function displayVotes()
	{
		global $db;
		$q=$db->query("SELECT *, SUM(`likes`) AS `total_likes`, SUM(`dislikes`) AS `total_dislikes`
							FROM `votes` WHERE `aid`='".$this->aid."' ");
		if($db->num_rows($q)>0){
			while($a=$db->fetch_array($q)){
				$likes=$a['total_likes'];
				$dislikes=$a['total_dislikes'];
			}
		}
		if(empty($likes))
			$likes=0;
		if(empty($dislikes))
			$dislikes=0;

		$result='
		<div id="vote-block">
			<h2 id="vote-block-title">Like this album:</h2>
			<form method="post" action="?#vote-block" name="like" id="likeForm">
				<button type="submit" id="like">'.$likes.'</button>
				<input type="hidden" name="action" value="like"/>
			</form>
			<form method="post" action="?#vote-block" name="dislike" id="dislikeForm">
				<button type="submit" id="dislike">'.$dislikes.'</button>
				<input type="hidden" name="action" value="dislike"/>
			</form>
		</div>';

		return $result;
	}


}
