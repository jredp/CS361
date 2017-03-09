<?php 
$pageTitle = "Welcome!";
include("inc/header.php"); ?>

	<div class = "message">
	<?php 
		if($msg) {
			echo $msg;
		}
	?>
	</div>
	<div class = "index">
  		<h1>CS361 Team 15 Project B: Tragedy of the Commons</h1>
  		<img class = "resize" src = "img/Cows_on_Selsley_Common_-_geograph.org.uk_-_192472.jpg" alt = "Cows on Selsley Common" title = "Cows on Selsley Common">
  		<br>  		
  	</div>

	<!--MODAL REGISTER-->
	<button id="reg" onclick="document.getElementById('id00').style.display='block'" style="width:auto;">Register</button>
		<div id="id00" class="modal">		  
		  <form class="modal-content animate" method = "post" action="./processRegister.php">
		    <div class="container">		      
				<legend align = "center">All fields are required.</legend>				   
				<label><b>First Name</b></label>
				<input type = "text" name = "first_name" required>
				<label><b>Last Name</b></label>				
			    <input type = "text" name = "last_name" required>
				<label><b>User Name</b></label>
			    <input type="text" placeholder="Enter Username" name="user_name" required>
			    <label><b>Password</b></label>
			    <input id="pass1" type="password" placeholder="Enter Password" name="user_pass" required>		    
			    <label><b>Confirm Password</b></label>
			    <input id="pass2" type = "password" name = "user_pass_check" required>
			    <label><b>Email</b></label>
			    <input required type = "email" name = "user_email"  required>
			    <label><b>Zipcode</b></label>
			    <input name="user_zip"
          			oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"
          			type = "number"
          			maxlength = "5"
        		/>
			    <button type="submit">Register</button>
		    </div>
		    <div class="container" style="background-color:#f1f1f1">
		      <button type="button" onclick="document.getElementById('id00').style.display='none'" class="cancelbtn">Cancel</button>
		    </div>
		  </form>
		</div>	
	
  	<!--MODAL LOGIN-->
	<button onclick="document.getElementById('id01').style.display='block'" style="width:auto;">Login</button>
		<div id="id01" class="modal">		  
		  <form class="modal-content animate" method = "post" action="./processLogin.php">
		    <div class="container">
			    <label><b>Username</b></label>
			    <input type="text" placeholder="Enter Username" name="user_name" required>
			    <label><b>Password</b></label>
			    <input type="password" placeholder="Enter Password" name="user_pass" required>		        
			    <button type="submit">Login</button>
		    </div>
		    <div class="container" style="background-color:#f1f1f1">
		      <button type="button" onclick="document.getElementById('id01').style.display='none'" class="cancelbtn">Cancel</button>
		    </div>
		  </form>
		</div>

	
	<script>
		// Get the modal
		var modal1 = document.getElementById('id00');
		var modal2 = document.getElementById('id01');
		// When the user clicks anywhere outside of the modal, close it
		window.onclick = function(event) {
		    if (event.target == modal1) {
		        modal1.style.display = "none";
		    }
		    else if(event.target == modal2) {
		    	modal2.style.display = "none";
		    }
		}	
	</script>

<?php include("inc/footer.php"); ?>	