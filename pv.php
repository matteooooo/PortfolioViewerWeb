<html>
<head>
<title>Portfolio Manager - Asset Value</title>
<link rel="stylesheet" type="text/css" href="pv.css" />
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script type="text/javascript" src="http://code.highcharts.com/highcharts.js"></script>
<meta http-equiv="refresh" content="300">
<meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate" />
<meta http-equiv="Pragma" content="no-cache" />
<meta http-equiv="Expires" content="0" />

<!--link type='text/css' href='js/CookieCompliance/stylesheet.css' rel='stylesheet'-->
<!--#include virtual="include/meta.inc" -->
</head>
<body>
<?php

function dateFormatter($giorno,$mese,$anno,$ore,$minuti)
{
	if (strlen($giorno) == 1)
	{
		$giorno = "0" . $giorno;
	}

	if (strlen($mese) == 1)
	{
		$mese = "0" . $mese;
	}

	if (strlen($ore) == 1)
	{
		$ore = "0" . $ore;
	}

	if (strlen($minuti) == 1)
	{
		$minuti = "0" . $minuti;
	}
	return $giorno . "/" . $mese . "/" . $anno . " " . $ore . ":" . $minuti;
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

// Read the JSON file 
$json = file_get_contents('currentPortfolioNew.json');
$json_currentportfolio = json_decode($json,true);
// Read the JSON file 
$json = file_get_contents('dailyAssetClassDataNew.json');
$json_dailyhist = json_decode($json,true);






echo "<h1>Portfolio Manager - Asset Value</h1>";
//echo "</div>";


$currentDate = date_parse($json_dailyhist[count($json_dailyhist)-1]["EvaluationDateTime"]);
echo "<h5>" . dateFormatter($currentDate["day"],$currentDate["month"],$currentDate["year"],$currentDate["hour"],$currentDate["minute"]) . "</h5>";

//echo "<br/>";
//echo "<h2>Suddivione per titolo</h2>";
echo "<div>";
echo "<table class='table-bordered center' width='98%' >";
echo "<thead>";
//echo "<tr class='table-warning'><th colspan='2' align='center'>Security</th><th colspan='3'>Portfolio</th><th colspan='2'>Last</th><th colspan='2'>Yesterday</th><th colspan='2'>WTD</th><th colspan='2'>MTD</th>";
echo "<tr class='table-warning'><th colspan='4' align='center'>Security</th><th colspan='2'>Last</th><th colspan='2'>Yesterday</th><th colspan='4'>WTD</th><th colspan='4'>MTD</th>";
echo "<tr class='table-primary'>";
echo "<th class='text-center' width='9%'>ISIN Code</th>";
echo "<th class='text-center' >Description</th>";
echo "<th class='text-center' >Ticker</th>";
echo "<th class='text-center' >Ref price</th>";
/*echo "<th class='text-center' width='6%'>Quantity</th>";
echo "<th class='text-center' width='6%'>PMC</th>";*/
echo "<th class='text-center' width='5%'>Price</th>";
echo "<th class='text-center' width='5%'>Amount</th>";
echo "<th class='text-center' width='5%'>Delta</th>";
echo "<th class='text-center' width='5%'>Var</th>";
echo "<th class='text-center' width='5%'>Amount</th>";
echo "<th class='text-center' width='5%'>Cash flow</th>";
echo "<th class='text-center' width='5%'>Delta</th>";
echo "<th class='text-center' width='5%'>Var</th>";
echo "<th class='text-center' width='5%'>Amount</th>";
echo "<th class='text-center' width='5%'>Cash flow</th>";
echo "<th class='text-center' width='5%'>Delta</th>";
echo "<th class='text-center' width='5%'>Var</th>";
echo "</tr>";
echo "</thead>";
echo "<tbody>";

//calcolo i totali 
$totalAmountLast = 0;
$yesterdayTotalAmount = 0;
$yesterdayTotalDelta = 0;
$lastWeekTotalAmount = 0;
$lastWeekTotalDelta = 0;
$lastMonthTotalAmount = 0;
$lastMonthTotalDelta = 0;
for ($i=0; $i < count($json_currentportfolio); $i = $i+1)
{
	$totalAmountLast = $totalAmountLast + (float)$json_currentportfolio[$i]["amount"];
	$yesterdayTotalAmount = $yesterdayTotalAmount + (float)$json_currentportfolio[$i]["yamount"];
	$yesterdayTotalDelta = $yesterdayTotalDelta + (float)$json_currentportfolio[$i]["ydelta"];
	$lastWeekTotalAmount = $lastWeekTotalAmount   + (float)$json_currentportfolio[$i]["WTDamount"];
	$lastWeekTotalDelta = $lastWeekTotalDelta    + (float)$json_currentportfolio[$i]["WTDdelta"];
	$lastMonthTotalAmount = $lastMonthTotalAmount + (float)$json_currentportfolio[$i]["MTDamount"];
	$lastMonthTotalDelta = $lastMonthTotalDelta   + (float)$json_currentportfolio[$i]["MTDdelta"];



echo "<tr>";
echo "<td>";
echo $json_currentportfolio[$i]["isincode"];
echo "</td>";
echo "<td>";
echo $json_currentportfolio[$i]["description"];
echo "</td>";
echo "<td>";
echo "<a href=detail.php?ticker=". (string)$json_currentportfolio[$i]["ticker"] . ">" . (string)$json_currentportfolio[$i]["ticker"] . "</a>";
//echo "<a href=detail.php?ticker=". (string)$item->ticker . " target='_blank'>" . (string)$item->ticker . "</a>";


echo "</td>";
/*echo "<td align='right'>";
echo number_format((float)$item->quantity,3);
echo "</td>";
echo "<td align='right' >";
echo number_format((float)$item->pmc,3);
echo "</td>";
*/
echo "<td align='right' >";
echo number_format((float)$json_currentportfolio[$i]["yprice"],3);
echo "</td>";



echo "<td align='right' class='font-weight-bold' >";
echo number_format((float)$json_currentportfolio[$i]["lastprice"],3);
echo "</td>";




echo "<td align='right'>";
echo number_format((float)$json_currentportfolio[$i]["amount"],2);
echo "</td>";


if ((float)$json_currentportfolio[$i]["ydelta"] >= 0) 
{
echo "<td class='table-success' align='right'>";
}
else
{
echo "<td class='table-danger' align='right'>";
}
echo number_format((float)$json_currentportfolio[$i]["ydelta"],2);
echo "</td>";

if ((float)$json_currentportfolio[$i]["yvar"] >= 0) 
{
echo "<td class='table-success' align='right'>";
}
else
{
echo "<td class='table-danger' align='right'>";
}
echo number_format((float)$json_currentportfolio[$i]["yvar"]*100,2) . "%";
echo "</td>";


echo "<td align='right'>";
echo number_format((float)$json_currentportfolio[$i]["WTDamount"],2);
echo "</td>";
echo "<td align='right'>";
echo number_format((float)$json_currentportfolio[$i]["WTDcashflow"],2);
echo "</td>";




if ((float)$json_currentportfolio[$i]["WTDdelta"] >= 0) 
{
echo "<td class='table-success' align='right'>";
}
else
{
echo "<td class='table-danger' align='right'>";
}
echo number_format((float)$json_currentportfolio[$i]["WTDdelta"],2);
echo "</td>";

if ((float)$json_currentportfolio[$i]["WTDvar"] >= 0) 
{
echo "<td class='table-success' align='right'>";
}
else
{
echo "<td class='table-danger' align='right'>";
}
echo number_format((float)$json_currentportfolio[$i]["WTDvar"]*100,2) . "%";
echo "</td>";



echo "<td align='right'>";
echo number_format((float)$json_currentportfolio[$i]["MTDamount"],2);
echo "</td>";
echo "<td align='right'>";
echo number_format((float)$json_currentportfolio[$i]["MTDcashflow"],2);
echo "</td>";









if ((float)$json_currentportfolio[$i]["MTDdelta"] >= 0) 
{
echo "<td class='table-success' align='right'>";
}
else
{
echo "<td class='table-danger' align='right'>";
}
echo number_format((float)$json_currentportfolio[$i]["MTDdelta"],2);
echo "</td>";
if ((float)$json_currentportfolio[$i]["MTDvar"] >= 0) 
{
echo "<td class='table-success' align='right'>";
}
else
{
echo "<td class='table-danger' align='right'>";
}
echo number_format((float)$json_currentportfolio[$i]["MTDvar"]*100,2) . "%";
echo "</td>";
echo "</tr>";
}

echo "<tr class='table-primary'>";
echo "<td colspan='5' align='center'><b>Totale</b></td>";
echo "<td align='right'>" . number_format((float)$totalAmountLast,2) . "</td>";
echo "<td align='right' class='font-weight-bold'>" . number_format((float)$yesterdayTotalDelta,2) . "</td>";
$yesterdaytotalvar = ($totalAmountLast / $yesterdayTotalAmount  - 1)*100;
echo "<td align='right'>" . number_format($yesterdaytotalvar,2) .  "%</td>";


echo "<td></td>";
echo "<td></td>";

echo "<td align='right'>" . number_format((float)$lastWeekTotalDelta,2) . "</td>";
$lastweektotalvar = ($totalAmountLast / $lastWeekTotalAmount - 1)*100;
echo "<td align='right'>" . number_format($lastweektotalvar,2) .  "%</td>";


echo "<td></td>";
echo "<td></td>";
echo "<td align='right'>" . number_format((float)$lastMonthTotalDelta,2) . "</td>";



$lastmonthtotalvar = ($totalAmountLast /$lastMonthTotalAmount- 1)*100;
echo "<td align='right'>" . number_format($lastmonthtotalvar,2) .  "%</td>";
echo "</table>";
echo "<br>";


echo "<h2>Grafico giornaliero per asset class - Delta</h2>";
echo "<div id='container' style='height: 640px' ></div>";
echo "<br/><br/><br/><br/>";

echo "<h2>Grafico giornaliero per asset class - Variazione(%)</h2>";
echo "<div id='container1' style='height: 640px' ></div>";
echo "<br/><br/><br/><br/>";

echo "<h2>Grafico giornaliero per titolo - Delta</h2>";
echo "<div id='container2' style='height: 1080px' ></div>";
echo "<br/><br/><br/><br/>";

echo "<h2>Grafico giornaliero per titolo - Variazione(%)</h2>";
echo "<div id='container3' style='height: 1080px' ></div>";
echo "<br/><br/><br/><br/>";

echo "<br>";
echo "<h2>Storico giornaliero per asset class</h2>";
echo "<br>";
echo "<table class='table-bordered center' width='98%' >";
echo "<thead>";
echo "<tr class='table-warning text-center'><th></th><th colspan='4'>Bond</th><th colspan='4'>Equity</th><th colspan='4'>Gold</th><th colspan='4'>Real Estate</th><th colspan='4'>Totale</th></tr>";
echo "<tr class='table-primary text-center'><th>Date-time</th><th>Amount</th><th>Delta</th><th>Var(%)</th><th>Sto(%)</th><th>Amount</th><th>Delta</th><th>Var(%)</th><th>Sto(%)</th><th>Amount</th><th>Delta</th><th>Var(%)</th><th>Sto(%)</th><th>Amount</th><th>Delta</th><th>Var(%)</th><th>Sto(%)</th><th>Amount</th><th>Delta</th><th>Var(%)</th><th>Sto(%)</th></tr>";
echo "</thead>";
echo "<tbody>";
$count = 1;
$maxB = 0;
$maxE = 0;
$maxG = 0;
$maxR = 0;
$maxT = 0;
$minB = 0;
$minE = 0;
$minG = 0;
$minR = 0;
$minT = 0;
$count = 1;

for ($i=0; $i < count($json_dailyhist); $i = $i +1)
{
	if ($i == 0 )
	{
		$maxB = (float)$json_dailyhist[$i]["bDelta"];
		$minB = (float)$json_dailyhist[$i]["bDelta"];
		$maxE = (float)$json_dailyhist[$i]["eDelta"];
		$minE = (float)$json_dailyhist[$i]["eDelta"];
		$maxG = (float)$json_dailyhist[$i]["gDelta"];
		$minG = (float)$json_dailyhist[$i]["gDelta"];
		$maxR = (float)$json_dailyhist[$i]["rDelta"];
		$minR = (float)$json_dailyhist[$i]["rDelta"];
		$maxT = (float)$json_dailyhist[$i]["tDelta"];
		$minT = (float)$json_dailyhist[$i]["tDelta"];
	}
	else
	{
		$maxB = Max((float)$json_dailyhist[$i]["bDelta"],$maxB);
		$minB = Min((float)$json_dailyhist[$i]["bDelta"],$minB);
		$maxE = Max((float)$json_dailyhist[$i]["eDelta"],$maxE);
		$minE = Min((float)$json_dailyhist[$i]["eDelta"],$minE);
		$maxG = Max((float)$json_dailyhist[$i]["gDelta"],$maxG);
		$minG = Min((float)$json_dailyhist[$i]["gDelta"],$minG);
		$maxR = Max((float)$json_dailyhist[$i]["rDelta"],$maxR);
		$minR = Min((float)$json_dailyhist[$i]["rDelta"],$minR);
		$maxT = Max((float)$json_dailyhist[$i]["tDelta"],$maxT);
		$minT = Min((float)$json_dailyhist[$i]["tDelta"],$minT);
	}
}

//MINIMI
echo "<tr class='table-danger'>";
echo "<td align='center' >Minimo</td>";
echo "<td></td>";
echo "<td align='right'>";
	echo number_format((float)$minB,2);
	echo "</td>";
echo "<td></td>";
echo "<td></td>";
echo "<td></td>";
echo "<td align='right'>";
echo number_format((float)$minE,2);
	echo "</td>";
echo "<td></td>";
echo "<td></td>";
echo "<td></td>";
echo "<td align='right'>";
echo number_format((float)$minG,2);
	echo "</td>";
echo "<td></td>";
echo "<td></td>";
echo "<td></td>";
echo "<td align='right'>";
echo number_format((float)$minR,2);
	echo "</td>";
echo "<td></td>";
echo "<td></td>";
echo "<td></td>";
echo "<td align='right'>";
echo number_format((float)$minT,2);
	echo "</td>";
echo "<td></td>";
echo "<td></td>";
echo "</tr>";

//MASSIMI
echo "<tr class='table-success'>";
echo "<td align='center' >Massimo</td>";
echo "<td></td>";
echo "<td align='right'>";
	echo number_format((float)$maxB,2);
	echo "</td>";
echo "<td></td>";
echo "<td></td>";
echo "<td></td>";
echo "<td align='right'>";
echo number_format((float)$maxE,2);
	echo "</td>";
echo "<td></td>";
echo "<td></td>";
echo "<td></td>";
echo "<td align='right'>";
echo number_format((float)$maxG,2);
	echo "</td>";
echo "<td></td>";
echo "<td></td>";
echo "<td></td>";
echo "<td align='right'>";
echo number_format((float)$maxR,2);
	echo "</td>";
echo "<td></td>";
echo "<td></td>";
echo "<td></td>";
echo "<td align='right'>";
echo number_format((float)$maxT,2);
	echo "</td>";
echo "<td></td>";
echo "<td></td>";
echo "</tr>";


$count = 0;
for ($i=count($json_dailyhist)-1; $i >= 0; $i = $i -1)
{

	echo "<tr>";
	echo "<td align='center'>";
	echo $json_dailyhist[$i]["EvaluationDateTime"];
	echo "</td>";
	echo "<td align='right'>";
	echo number_format((float)$json_dailyhist[$i]["bAmount"],2);
	echo "</td>";
	
	echo WriteCell($json_dailyhist[$i]["bDelta"],0,1,2);
	echo WriteCell($json_dailyhist[$i]["bVar"],0,100,2);
	echo WriteCell(stocastico($json_dailyhist[$i]["bDelta"],$maxB,$minB),0.5,100,0);
	echo "<td align='right'>";
	echo number_format((float)$json_dailyhist[$i]["eAmount"],2);
	echo "</td>";

	echo WriteCell($json_dailyhist[$i]["eDelta"],0,1,2);
	echo WriteCell($json_dailyhist[$i]["eVar"],0,100,2);
	echo WriteCell(stocastico($json_dailyhist[$i]["eDelta"],$maxE,$minE),0.5,100,0);


	echo "<td align='right'>";
	echo number_format((float)$json_dailyhist[$i]["gAmount"],2);
	echo "</td>";
	
	echo WriteCell($json_dailyhist[$i]["gDelta"],0,1,2);
	echo WriteCell($json_dailyhist[$i]["gVar"],0,100,2);
	echo WriteCell(stocastico($json_dailyhist[$i]["gDelta"],$maxG,$minG),0.5,100,0);

		
	
	echo "<td align='right'>";
	echo number_format((float)$json_dailyhist[$i]["rAmount"],2);
	echo "</td>";
	
	echo WriteCell($json_dailyhist[$i]["rDelta"],0,1,2);
	echo WriteCell($json_dailyhist[$i]["rVar"],0,100,2);
	echo WriteCell(stocastico($json_dailyhist[$i]["rDelta"],$maxR,$minR),0.5,100,0);

	
	echo "<td align='right'>";
	echo number_format((float)$json_dailyhist[$i]["tAmount"],2);
	echo "</td>";
	
		
	echo WriteCell($json_dailyhist[$i]["tDelta"],0,1,2);
	echo WriteCell($json_dailyhist[$i]["tVar"],0,100,2);
	echo WriteCell(stocastico($json_dailyhist[$i]["tDelta"],$maxT,$minT),0.5,100,0);


	echo "</tr>";
	
	
}
echo "</tbody>";
echo "</table>";
echo "<br/>";



?>

<script type="text/javascript">

var series;
$.ajax({
	url: 'dailyAssetClassDataNew.json',
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
			{ name:"Bond", data: [],lineWidth: 3,color: '#D3D3D3',symbol: 'cross'}, 
			{ name:"Equity",data: [],lineWidth: 3, color: '#ADD8E6'},
			{ name:"Gold",data: [],lineWidth: 3, color: '#FFD700'},
			{ name:"Real Estate",data: [],lineWidth: 3, color: '#7FFFD4'},
			{ name:"Total",data: [],lineWidth: 3,color: '#FF0000'}
		];

		
		for (var i = 0, l = json.length; i < l; i++) {
			x = Date.parse(json[i].EvaluationDateTime);
			series[0].data[i] = [x, json[i].bDelta];
			series[1].data[i] = [x, json[i].eDelta];
			series[2].data[i] = [x, json[i].gDelta];
			series[3].data[i] = [x, json[i].rDelta];
			series[4].data[i] = [x, json[i].tDelta];
		}
	var chart = new Highcharts.Chart({
    chart: {
        renderTo: 'container'
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

<script type="text/javascript">

var series;
$.ajax({
	url: 'dailyAssetClassDataNew.json',
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
			{ name:"Bond", data: [],lineWidth: 3,color: '#D3D3D3',symbol: 'cross'}, 
			{ name:"Equity",data: [],lineWidth: 3, color: '#ADD8E6'},
			{ name:"Gold",data: [],lineWidth: 3, color: '#FFD700'},
			{ name:"Real Estate",data: [],lineWidth: 3, color: '#7FFFD4'},
			{ name:"Total",data: [],lineWidth: 3,color: '#FF0000'}
		];

		
		for (var i = 0, l = json.length; i < l; i++) {
			x = Date.parse(json[i].EvaluationDateTime);
			series[0].data[i] = [x, Math.round(json[i].bVar*10000)/100];
			series[1].data[i] = [x, Math.round(json[i].eVar*10000)/100];
			series[2].data[i] = [x, Math.round(json[i].gVar*10000)/100];
			series[3].data[i] = [x, Math.round(json[i].rVar*10000)/100];
			series[4].data[i] = [x, Math.round(json[i].tVar*10000)/100];
		}
	var chart = new Highcharts.Chart({
    chart: {
        renderTo: 'container1'
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

<script type="text/javascript">

    var series;
    $.ajax({
        url: 'dailySecurityData.json',
        async: false,
        dataType: "text",
        error: function()
        {
            alert ("Impossibile generare il grafico - Variazione");
            //window.location.replace("monitors.shtml");
        },
        success: function(data) 
        {
            var json = $.parseJSON(data);
            let series = [];
			console.log(json.length);
			for (i=0;i<json.length;i++)
			{
				console.log(json[i].ticker);
				obj = {name:json[i].ticker,data: [],lineWidth: 3};
				series.push(obj);
				series[i].lineWidth = 3;
				for (var j=0; j < json[i].values.length; j++)
                {
					console.log(json[i].values[j].cycledatetime);
					x = Date.parse(json[i].values[j].cycledatetime);
                    series[i].data[j] = [x, json[i].values[j].delta];
				}
			}

			//console.log(series[0].data);
			
			
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
                    title: {text: 'Variazione(%)'}
        }, {
            linkedTo: 0,
            opposite: true,
             title: {text: 'Variazione(%)'}
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



<script type="text/javascript">

    var series;
    $.ajax({
        url: 'dailySecurityData.json',
        async: false,
        dataType: "text",
        error: function()
        {
            alert ("Impossibile generare il grafico - Variazione");
            //window.location.replace("monitors.shtml");
        },
        success: function(data) 
        {
            var json = $.parseJSON(data);
            let series = [];
			console.log(json.length);
			for (i=0;i<json.length;i++)
			{
				console.log(json[i].ticker);
				obj = {name:json[i].ticker,data: [],lineWidth: 3};
				series.push(obj);
				series[i].lineWidth = 3;
				for (var j=0; j < json[i].values.length; j++)
                {
					console.log(json[i].values[j].cycledatetime);
					x = Date.parse(json[i].values[j].cycledatetime);
                    series[i].data[j] = [x, Math.round(json[i].values[j].var*10000)/100];
				}
			}

			//console.log(series[0].data);
			
			
        var chart = new Highcharts.Chart({
        chart: {
            renderTo: 'container3'
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
                    title: {text: 'Variazione(%)'}
                    
                    
        }, {
            linkedTo: 0,
            opposite: true,
             title: {text: 'Variazione(%)'}
        }],
        
        
        
        
        /*yAxis: {
                plotLines: [{
                    color: '#FF0000',
                    width: 1,
                    value: 0,
                    zIndex:2}]
            },
        */
        
            
        
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

</body>
</html>
