<?php
	function unitTest () {
	//PHP ASSERTION WORK -------------------
		// Active assert and make it quiet
	    assert_options(ASSERT_ACTIVE, 1);
	    assert_options(ASSERT_WARNING, 0);
	    assert_options(ASSERT_QUIET_EVAL, 1);
		// Create a handler function
		function my_assert_handler($file, $line, $code, $desc = null) {
		    echo "PASS: Assertion failed at line $line. As expected, error is";
		    if ($desc) {
		        echo ": $desc";
		  	}
	    	echo "\n";
		}
		// Set up the callback
		assert_options(ASSERT_CALLBACK, 'my_assert_handler');	
    }
    unitTest();
    
	//---------- Login TESTS ------------	
		//Test 1: Username is blank
		function testUserName(){
	   	  	$uname ; //empty
	   	 	$pass = "not empty";
			assert(isset($uname), 'The username field must not be empty.<br>');
		}
		testUserName();
		
		//Test 2: Password is blank
		function testPassword(){
	    	$uname = "not empty"; //empty
	    	$pass ;
			assert(isset($pass), 'The password field must not be empty.<br>');
		}
		testPassword();
		
		//Test 3: Username not in the system
		function testUserNotIn(){
		    $mysqli = new mysqli("oniddb.cws.oregonstate.edu","parkinja-db","FnfHVCECnMOBAPPX","parkinja-db");
	    	$uname = "test111"; //Not in DB
	    	$pass = "abc123";
	    	$sql = "SELECT user_name, user_zip, user_level 
                    FROM users 
                    WHERE user_name = '$uname' 
                    AND user_pass = '$pass'";
            $result = $mysqli->query($sql);
            $mysqli->close();
			assert(($result->num_rows > 0), 'You have supplied a wrong user/password combination. Please try again.<br>');
		}
		testUserNotIn();
		
		//Test 4: Wrong Password
		function testUserWrongPass(){
		    $mysqli = new mysqli("oniddb.cws.oregonstate.edu","parkinja-db","FnfHVCECnMOBAPPX","parkinja-db");
	    	$uname = "test1";
	    	$pass = "abc123"; //Wrong Pass
	    	$sql = "SELECT user_name, user_zip, user_level 
                    FROM users 
                    WHERE user_name = '$uname' 
                    AND user_pass = '$pass'";
            $result = $mysqli->query($sql);
            $mysqli->close();
			assert(($result->num_rows > 0), 'You have supplied a wrong user/password combination. Please try again.<br>');
		}
		testUserWrongPass();				
?>