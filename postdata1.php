<?php
// Connect to MySQL
include("db_connect.php");
//SQL Query for fetch light and fan
$SQL = "SELECT fan FROM roomdb.onoff order by ID DESC LIMIT 1;";
$result = mysqli_query($conn,$SQL); 
//echo $temp;
while($rows=$result->fetch_assoc())
{
//echo "aa";
echo $rows['fan'];
}
 

mysqli_close($conn);
?>



