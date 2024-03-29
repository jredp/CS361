<?php

/*
 * class to manage posts
 * three member variables: connection string, valid file extensions, and image directory
 * just normal crud, with removing user uploaded files during edit
 * and delete
 */

class post {
	private $conn;
	private $img_dir;
	private $valid_files;

	function __construct($dbcn) {
		$this->conn = $dbcn;
		$this->img_dir = 'postImages/';
		$this->valid_files = array('.png', '.jpeg', '.jpg', '.gif');
	}

	/*
	 * create a post for a given user
	 */
	public function create($username, $content, $imgurl) {
		try {
			$sql = "INSERT INTO posts (user_id, content, post_img) VALUES ";
			$sql .= "((select user_id from users where user_name = :user_name), ";
			$sql .= ":content, :post_img)";
			$rs = $this->conn->prepare($sql);
			$rs->bindparam(":user_name", $username);
			$rs->bindparam(":content", $content);
			$rs->bindparam(":post_img", $imgurl);
			$rs->execute();
			return true;
		} catch (PDOException $e) {
			/* echo $e->getMessage(); */
			return false;
		}
	}

	public function getImgDir() {
		return $this->img_dir;
	}

	public function getValidFileTypes() {
		return $this->valid_files;
	}

	/*
	 * get post information to populate edit and delete forms
	 */
	public function getId($id) {
		$rs = $this->conn->prepare("select * from posts where post_id = :id");
		$rs->bindparam(":id", $id);
		$rs->execute();
		$edit_row = $rs->fetch(PDO::FETCH_ASSOC);
		return $edit_row;
	}

	/*
	 * update a post and the associated image, if one exists
	 */
	public function update($post_id, $content, $imgurl, $oldimg=NULL) {
		try {
			$sql = "UPDATE posts SET content = :content, ";
			$sql .= "post_img = :post_img WHERE post_id=:post_id;";
			$rs = $this->conn->prepare($sql);
			$rs->bindparam(":post_id", $post_id);
			$rs->bindparam(":content", $content);
			$rs->bindparam(":post_img", $imgurl);
			$rs->execute();
			if (!is_null($oldimg)) {
				$image = realpath($this->img_dir . $oldimg);
				if (file_exists($image)) unlink($image);
			}
			return true;
		} catch (PDOException $e) {
			/* echo $e->getMessage(); */
			return false;
		}
	}

	/*
	 * delete a post and the associated image, if one exists
	 */
	public function delete($id, $post_img) {
		try {
			$rs = $this->conn->prepare("DELETE FROM posts WHERE post_id=:id");
			$rs->bindparam(":id", $id);
			$rs->execute();
			$image = realpath($this->img_dir . $post_img);
			if (file_exists($image)) unlink($image);
			return true;
		} catch (PDOException $e) {
			/* echo $e->getMessage(); */
			return false;
		} 
	}

	/*
	 * follow new post
	 */
	public function follow($post_id, $user_id) {
		try {
			$rs = $this->conn->prepare("INSERT INTO followed_posts (user_id, post_id) VALUES (:user_id, :post_id);");
			$rs->bindparam(":user_id", $user_id);
			$rs->bindparam(":post_id", $post_id);
			$rs->execute();
			return true;
		} catch (PDOException $e) {
			/* echo $e->getMessage(); */
			/* echo "User ID: " . $user_id; */
			/* echo "Post ID: " . $post_id; */
			return false;
		}		
	}

	/*
	 * unfollow post
	 */
	public function unfollow($post_id, $user_id) {
		try {
			$rs = $this->conn->prepare("DELETE FROM followed_posts WHERE user_id=:user_id AND post_id=:post_id");
			$rs->bindparam(":user_id", $user_id);
			$rs->bindparam(":post_id", $post_id);
			$rs->execute();
			return true;
		} catch (PDOException $e) {
			/* echo $e->getMessage(); */
			return false;
		}		
	}

	/* 
	 * prints table of data with two links for edit and delete
	 * $filter values: mine, following, local, all
	 * TODO: major refactor!!!
	 */
	public function view($curr_user, $filter, $value=NULL) {
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
			$sql .= ", user_name_author ";
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
					if (($curr_user == $row['user_name'] && $filter != 'following') || ($filter == 'following' && $curr_user == $row['user_name_author'])) {
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
					// show the create date and username
					if ($filter != 'following') {
						$html .= '<td align="right"><em>posted by ' . $row['user_name'] . ' on ' . $row['post_date'] . '</em></tr>';
					} else {
						$html .= '<td align="right"><em>posted by ' . $row['user_name_author'] . ' on ' . $row['post_date'] . '</em></tr>';
					}
					$html .= '<tr><td colspan="2"><hr align="center" width="100%"></td></tr>';
				}
			}
		} else {
			$html = '<tr><td colspan="4" class="text-center">no records found</td></tr>';
		}
		$html .= '</tbody></table>';
		return $html;
	}

	/* 
	 * prints table of data with two links for edit and delete
	 * $filter values: mine, following, local, all
	 * TODO: major refactor!!
	 */
	public function search($curr_user, $filter) {
		// Get current user's ID
		$sql = "SELECT * from users WHERE user_name = :user_name";
		$rs = $this->conn->prepare($sql);
		$rs->bindparam(":user_name", $curr_user);
		$rs->execute();
		$row = $rs->fetch();
		$user_id = $row['user_id'];

		// Select posts
		$filter = "%$filter%";
		$sql = "SELECT post_id, user_name, post_date, content, post_img ";
		$sql .= "from all_posts ";
		$sql .= "where content like :filter order by post_date desc";
		$rs = $this->conn->prepare($sql);
		$rs->bindparam(":filter", $filter);
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
					// show the create date and username
					if ($filter != 'following') {
						$html .= '<td align="right"><em>posted by ' . $row['user_name'] . ' on ' . $row['post_date'] . '</em></tr>';
					} else {
						$html .= '<td align="right"><em>posted by ' . $row['user_name_author'] . ' on ' . $row['post_date'] . '</em></tr>';
					}
					$html .= '<tr><td colspan="2"><hr align="center" width="100%"></td></tr>';
				}
			}
		} else {
			$html = '<tr><td colspan="4" class="text-center">no records found</td></tr>';
		}
		$html .= '</tbody></table>';
		return $html;
	}
}
?>
