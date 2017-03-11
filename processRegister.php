<?php
    //Turn on error reporting
    ini_set('display_errors', 'On');
    //Connects to the database
    $mysqli = new mysqli("oniddb.cws.oregonstate.edu","parkinja-db","FnfHVCECnMOBAPPX","parkinja-db");
    if($mysqli->connect_errno) {
        echo "Connection error " . $mysqli->connect_errno . " " . $mysqli->connect_error;
    }
        
    //Clear the error banner
    unset($msg);   
    //Check the passwords match
    $pass = $_POST['user_pass'];
    $cpass = $_POST['user_pass_check'];
    if($pass != $cpass) {
        $msg = "<h3><br><p>Paswords do not match! Try again.</h3>";  
        $url = './index.php';               
        return require $url;     
    }
    else {
        $uname = $_POST['user_name'];
        $fname = $_POST['first_name'];
        $lname = $_POST['last_name'];        
        $email = $_POST['user_email'];
        $zip = $_POST['user_zip'];

        echo '<br><span> user_name: </span>'.$uname;
        echo '<br><span"> first_name: </span>'.$fname;
        echo '<br><span> last_name: </span>'.$lname;
        echo '<br><span> user_email: </span>'.$email;
        echo '<br><span> Zip: </span>'.$zip;

        $statement = $mysqli->prepare("INSERT INTO users(user_name, first_name, last_name, user_pass, user_email, user_zip) VALUES (?, ?, ?, ?, ?, ?)");
        //([uname], [fname], [lname], [pass], [email], [zip])
        $statement->bind_param("sssssi",$uname,$fname,$lname,$pass,$email,$zip);
        $result = $statement->execute();

        if ($result) {
					  // TODO: need to add the session variables and then redirect, see processLogin for example
            echo "<h3><br><p>Record added successfully</h3>";
            echo 'Welcome, ' . $uname . '. <a href="index.php">would auto-forward to landing here</a>.';
        } 
        else {
            echo "<br>Error adding record: " . $mysqli->error;
        }
        $mysqli->close();
    }
?>
