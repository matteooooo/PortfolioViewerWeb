<html>
<head>
<title>Detail Viewer</title>
<link rel="stylesheet" type="text/css" href="pv.css" />
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script type="text/javascript" src="http://code.highcharts.com/highcharts.js"></script>
<!--link type='text/css' href='js/CookieCompliance/stylesheet.css' rel='stylesheet'-->
<!--#include virtual="include/meta.inc" -->
<meta http-equiv="refresh" content="120">
<meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate" />
<meta http-equiv="Pragma" content="no-cache" />
<meta http-equiv="Expires" content="0" />
</head>
<body>
<?php

// Read the JSON file 
$json = file_get_contents('dailySecurityData.json');
  
// Decode the JSON file
$json_data = json_decode($json,true);
  
// Display data
//print_r($json_data);

$securityticker = $_GET["ticker"];

echo "<table class='table-bordered center' width='37%' >";
echo table_header();

$min = 0;
$max = 0;
$count = 0;
for ($i = 0; $i<count($json_data); $i++)
{
    if ($json_data[$i]["ticker"] == $securityticker)
    {
        $sec = $json_data[$i];
    }
}
    //echo (count($sec["values"]));

for ($j = 0; $j < count($sec["values"]); $j++)
{
    if ($count == 0)
    {    
        $min = (float)$sec["values"][$j]["bidprice"];
        $max = (float)$sec["values"][$j]["bidprice"];
        //print_r(" Count -> " . $count . " | Max->" . $max . "| Current -> " . (float)$sec["values"][$j]["bidprice"]);
    }
    else
    {
        $min = min($min,(float)$sec["values"][$j]["bidprice"]);
        $max = max($max,(float)$sec["values"][$j]["bidprice"]);     
        //print_r(" Count -> " . $count . " | Max->" . $max . "| Current -> " . (float)$sec["values"][$j]["bidprice"]);
    }
    $count = $count+1;

}

echo "<br/>";
//echo "<h1>". $_GET["ticker"] . "</h1>";
echo "<h1>" .  $sec["description"] . "</h1>";
echo "<h2>" . $sec["isincode"] . " - " . $sec["ticker"] . "</h2>";
echo "<br/>";
echo "<div id='container2' style='height: 400px' ></div>";


//Creo la riga con il minimo
echo "<tr class='table-danger'><td>Minimo</td><td></td><td></td><td align='right' class='font-weight-bold'>". number_format((float)$min,3) ."</td><td></td><td></td><td></td><td></td></tr>";

//Creo la riga con il massimo
echo "<tr class='table-success'><td>Massimo</td><td></td><td></td><td align='right' class='font-weight-bold'>" . number_format((float)$max,3) . "</td><td></td><td></td><td></td><td></td></tr>";

//for ($i=0; $i<count($sec["values"]);$i = $i+1)
for ($i = count($sec["values"])-1; $i>=0 ; $i = $i-1)


{
    if ($sec["ticker"] == $securityticker)
    {
        echo "<tr>";
        echo "<td>";
        echo $sec["values"][$i]["cycledatetime"];
        echo "</td><td align='right'>";
        echo number_format((float)$sec["values"][$i]["var"]*100,2) . "%";
        
        echo "</td><td align='right'>";
        echo number_format((float)$sec["values"][$i]["lastprice"],3);
        echo "</td><td align='right' class='font-weight-bold'>";
        echo number_format((float)$sec["values"][$i]["bidprice"],3);
        echo "</td><td align='right'>";
        echo number_format((float)$sec["values"][$i]["askprice"],3);
        echo "</td><td align='right'>";
        if ((float)$sec["values"][$i]["askprice"] != 0 and (float)$sec["values"][$i]["bidprice"] != 0)
        {
            $spread = (float)$sec["values"][$i]["askprice"] / (float)$sec["values"][$i]["bidprice"];
            $spread = $spread-1;
            echo number_format($spread*100,3);
        }
        else
        {
            echo "N/A";
        }
        
        
        echo "</td>";
   
        echo WriteCell(stocastico((float)$sec["values"][$i]["bidprice"],$max,$min),0.5,100,0);

        /*echo "</td><td align='right'>";
        echo number_format(stocastico((float)$json_data[$i]["bidprice"],$max,$min)*100,0);
        echo "</td>";*/



        echo "</td><td align='right'>";
        echo number_format((float)$sec["values"][$i]["amount"],2);
        echo "</td>";
        echo "</tr>";
    }
}
echo "</table>";
function table_header()
{
    $spool = "";
    $spool = $spool . "<tr class='table-warning'>";
    $spool = $spool . "<th align='center' width='25%'>";
    $spool = $spool . "Datetime";
    $spool = $spool .  "</th>";
    $spool = $spool . "<th align='center' width='13%'>";
    $spool = $spool . "Variazione";
    $spool = $spool .  "</th>";
    $spool = $spool . "<th align='center' width='13%'>";
    $spool = $spool .  "Last";
    $spool = $spool .  "</th>";
    $spool = $spool . "<th align='center' width='13%'>";
    $spool = $spool .  "Bid";
    $spool = $spool .  "</th>";
    $spool = $spool . "<th align='center' width='13%'>";
    $spool = $spool .  "Ask";
    $spool = $spool .  "</th>";
    $spool = $spool . "<th align='center' width='13%'>";
    $spool = $spool .  "Spread(%)";
    $spool = $spool .  "</th>";
    $spool = $spool . "<th align='center' width='12%'>";
    $spool = $spool .  "Sto(%)";
    $spool = $spool .  "</th>";
    $spool = $spool . "<th align='center'>";
    $spool = $spool .  "Amount";
    $spool = $spool .  "</th>";
    $spool = $spool .  "</tr>";
    return $spool;
}


