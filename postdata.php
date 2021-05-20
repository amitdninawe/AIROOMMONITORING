<?php
// Connect to MySQL
include("db_connect.php");
//SQL Query for fetch light and fan
$SQL = "SELECT light FROM roomdb.onoff order by ID DESC LIMIT 1;";
$result = mysqli_query($conn,$SQL); 
//echo $temp;
while($rows=$result->fetch_assoc())
{
//echo "aa";
echo $rows['light'];
}
//SQL Query for post data
//       $SQL = "INSERT INTO roomdb.temperature (ID,TEMPERATURE,HUMIDITY) VALUES (now(),'23','45')";
if(isset($_GET["temperature"])){
if($_GET["temperature"]!= 0){
$SQL = "INSERT INTO roomdb.temperature (ID,temp,hum) VALUES (now(),'".$_GET["temperature"]."','".$_GET["humidity"]."')";
// Execute SQL statement
$a =  mysqli_query($conn,$SQL);
//echo $a;
}
}

mysqli_close($conn);
?>



