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
		if(isset($_POST['action'])&&($_POST['action']=='vote')){

			$likes=1;$dislikes=0;
			if(isset($_POST['likes']) && ($_POST['likes'] == 2)){
				$likes=0; $dislikes=1;
			}
			$db->query("INSERT INTO `votes` (`aid`,`ip`,`likes`,`dislikes`)
			VALUES ('".$this->aid."','".ip2long($_SERVER['REMOTE_ADDR'])."','".$likes."','".$dislikes."') ");
		}	
	}

	/**
	 * Display votes per album
	 */
	public function displayVotes(){
		$vote=0;$dislikes=0;

		$result='
		<div id="vote-block">
			<h2 id="vote-block-title">Like this album:</h2>
			<form method="post" action="?#vote-block" name="like">
				<p class="current-votes"><strong>Likes:</strong> '.$vote.' | <strong>Dislikes:</strong> '.$dislikes.'</p>
				<p>
					<input type="radio" name="likes" value="1" onclick="this.form.submit()" id="like"/>
					<input type="radio" name="likes" value="2" onclick="this.form.submit()" id="dislike"/>
				</p>
				<input type="hidden" name="action" value="vote"/>
				<input type="hidden" name="aid" value="'.$this->aid.'"/>
			</form>
		</div>';

	  return $result;
	}


}
