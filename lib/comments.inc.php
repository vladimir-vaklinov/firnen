<?php

/**
 * <h1> Comments functionality</h1>
 * This class provides the comments add / list functionality
 *
 * @version 0.8
 * @since 2014-08-25
 */
class comments
{

	public $aid=0; // Album ID by default - 0
	public $phid=0; // Photo ID by default - 0


	/**
	 * <h1> Comments actions </h1>
	 * Once the comment is posted this method saves the
	 * information into the database.
	 */
	public function actions()
	{

		global $db, $ws;

		if(isset($_POST['action'])&&($_POST['action']=='addcomment')&&($_POST['captcha']==$_SESSION['captcha123']['code'])){

			$name=filter_var($_POST['name'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$comment=filter_var($_POST['comment'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);

			$db->query("INSERT INTO `comments` (`aid`,`phid`,`uname`,`comment`,`cdate`)
				   VALUES ('".$this->aid."','".$this->phid."','".$name."','".$comment."','".time()."') ");

			$ws->updateCapcha();
		}

	}

	/**
	 * <h1> Display the "Add comment" Form
	 * This method display's the form for posting a new comment
	 *
	 * @return string
	 */
	public function displayAddCommentForm()
	{

		$result='
		<div id="comment-block">
			<h2 id="comment-block-title">Add Comments:</h2>
			<form method="post" action="?#comment-block">
				<p><input type="text" name="name" id="comment-uname" value="" placeholder="Your Name..." required="required"/></p>
				<p><textarea name="comment" placeholder="Write a comment..." id="comment-text" required="required"></textarea></p>
				<p class="captcha">
					<label for="captcha">Enter the numbers from the image:</label>
					<img src="/assets/img/captcha/'.$_SESSION['captcha123']['img'].'" alt="Captcha"/>
					<input type="number" name="captcha" id="captcha" value="" required="required"/>
				</p>
				<p><button type="submit" id="comment-button">Comment</button></p>
				<input type="hidden" name="action" value="addcomment"/>
				<input type="hidden" name="aid" value="'.$this->aid.'"/>
				<input type="hidden" name="phid" value="'.$this->phid.'"/>
			</form>
		</div>';

		return $result;
	}


	/**
	 * <h1> Display Comments </h1>
	 * This method display's the existing comments
	 *
	 * @return string
	 */
	public function displayComments()
	{
		global $db;

		$result='';

		if($this->aid>0){
			$filter="`aid`='".$this->aid."' ";
		}else if($this->phid>0){
			$filter="`phid`='".$this->phid."' ";
		}else{
			return '<h2>Something went wrong here!?</h2>';
		}

		$q=$db->query("SELECT * FROM `comments` WHERE ".$filter." ORDER BY `cdate` DESC");
		if($db->num_rows($q)>0){
			$result.='
			<div id="comment-list">';

			while($a=$db->fetch_array($q)){
				$name=$a['uname'];
				if(empty($a['uname']))
					$name=' --- noname --- ';
				$result.='
					<div class="full-comment">
						<strong class="full-comment-name">'.$name.':</strong>
						<em class="full-comment-date">'.date('Y/m/d.', $a['cdate']).'</em>
						<p class="full-comment-comment">'.$a['comment'].'</p>
					</div>';
			}

			$result.='
			</div>';
		}

		return $result;
	}

}
