<html>
<head>
<title>Detail Viewer</title>
<link rel="stylesheet" type="text/css" href="pv.css" />
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
<!--link type='text/css' href='js/CookieCompliance/stylesheet.css' rel='stylesheet'-->
<!--#include virtual="include/meta.inc" -->
</head>
<body>

<?php

echo "<br/>";
echo "<h1>". $_GET["ticker"] . "</h1>";
echo "<br/>";
// Read the JSON file 
$json = file_get_contents('detail.json');
  
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
for ($i = 0; $i<=count($json_data)-1; $i = $i+1)
{
    if ($json_data[$i]["ticker"] == $securityticker)
    {
        
        if ($count == 0)
        {
        
            $min = (float)$json_data[$i]["bidprice"];
            $max = (float)$json_data[$i]["bidprice"];
            
        }
        else
        {
            $min = min($min,(float)$json_data[$i]["bidprice"]);
            $max = max($max,(float)$json_data[$i]["bidprice"]);
        }
        $count = $count+1;
        
        
        
    }
}

//Creo la riga con il minimo
echo "<tr class='table-danger'><td>Minimo</td><td></td><td align='right' class='font-weight-bold'>". number_format((float)$min,3) ."</td><td></td><td></td><td></td><td></td></tr>";

//Creo la riga con il massimo
echo "<tr class='table-success'><td>Massimo</td><td></td><td align='right' class='font-weight-bold'>" . number_format((float)$max,3) . "</td><td></td><td></td><td></td><td></td></tr>";

for ($i=0; $i<=count($json_data)-1;$i = $i+1)
{
    if ($json_data[$i]["ticker"] == $securityticker)
    {
        echo "<tr>";
        echo "<td>";
        echo $json_data[$i]["cycleDateTime"];
        echo "</td><td align='right'>";
        echo number_format((float)$json_data[$i]["lastPrice"],3);
        echo "</td><td align='right' class='font-weight-bold'>";
        echo number_format((float)$json_data[$i]["bidprice"],3);
        echo "</td><td align='right'>";
        echo number_format((float)$json_data[$i]["askprice"],3);
        echo "</td><td align='right'>";
        if ((float)$json_data[$i]["askprice"] != 0 and (float)$json_data[$i]["bidprice"] != 0)
        {
            $spread = (float)$json_data[$i]["askprice"] / (float)$json_data[$i]["bidprice"];
            $spread = $spread-1;
            echo number_format($spread*100,3);
        }
        else
        {
            echo "N/A";
        }
        
        
        echo "</td>";
   
        echo WriteCell(stocastico((float)$json_data[$i]["bidprice"],$max,$min),0.5,100,0);

        /*echo "</td><td align='right'>";
        echo number_format(stocastico((float)$json_data[$i]["bidprice"],$max,$min)*100,0);
        echo "</td>";*/



        echo "</td><td align='right'>";
        echo number_format((float)$json_data[$i]["amount"],2);
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


</body>
</html>
