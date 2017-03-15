<?php

/*
 Class to manage replies
 No images are associated with replies right now - none asked for
 */

class reply {
	private $conn;

	function __construct($dbcn) {
		$this->conn = $dbcn;
	}

	//create a reply
	public function createReply($username, $content) {
		try {
			$sql = "INSERT INTO reply_posts (user_id, content, post_id) VALUES ";
			$sql .= "((select user_id from users where user_name = :user_name), ";
			$sql .= ":content, :post_id)";
			$rs = $this->conn->prepare($sql);
			$rs->bindparam(":user_name", $username);
			$rs->bindparam(":content", $content);
			$rs->bindparam(":post_id", $postid);
			$rs->execute();
			return true;
		} catch (PDOException $e) {
			echo $e->getMessage();
			return false;
		}
	}
	
	//get replies based on post_id and order by reply_date
	public function getReplyId($id) {
		$rs = $this->conn->prepare("select * from reply_posts where post_id = :id order by reply_date");
		$rs->bindparam(":id", $id);
		$rs->execute();
		$edit_row = $rs->fetch(PDO::FETCH_ASSOC);
		return $edit_row;
	}

	//Update a reply
	public function updateReply($reply_id, $content) {
		try {
			$sql = "UPDATE reply_id SET content = :content, ";
			$sql .= "WHERE reply_id=:reply_id;";
			$rs = $this->conn->prepare($sql);
			$rs->bindparam(":reply_id", $reply_id);
			$rs->bindparam(":content", $content);			
			$rs->execute();			
			return true;
		} catch (PDOException $e) {
			echo $e->getMessage();
			return false;
		}
	}

	
	//Delete a reply	
	public function deleteReply($id) {
		try {
			$rs = $this->conn->prepare("DELETE FROM reply_posts WHERE reply_id=:id");
			$rs->bindparam(":id", $id);
			$rs->execute();			
			return true;
		} catch (PDOException $e) {
			echo $e->getMessage();
			return false;
		} 
	}

	/* 
	 * prints table of data with two links for edit and delete
	 * $filter values: mine, following, local, all
	 * TODO: refactor
	 */
	public function viewReply($curr_user, $filter, $value=NULL) {
		// Get current user's ID
		$sql = "SELECT * from users WHERE user_name = :user_name";
		$rs = $this->conn->prepare($sql);
		$rs->bindparam(":user_name", $curr_user);
		$rs->execute();
		$row = $rs->fetch();
		$user_id = $row['user_id'];

		// Select posts
		$sql = "SELECT post_id, user_name, post_date, content, post_img ";

		if ($filter == 'all') {
			$sql .= "from all_posts ";
		} else if ($filter == 'mine') {
			$sql .= "from all_posts ";
			$sql .= "where user_name = :value ";
		} else if ($filter == 'following') {
			$sql .= "from all_followed_posts ";
			$sql .= "where user_name = :value ";
		} else if ($filter == 'local') {
			$sql .= "from all_posts ";
			$sql .= "where user_zip = :value ";
		}
		$sql .= "order by post_date desc";
		$rs = $this->conn->prepare($sql);
		if ($filter != 'all')
			$rs->bindparam(":value", $value);
		/* echo $sql; */
		$rs->execute();

		if ($rs->rowCount() > 0) {
			$html = '<table border="0">';
			$html .= '<tbody>';
			while ($row = $rs->fetch(PDO::FETCH_ASSOC)) {
				// check for content, to avoid showing rows from the view that are blank for this user
				if (!is_null($row['content']) && strlen(trim($row['content'])) > 0) {
					// first row is image if there is one
					$html .= '<tr>';
					if (!is_null($row['post_img']) && strlen(trim($row['post_img'])) > 0) {
						$html .= '<td colspan="2"><img src="' . $this->img_dir . $row['post_img'] . '"></td>';
					} else {
						$html .= '<td colspan="2">&nbsp;</td>';
					}
					$html .= '<tr><td colspan="2">&nbsp;</td></tr>';
					// next row is content
					$html .= '<tr><td colspan="2">' . $row['content'] . '</td></tr>';
					// if the user owns the post show edit / delete links
					$html .= '<tr>';
					if (($curr_user == $row['user_name'] && $filter != 'following') || ($curr_user == $row['user_name_author'] && $filter == 'following')) {
						$html .= '<td><a href="edit-post.php?pid=' . $row['post_id'] . '">edit post</a>&nbsp;&nbsp;';
						$html .= '<a href="delete-post.php?pid=' . $row['post_id'] . '">delete post</a>';
					// if user already follows post, show unfollow button; else show follow button
					} else {
						$sql_follow = "SELECT user_id, post_id FROM followed_posts WHERE user_id=:user_id AND post_id=:post_id;";
						$rs_follow = $this->conn->prepare($sql_follow);
						$rs_follow->bindparam(":user_id", $user_id);
						$rs_follow->bindparam(":post_id", $row['post_id']);
						$rs_follow->execute();
						$html .= '<td><form method="post">';
						$html .= '<input type="hidden" name="user_id" value="' . $user_id . '">';
						$html .= '<input type="hidden" name="post_id" value="' . $row['post_id'] . '">';						
						if ($rs_follow->rowCount() > 0) {
							$html .= '<button type="submit" name="unfollow" value="unfollow">unfollow</button>';
						} else {
							$html .= '<button type="submit" name="follow" value="follow">follow</button>';
						}
						$html .= '</form>';
					}
					// show the create date
					$html .= '<td align="right"><em>original post date: ' . $row['post_date'] . '</em></tr>';
					$html .= '<tr><td colspan="2"><hr align="center" width="100%"></td></tr>';
				}
			}
		} else {
			$html= '<tr><td colspan="4" class="text-center">no records found</td></tr>';
		}
		$html .= '</tbody></table>';
		return $html;
	}
}
?>
