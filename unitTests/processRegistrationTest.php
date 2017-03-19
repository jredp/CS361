<?php
$inc = dirname(dirname(__FILE__)) . '/dbcn.php';
requre_once $inc;

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

	//---------- Registration TESTS ------------	
		//Test 1: Username is blank
		function testUserName(){
	   	  	$uname ; //empty
	   	  	$fname = "not empty";
	   	  	$lname = "not empty";
	   	 	$pass = "not empty";
	   	 	$email = "not@empty.com";
	   	 	$zip = 00000;
			assert(isset($uname), 'The username field must not be empty.');
		}
		testUserName();

		//Test 2: First name is blank
		function testFirstName(){
	   	  	$uname = "not empty";
	   	  	$fname ; //empty
	   	  	$lname = "not empty";
	   	 	$pass = "not empty";
	   	 	$email = "not@empty.com";
	   	 	$zip = 00000;
			assert(isset($fname), 'The first name field must not be empty.');
		}
		testFirstName();

		//Test 3: Last name is blank
		function testLastName(){
	   	  	$uname = "not empty";
	   	  	$fname = "not empty";
	   	  	$lname ; //empty
	   	 	$pass = "not empty";
	   	 	$email = "not@empty.com";
	   	 	$zip = 00000;
			assert(isset($lname), 'The last name field must not be empty.');
		}
		testLastName();
		
		//Test 4: Password is blank
		function testPassword(){
	    	$uname = "not empty";
	   	  	$fname = "not empty";
	   	  	$lname = "not empty";
	   	 	$pass ; //empty
	   	 	$email = "not@empty.com";
	   	 	$zip = 00000;
			assert(isset($pass), 'The password field must not be empty.');
		}
		testPassword();
		
		//Test 5: email is blank
		function testEmail(){
	    	$uname = "not empty";
	   	  	$fname = "not empty";
	   	  	$lname = "not empty";
	   	 	$pass = "not empty";
	   	 	$email ; //empty
	   	 	$zip = 00000;
			assert(isset($email), 'The email field must not be empty.');
		}
		testEmail();

		//Test 6: zip code is blank
		function testZip(){
	    	$uname = "not empty";
	   	  	$fname = "not empty";
	   	  	$lname = "not empty";
	   	 	$pass = "not empty";
	   	 	$email = "not@empty.com";
	   	 	$zip ; //empty
			assert(isset($zip), 'The zip code field must not be empty.');
		}
		testZip();

		//Test 6: username is alphanumeric only
		function testUsernameAlNum(){
	    	$uname = "_notalphanumeric$#@!"; //not a valid username
	    	$fname = "not empty";
	   	  	$lname = "not empty";
	   	 	$pass = "not empty";
	   	 	$email = "not@empty.com";
	   	 	$zip = 00000;
			assert(is_numeric($uname), 'The username must consist only of alphanumeric characters.');
		}
		testUsernameAlNum();

		//Test 7: zip code is numbers only
		function testZipNum(){
	    	$uname = "not empty";
	    	$fname = "not empty";
	   	  	$lname = "not empty";
	   	 	$pass = "not empty";
	   	 	$email = "not@empty.com";
	   	 	$zip = "not numbers"; //not a valid zip code
			assert(ctype_digit($zip), 'The zip code must consist only of numbers.');
		}
		testZipNum();

		//Test 8: zip code is exactly 5 digits
		function testZipLength(){
	    	$uname = "not empty";
	    	$fname = "not empty";
	   	  	$lname = "not empty";
	   	 	$pass = "not empty";
	   	 	$email = "not@empty.com";
	   	 	$zip = 123456; //not a valid zip code
			assert(strlen((string)$zip) == 5, 'The zip code provided is invalid.');
		}
		testZipLength();

		//Test 9: username already exists
		function testUsernameExists(){
	    	//$mysqli = new mysqli("oniddb.cws.oregonstate.edu","parkinja-db","FnfHVCECnMOBAPPX","parkinja-db");
	    	$mysqli = new mysqli($hostname, $username, $password, $database);
	    	$uname = "test1"; //should already exist in DB
	    	$fname = "not empty";
	   	  	$lname = "not empty";
	   	 	$pass = "not empty";
	   	 	$email = "not@empty.com";
	   	 	$zip = 00000;
	    	$sql = "SELECT user_name
                    FROM users 
                    WHERE user_name = '$uname'";
            $result = $mysqli->query($sql);
			$mysqli->close();
			assert(($result->num_rows == 0), '<br>The username you have entered already exists. Please try another one.');
		}
		testUsernameExists();

		//Test 10: email already exists
		function testEmailExists(){
	    	//$mysqli = new mysqli("oniddb.cws.oregonstate.edu","parkinja-db","FnfHVCECnMOBAPPX","parkinja-db");
	    	$mysqli = new mysqli($hostname, $username, $password, $database);
	    	$uname = "not empty";
	    	$fname = "not empty";
	   	  	$lname = "not empty";
	   	 	$pass = "not empty";
	   	 	$email = "test1@test.com"; //should already exist in DB
	   	 	$zip = 00000;
	    	$sql = "SELECT email
                    FROM users 
                    WHERE email = '$email'";
            $result = $mysqli->query($sql);
            $mysqli->close();
			assert(($result->num_rows == 0), '<br>The email you have entered already exists.');
		}
		testEmailExists();

		/*//Test 9: zip code is exactly 5 digits
		function testValidEmail(){
	    	$uname = "not empty";
	    	$fname = "not empty";
	   	  	$lname = "not empty";
	   	 	$pass = "not empty";
	   	 	$email = "notvalid.com";
	   	 	$zip = 00000; //not a valid zip code
			assert(filter_var($email, FILTER_VALIDATE_EMAIL), 'The email provided is invalid.');
		}
		testValidEmail();*/

	unitTest();
?>