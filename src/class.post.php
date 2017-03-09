<?php

class post {
	private $conn;

	function __construct($dbcn) {
		$this->conn = $dbcn;
	}

	function insert($content) {
		try {
			$sql = "INSERT INTO posts (content) VALUES (:content)";
			$rs = $this->conn->prepare($sql);
			$rs->bindparam(":content", $content);
			$rs->execute();
			return true;
		} catch (PDOException $e) {
			return false;
		}
	}

	function update($post_id, $content) {
		try {
			$sql = "UPDATE posts SET content=:content WHERE post_id=:post_id;";
			$rs = $this->conn->prepare($sql);
			$rs->bindparam(":post_id", $post_id);
			$rs->bindparam(":content", $content);
			$rs->execute();
			return true;
		} catch (PDOException $e) {
			return false;
		}
	}

	function delete($id) {
		$rs = $this->conn->prepare("DELETE FROM posts WHERE post_id=:id");
		$rs->bindparam(":id", $id);
		$rs->execute();
		return true;
	}

  	/* prints table of data with two icon links for edit and delete */
	public function view() {
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