<?php

class post {
	private $conn;

	function __construct($dbcn) {
		$this->conn = $dbcn;
	}

	public function insert($content) {
		try {
			$sql = "INSERT INTO posts (content, user_id) VALUES (:content, :user_id)";
			$rs = $this->conn->prepare($sql);
			$rs->bindparam(":content", $content);
			$rs->bindparam(":user_id", $user_id);
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

	function getSelect($selIdx = NULL) {
		$list = '<option value="">select species</option>';
		$list .= '<option value="all">all species</option>';
		$rs = $conn->query("select species from pets group by 1 order by 1");
		while ($row = $rs->fetch_assoc()) {
			$list .= '<option value="' . $row['species'] . '"';
			if ($selIdx == $row['species']) {
				$list .= ' selected';
			}
			$list .= '>' . $row['species'] . '</option>';
		}
		echo $list;
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
	public function view($filter, $value=NULL) {
		$sql = "SELECT post_date, content, post_img ";

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
		echo $sql;
		$rs->execute();

		if ($rs->rowCount() > 0) {
			$html = '<table border="1"><thead><tr><th>content</th><th>image</th><th>date</th></tr></thead>';
			$html .= '<tbody>';
			while ($row = $rs->fetch(PDO::FETCH_ASSOC)) {
				$html .= '<tr>';
				$html .= '<td>' . $row['content'] . '</td>';
				if (!is_null($row['post_img']) && strlen(trim($row['post_img'])) > 0) {
					$html .= '<td><img src="postImages/' . $row['post_img'] . '"></td>';
				} else {
					$html .= '<td>&nbsp;</td>';
				}
				$html .= '<td>' . $row['post_date'] . '</td>';
				$html .= '</tr>';
			}
		} else {
			$html= "<tr><td colspan='3' class='text-center'>no records found</td></tr>";
		}
		return $html;
	}

}
?>
