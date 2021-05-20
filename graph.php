<?php
include("db_connect.php");
$sql = "SELECT round(AVG(temp),2) as TEMPERATURE ,round(avg(hum),2) as HUMIDITY, date(ID),dayofweek(date(ID)) FROM temperature GROUP BY DATE(ID) ORDER BY DATE(ID) DESC LIMIT 7;"; 
$result = mysqli_query($conn,$sql); 

$dowMap = array('NULL','Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday');
$days=array();
$temp=array();
$hum=array();
$date=array();
while($rows=$result->fetch_assoc()) 
    { 
		//echo $rows['date(ID)'];
		//echo $rows['TEMPERATURE'];
		//echo $rows['HUMIDITY'];
		
        array_push($date,$rows['date(ID)']);
        array_push($temp,$rows['TEMPERATURE']);
        array_push($hum,$rows['HUMIDITY']);
        array_push($days,$dowMap[$rows['dayofweek(date(ID))']]);         
    }
//echo $date[6];
$dataPoints1 = array(
  array("label"=> $days[0], "y"=> $temp[0]),
  array("label"=> $days[1], "y"=> $temp[1]),
  array("label"=> $days[2], "y"=> $temp[2]),
  array("label"=> $days[3], "y"=> $temp[3]),
  array("label"=> $days[4], "y"=> $temp[4]),
  array("label"=> $days[5], "y"=> $temp[5]),
  array("label"=> $days[6], "y"=> $temp[6])
);
$dataPoints2 = array(
  array("label"=> $days[0], "y"=> $hum[0]),
  array("label"=> $days[1], "y"=> $hum[1]),
  array("label"=> $days[2], "y"=> $hum[2]),
  array("label"=> $days[3], "y"=> $hum[3]),
  array("label"=> $days[4], "y"=> $hum[4]),
  array("label"=> $days[5], "y"=> $hum[5]),
  array("label"=> $days[6], "y"=> $hum[6])
);
  
?>
<html>
  <head>
    <title>What's happening in the room?</title>
    <style>

.vertical-menu {
  width: 100px;
}

.vertical-menu a {
  background-color: #eee;
  color: black;
  display: block;
  padding: 12px;
  text-decoration: none;
}

.vertical-menu a:hover {
  background-color: #ccc;
}

.vertical-menu a.active {
  background-color: #4CAF50;
  color: white;
}
</style>
<table style="width:100%">
  <tr>
<td>  
<h1>Menu</h1>

<div class="vertical-menu">
  <a href="index.php" class="active">Home</a>
  <a href="graph.php">Graph</a>
  <!-- <a href="file:///C:/Users/Lokesh/Desktop/website/image.html">Gallery</a> -->
</div>

</td>
<td>
<script>
window.onload = function () {
 
var chart = new CanvasJS.Chart("chartContainer", {
  animationEnabled: true,
  theme: "light2",
  title:{
    text: "Average Temperture & Humidity of last week"
  },
  axisY:{
    includeZero: true
  },
  legend:{
    cursor: "pointer",
    verticalAlign: "center",
    horizontalAlign: "right",
    itemclick: toggleDataSeries
  },
  data: [{
    type: "column",
    name: "Temperture",
    indexLabel: "{y}",
    yValueFormatString: "#0.## °C",
    showInLegend: true,
    dataPoints: <?php echo json_encode($dataPoints1, JSON_NUMERIC_CHECK); ?>
  },{
    type: "column",
    name: "Humidity",
    indexLabel: "{y}",
    yValueFormatString: "#0.## '%'",
    showInLegend: true,
    dataPoints: <?php echo json_encode($dataPoints2, JSON_NUMERIC_CHECK); ?>
  }]
});
chart.render();
 
function toggleDataSeries(e){
  if (typeof(e.dataSeries.visible) === "undefined" || e.dataSeries.visible) {
    e.dataSeries.visible = false;
  }
  else{
    e.dataSeries.visible = true;
  }
  chart.render();
}
 
}
</script>
  </head>
  <body>
    <div id="container" style="width: 90%;">
      <div id="header">
        <div id="logoContainer">
          <h1 style="color:grey;font-size:300%;width:100%;text-align:center;">Weekly Temperture Graph</h1>
         
        </div>
        
       
     

    
      </div>   
          <div id="chartContainer" style="height: 80%; width: 100%;"></div>
          <script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
      </td>
    </tr>
      </div>
    </div>
  </body>
</html>