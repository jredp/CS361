<?php
		session_start();
    //Turn on error reporting
    ini_set('display_errors', 'On');
		//Connects to the database
    $mysqli = new mysqli("oniddb.cws.oregonstate.edu","parkinja-db","FnfHVCECnMOBAPPX","parkinja-db");
		/* $mysqli = new mysqli("127.0.0.1","cs361","p@ssw0rD","scratch"); */
    if($mysqli->connect_errno) {
        echo "Connection error " . $mysqli->connect_errno . " " . $mysqli->connect_error;
    }
        
    //Clear the error banner
    unset($msg);    

    //Get passed Login info
    $uname = $_POST['user_name'];
    $pass = $_POST['user_pass'];

    if(isset($_SESSION['signed_in']) && $_SESSION['signed_in'] == true) {
        echo 'You are already signed in, you can <a href="signout.php">sign out</a> if you want.';
    }
    else {
        $errors = array();         
        if(!isset($uname)) {
            $errors[] = 'The username field must not be empty.';
        }         
        if(!isset($pass)) {
            $errors[] = 'The password field must not be empty.';
        }         
        if(!empty($errors)) {
            echo 'Fields are not filled in correctly..';
            echo '<ul>';
            foreach($errors as $key => $value) {
                echo '<li>' . $value . '</li>'; /* this generates a nice error list */
            }
            echo '</ul>';
        }
        else {
            $sql = "SELECT user_name, user_zip, user_level 
                    FROM users 
                    WHERE user_name = '$uname' 
                    AND user_pass = '$pass'"; //sha1($pass) is for hashing
                         
            //$result = mysqli_query($mysqli, $sql);
            $result = $mysqli->query($sql);
            if(!$result) {
                //something went wrong, display the error
                echo '<br><span> user_name: </span>'.$uname;
                echo '<br><span> user_pass: </span>'.$pass;
                echo '<br><span> sql: </span>'.$sql;
                echo '<br>Something went wrong while signing in. Please try again later.';
                echo $mysqli->error; //debugging purposes, uncomment when needed
            }
            else {
                if($result->num_rows == 0) {
                    echo '<br><span> user_name: </span>'.$uname;
                    echo '<br><span> user_pass: </span>'.$pass;
                    echo '<br>You have supplied a wrong user/password combination. Please try again.';
                    echo $mysqli->error; //debugging purposes, uncomment when needed
                }
                else {                    
                    $_SESSION['signed_in'] = true;

                    while($row = mysqli_fetch_assoc($result)) {                        
                        $_SESSION['user_name']  = $row['user_name'];
                        $_SESSION['user_zip']  = $row['user_zip'];
                        $_SESSION['user_level'] = $row['user_level'];
                    }
                    // redirect to landing page if login successful 
										header("Location: landing.php");
                }
            }
        }
    }
    $mysqli->close();
?>
