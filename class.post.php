<?php

class post {
    private $conn;

    function __construct($dbcn) {
	$this->conn = $dbcn;
    }

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
	    echo $e->getMessage();
	    return false;
	}
    }

    public function update($post_id, $content) {
	try {
	    $sql = "UPDATE posts SET content=:content WHERE post_id=:post_id;";
	    $rs = $this->conn->prepare($sql);
	    $rs->bindparam(":post_id", $post_id);
	    $rs->bindparam(":content", $content);
	    $rs->execute();
	    return true;
	} catch (PDOException $e) {
	    echo $e->getMessage();
	    return false;
	}
    }

    public function delete($id) {
	try {
	    $rs = $this->conn->prepare("DELETE FROM posts WHERE post_id=:id");
	    $rs->bindparam(":id", $id);
	    $rs->execute();
	    return true;
	} catch (PDOException $e) {
	    echo $e->getMessage();
	    return false;
	} 
    }

    // prints table of data with two icon links for edit and delete
    // $filter values: mine, following, local, all
    // TODO: refactor
    public function view($curr_user, $filter, $value=NULL) {
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
		    $html .= '<tr>';
		    if (!is_null($row['post_img']) && strlen(trim($row['post_img'])) > 0) {
			$html .= '<td colspan="2"><img src="postImages/' . $row['post_img'] . '"></td>';
		    } else {
			$html .= '<td colspan="2">&nbsp;</td>';
		    }
		    $html .= '<tr><td colspan="2">&nbsp;</td></tr>';
		    $html .= '<tr><td colspan="2">' . $row['content'] . '</td></tr>';
		    $html .= '<tr>';
		    if ($curr_user == $row['user_name']) {
			$html .= '<td><a href="edit-post.php?pid=' . $row['post_id'] . '">edit post</a>&nbsp;&nbsp;';
			$html .= '<a href="delete-post.php?pid=' . $row['post_id'] . '">delete post</a>';
		    } else {
			$html .= '<td>&nbsp;</td>';
		    }
		    $html .= '<td align="right"><em>original post date: ' . $row['post_date'] . '</em></tr>';
		    $html .= '<tr><td><hr align="center" width="100%"></td></tr>';
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
