<?php
   include("db_connect.php");
   session_start();
   
   if($_SERVER["REQUEST_METHOD"] == "POST") {
      // username and password sent from form 
      
      $myusername = mysqli_real_escape_string($conn,$_POST['username']);
      $mypassword = mysqli_real_escape_string($conn,$_POST['password']); 
      
      $sql = "SELECT ID FROM USER WHERE USERNAME = '$myusername' and PASSWORD = '$mypassword'";
      $result = mysqli_query($conn,$sql);
      $row = mysqli_fetch_array($result,MYSQLI_ASSOC);
      $active = $row['active'];
      
      $count = mysqli_num_rows($result);
      
      // If result matched $myusername and $mypassword, table row must be 1 row
		
      if($count == 1) {
         //session_register("myusername");
         $_SESSION['login_user'] = $myusername;

         header("location: index.php");
      }else {
         $error = "Your Login Name or Password is invalid";
      }
   }
?>
<html>
<head>
  <title></title>
  <style type="text/css">
    body{
      font-family: verdana;
    }
    form {
      width: 40%;
      vertical-align:center;
      border: 3px solid blue;
    }

/* Full-width inputs */
input[type=text], input[type=password] {
  width: 75%;
  padding: 12px 20px;
  margin: 8px 0;
  display: inline-block;
  border: 1px solid #ccc;
  box-sizing: border-box;
  font-family: verdana;
}

/* Set a style for all buttons */
button {
  background-color: black;
  color: white;
  padding: 14px 20px;
  margin: 8px 0;
  border: none;
  cursor: pointer;
  width: 75%;
  font-family: verdana;
}

/* Add a hover effect for buttons */
button:hover {
  opacity: 0.8;
  font-family: verdana;
}

/* Extra style for the cancel button (red) */
.cancelbtn {
  width: auto;
  align-self: left;
  padding: 10px 18px;
  background-color: #f44336;
}

/* Center the avatar image inside this container */
.imgcontainer {
  text-align: center;
  margin: 24px 0 12px 0;
}

/* Avatar image */
img.avatar {
  width: 20%;
  border-radius: 2%;
}

/* Add padding to containers */
.container {
  padding: 8px;
}

/* The "Forgot password" text */
span.psw {
  float: right;
  padding-top: 16px;
}

/* Change styles for span and cancel button on extra small screens */
@media screen and (max-width: 300px) {
  span.psw {
    display: block;
    float: none;
  }
  .cancelbtn {
    width: 100%;
  }

}

  </style>
</head>
<body>
  <center>
  <form action="login.php" method="post">
  <div class="imgcontainer">
    <img src="index.png" alt="Avatar" class="avatar">
  </div>

  <div class="container" border = "thick solid #0000FF">
    <!-- <label for="uname"><b>Username</b></label> -->
    <center>
    <input type="text" placeholder="Enter Username" name="username" required><br>
    <!-- <label for="psw"><b>Password</b></label> -->
    <input type="password" placeholder="Enter Password" name="password" required>
    <br>
    <button type="submit">Login</button>
    <br>
    </center>
    <label>
      <input type="checkbox" checked="checked" name="remember"> Remember me
    </label>

  </div>

  <div class="container" style="background-color:#f1f1f1">
    <button type="button" class="cancelbtn">Cancel</button>
  </div>
</form> 
</body>
</html>
