<?php
$inc = dirname(dirname(__FILE__)) . '/dbcn.php';
require_once $inc;

runTests($hostname, $username, $password, $database, $mypost);

function test($testname, $expected, $actual) {
	if ($expected == $actual)
		$prefix = 'PASS: ';
	else
		$prefix = 'FAIL: ';
	echo $prefix . $testname . " expected: " . $expected . ", got: ". $actual . "<br>";
}

function runTests($hostname, $username, $password, $database, $mypost) {
	$new_img = 'cwiTest.jpg';
	$lastid = createNoImageTest($hostname, $username, $password, $database, $mypost);
	updateWithContentTest($hostname, $username, $password, $database, $mypost, $lastid);
	updateNoContentTest($hostname, $username, $password, $database, $mypost, $lastid);
	deleteNoImageTest($hostname, $username, $password, $database, $mypost, $lastid);
	$lastid = createWithImageTest($hostname, $username, $password, $database, $mypost, $new_img); 
	if ($lastid) deleteWithImageTest($hostname, $username, $password, $database, $mypost, $lastid, $new_img);
	createNoContentTest($hostname, $username, $password, $database, $mypost);
}

function createNoImageTest($hostname, $username, $password, $database, $mypost) {
	$user_name = 'test1';
	$content = 'a test post';
	$sql = 'select count(*) num_posts from posts';

	// get number of records currently in db
	$pre_count = getNumberOfRecords(
		$hostname, $username, $password, $database, 'posts');

	// create a new post
	$mypost->create($user_name, $content, null);

	// get the max id, since the last id isn't returned by the class
	$sql = 'select max(post_id) from posts';
	$mysqli = new mysqli($hostname, $username, $password, $database);
	$stmt = $mysqli->prepare($sql);
	$stmt->execute();
	$stmt->bind_result($lastid);
	$stmt->fetch();

	// get number of records now in db
	$post_count = getNumberOfRecords(
		$hostname, $username, $password, $database, 'posts');

	test('create post no image test', $pre_count + 1, $post_count);
	return $lastid;
}

function deleteNoImageTest(
	$hostname, $username, $password, $database, $mypost, $lastid) {
	$mypost->delete($lastid, null);
	// make sure it's gone
	$where_clause = ' where post_id = ' . $lastid;
	$post_count = getNumberOfRecords(
		$hostname, $username, $password, $database, 'posts', $where_clause); 
	test('delete post no image test', 0, $post_count);
}

// file permissions are different when image is copied by script rather than
// uploaded via form. the owner is still www but the group is different
// some parts are commented out so we can run locally
// will test on public web server
function createWithImageTest(
	$hostname, $username, $password, $database, $mypost, $new_img) {

	/* $image_src = dirname(dirname(__FILE__)) . '/' . $mypost->getImgDir() .'post1_img.jpg'; */
	/* $image_dest = dirname(dirname(__FILE__)) . '/' . $mypost->getImgDir() . $new_img; */
	/* copy($image_src, $image_dest); */

	$user_name = 'test1';
	$content = 'a test post';
	$sql = 'select count(*) num_posts from posts';

	// get number of records currently in db
	$pre_count = getNumberOfRecords(
		$hostname, $username, $password, $database, 'posts');

	// create a new post
	$mypost->create($user_name, $content, $new_img);

	// get the max id, since the last id isn't returned by the class
	$sql = 'select max(post_id) from posts';
	$mysqli = new mysqli($hostname, $username, $password, $database);
	$stmt = $mysqli->prepare($sql);
	$stmt->execute();
	$stmt->bind_result($lastid);
	$stmt->fetch();

	// get number of records now in db
	$post_count = getNumberOfRecords(
		$hostname, $username, $password, $database, 'posts');

	/* if (file_exists($image_dest)) { */
		test('create post with image test', $pre_count + 1, $post_count);
		return $lastid;
	/* } else { */
		/* test('create post with image test', 0, 1); */
		/* return 0; */
	/* } */
}

// file permissions created during createWithImageTest are such that
// we can't delete. parts commented out until we can verify on web server
function deleteWithImageTest(
	$hostname, $username, $password, $database, $mypost, $lastid, $new_img) {
	$mypost->delete($lastid, $new_img);
	// make sure it's gone
	$where_clause = ' where post_id = ' . $lastid;
	$post_count = getNumberOfRecords(
		$hostname, $username, $password, $database, 'posts', $where_clause); 

	/* $image_dest = dirname(dirname(__FILE__)) . '/' . $mypost->getImgDir() . $new_img; */
	/* if (!file_exists($image_dest)) { */
		test('delete post with image test', 0, $post_count);
	/* } else { */
		/* test('delete post with image test', 0, 1); */
	/* } */
}

function createNoContentTest($hostname, $username, $password, $database, $mypost) {
	$user_name = 'test1';
	$content = null;

	// create a new post with no content and no image
	if (!$mypost->create($user_name, $content, null)) {
		test('create post with no content test', 1, 1);
	} else {
		test('create post with no content test', 1, 0);
	}
}

function updateWithContentTest($hostname, $username, $password, $database, $mypost, $lastid) {
	$content = 'updateWithContentTest';
	$mypost->update($lastid, $content, null);
	$sql = "select post_id from posts where content like '%" . $content . "%'";
	$mysqli = new mysqli($hostname, $username, $password, $database);
	$stmt = $mysqli->prepare($sql);
	$stmt->execute();
	$stmt->bind_result($postid);
	$stmt->fetch();
	$stmt->close();
	$mysqli->close();
	if ($postid == $lastid) {
		test('update post with content test', 1, 1);
	} else {
		test('update post with content test', 1, 0);
	}
}

function updateNoContentTest($hostname, $username, $password, $database, $mypost, $lastid) {
	$content = null;
	if (!$mypost->update($lastid, $content, null)) {
		test('update post with no content test', 1, 1);
	} else {
		test('update post with no content test', 1, 0);
	}
}

function getNumberOfRecords(
	$hostname, $username, $password, $database, $table, $where_clause=null) {
	$sql = 'select count(*) from ' . $table;
	if ($where_clause)
		$sql .= $where_clause;
	$mysqli = new mysqli($hostname, $username, $password, $database);
	$stmt = $mysqli->prepare($sql);
	$stmt->execute();
	$stmt->bind_result($numrex);
	$stmt->fetch();
	$stmt->close();
	$mysqli->close();

	return $numrex;
}
?>
