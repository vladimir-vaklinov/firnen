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

		if(isset($_POST['action'])&&($_POST['action']=='like')){

			$name=filter_var($_POST['name'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			// tuk kak da vkaram $vote v bazata i kak da proverq kolko e tekushtoto
			$vote++; 

			$db->query("INSERT INTO `likes` (`aid`,`uname`,`likes`,`cdate`)
				   VALUES ('".$this->aid."','".$name."','".$vote."','".time()."') ");

			$ws->updateCapcha();
		}

	}

	/**
	 * Display votes per album
	 */
	public function displayVotes()
	{

		$result='
		<div id="vote-block">
			<h2 id="vote-block-title">Like this album:</h2>
			<form method="post" action="?#vote-block" name="like">
				<input type="submit" src="../assets/img/like_it.jpg" name="submit" alt="submit" />
				<p class="votes">$vote</p>
				<input type="hidden" name="action" value="like"/>
				<input type="hidden" name="aid" value="'.$this->aid.'"/>
			</form>
		</div>';

		return $result;
	}



}