function stocastico($Current,$Massimo,$Minimo)
{
	$Cur = (float)$Current;
	$Min = (float)$Minimo;
	$Max = (float)$Massimo;
	if ($Max == $Min)
	{
		return "N/A";
	}
	else
	{
		$sto = ($Cur - $Min)/($Max-$Min);
		return number_format((float)$sto,4);
	}
}

function WriteCell($content,$positive,$multiplier,$decimalPlaces)
{
	$spool = "";
	if ((float)$content >= (float)$positive)
	{
		$spool = $spool . "<td align='right' class='text-success'>";
	}
	else
	{
		$spool = $spool . "<td align='right' class='text-danger'>";
	}
	$spool = $spool . number_format((float)$content*$multiplier,$decimalPlaces);
	$spool = $spool . "</td>";
	return $spool;
}

?>

<script type="text/javascript">
function gup( name ){
	name = name.replace(/[\[]/,"\\\[").replace(/[\]]/,"\\\]");  
	var regexS = "[\\?&]"+name+"=([^&#]*)";  
	var regex = new RegExp( regexS );  
	var results = regex.exec( window.location.href ); 
	if( results == null )    return "";  
	else    return results[1];
    };
</script>

<script type="text/javascript">
$.ajax({
	url: 'dailySecurityData.json',
	async: false,
	dataType: "text",
	error: function()
    {
		alert ("Impossibile generare il grafico");
		//window.location.replace("monitors.shtml");
    },
	success: function(data) 
	{
        var json = $.parseJSON(data);
        series = [
			{ name: gup("ticker"), data: [],lineWidth: 3,color: '#FF0000',symbol: 'cross'}, 
		];
        
        for (var i = 0, l = json.length; i < l; i++) 
        {
            if (json[i].ticker == gup('ticker'))
            {
                for (var j=0; j < json[i].values.length; j++)
                {
                    x = Date.parse(json[i].values[j].cycledatetime);
			        if (json[i].bidprice == 0 )
                    {
                        series[0].data[j] = [x, json[i].values[j].lastprice];
                    }
                    else
                    {
                        series[0].data[j] = [x, json[i].values[j].bidprice];
                    }




                    //document.write(json[i].values[j].cycledatetime);
                    //document.write(json[i].values[j].delta);
                }
            }
        }
        
        var chart = new Highcharts.Chart({
    chart: {
        renderTo: 'container2'
    },
	title: {
        text: ''
    },
    xAxis: {
        type: 'datetime',
		tickInterval:  600 * 1000,
		 labels: {
            enabled: true
        }
    },
	
	yAxis: [{
        plotLines: [{
                color: '#FF0000',
                width: 1,
                value: 0,
                zIndex:2}],
			    title: {text: 'Delta'}
				
				
    }, {
        linkedTo: 0,
        opposite: true,
		 title: {text: 'Delta'}
    }],
	 plotOptions: {
        series: {
            cursor: 'pointer',
            className: 'popup-on-click',
            marker: {
                lineWidth: 1,
				radius: 5,
				symbol: 'circle'
				
            }
        }
    },
    series: series
});        
    }
});
</script>
<br/><br/><br/>
</body>


</html>




