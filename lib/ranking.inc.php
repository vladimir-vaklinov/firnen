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

		global $db, $ws;
		if(isset($_POST['action'])&&($_POST['like'])){
			$likes=1; $dislikes=0;
		}
		if(isset($_POST['action'])&&($_POST['like'])){
			$likes=0; $dislikes=1;
		}
		
		$db->query("INSERT INTO `votes` (`aid`,`likes`,`dislikes`)
		VALUES ('".$this->aid."','".ip2long($_SERVER['REMOTE_ADDR'])."','".$likes."','".$dislikes."') ");
		
	}

	/**
	 * Display votes per album
	 */
	public function displayVotes(){
		global $db;
		$vote=$db->query("SELECT * SUM(`likes`) AS `total_likes` FROM `votes` WHERE `aid`='3'");
		$dislikes=$db->query("SELECT * SUM(`dislikes`) AS `total_dislikes` FROM `votes` WHERE `aid`='3'");

		$result='
		
		<div id="vote-block">
			<h2 id="vote-block-title">Like this album:</h2>
			<form method="post" action="?#vote-block" name="like" id="likeForm">
				<input type="submit" name="likes" value="" id="like"/>
				<label for="like">'.$vote.'</label>
				<input type="hidden" name="action" value="vote"/>
				<input type="hidden" name="aid" value="'.$this->aid.'"/>
			</form>
			<form method="post" action="?#vote-block" name="dislike" id="dislikeForm">
				<input type="submit" name="dislikes" value="" id="dislike"/>
				<label for="like">'.$dislikes.'</label>
				<input type="hidden" name="action" value="dislike"/>
				<input type="hidden" name="aid" value="'.$this->aid.'"/>
			</form>
		</div>';

	  return $result;
	}


}
