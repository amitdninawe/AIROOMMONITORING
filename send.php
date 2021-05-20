<?php
    include "db_connect.php";
	 
    if(isset($_GET['light'])){
	$light = 1;	}
	else
	{
		$light = 0;
	}
    if(isset($_GET['fan'])){
        $fan = 1;}
		else{
			 $fan = 0;
		}
   // $light =isset($_GET['light']);
    //$fan = isset($_GET['fan']); 
	//echo $light,$fan;
    $SQL = "INSERT INTO onoff (ID,LIGHT,FAN) VALUES (now(),$light,$fan)";  
    // Execute SQL statement
    $a = mysqli_query($conn,$SQL);
	//echo $a
	header("Location:http://localhost/roomdata/");
?>