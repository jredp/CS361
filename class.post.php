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

	/* prints table of data with two icon links for edit and delete */
	public public function view() {
		$sql = "SELECT * FROM posts";
		$rs = $this->conn->prepare($sql);
		$rs->execute();

		if ($rs->rowCount() > 0) {
			echo "<table>";
			echo "<thead><tr><th>ID</th><th>Post date</th><th>Content</th></tr></thead>";
			echo "<tbody>";
			while ($row = $rs->fetch(PDO::FETCH_ASSOC)) {
				echo "<tr><td>" . $row["post_id"] . "</td><td>" . $row["post_date"] . "</td><td>" . $row["content"] . "</td>";
			}

		} else {
			echo "<tr><td colspan='5' class='text-center'>no records found</td></tr>";
		}
	}

}
?>
