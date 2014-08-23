<?php

/**
 * <h1> View Album </h1>
 * View album page with all item.
 *
 * @version 0.4
 * @since 2014-08-22
 */

class viewAlbum{

    public function contents(){
        global $db;

        $aid=0;
        if(isset($_GET['task_1'])&&(is_numeric($_GET['task_1']))){
            $aid=intval($_GET['task_1']);
        }else{
            @header("Location:/");
        }

        $uid=0;
        $albumname='';
        $username='';
        $image='';

//		$q=$db->query("SELECT *,IFNULL( (SELECT `filename` FROM `photos` WHERE `photos`.`aid`=`albums`.`aid`
//						ORDER BY `phid` DESC LIMIT 1), '') AS `photo` FROM `albums` WHERE `aid`='".$aid."' ");
//		if($db->num_rows($q)>0){
//			while($a=$db->fetch_array($q)){
//				$uid=$a['uid'];
//				$albumname=$a['name'];
//				if(!empty($a['photo']))$image='/photos/'.$a['uid'].'/'.$a['photo'];
//			}
//		}
//
//		$qu=$db->query("SELECT * FROM `users` WHERE `uid`='".$uid."' ");
//		if($db->num_rows($qu)>0){
//			while($a=$db->fetch_array($qu)){
//				$username=$a['fname'];
//			}
//		}
        $page=10;

        $result='
		<div id="view-album">
		<h2 id="view-album-username"><img src="/assets/img/user_ico.png" alt="'.$username.'" />'.$username.'</h2>

        <div id="view-album-blk">';
        $q=$db->query("SELECT * FROM `photos` WHERE `aid`='".$aid."' ORDER BY `phid` DESC LIMIT ".$page);
        if($db->num_rows($q)>0){
            $result.='<div id="view-album-block">';
            while($a=$db->fetch_array($q)){

                $image='/photos/'.$a['uid'].'/t_'.$a['filename'];

                $result.='
					<a href="/photo/'.$a['phid'].'/" class="aimgi">
							<img src="'.$image.'" alt="'.$a['name'].'"/>
						</a><br>';

            }
        }
        $result.='
        </div>
        </div>
        <div>
        <h2 id="view-album-name">'.$albumname.'</h2>
        </div>
		<div id="comment-block">
        <form method="post">
        <p>
          <input type="text" name="name" id="comment-uname" value="" placeholder="Your Name..." />
        </p>
        <p>
           <textarea name="comment" placeholder="Write a comment..." id="comment-text"></textarea>
        </p>
        <p>
            <button type="submit" id="comment-button">Comment</button>
        </p>
        <input type="hidden" name="action" value="addcomment"/>
        </form>
        ';
        if(isset($_POST['action'])){
        $text=$_POST['comment'];
        $aname=$_POST['name'];
        $q=$db->query("INSERT INTO `comments` (`aid`,`uname`,`comment`,'cdate')
					   VALUES ('".$aid."','".$aname."','".$text."','".time()."') ");
        }

	    $result.='</div> </div>';


        return $result;

    }

}