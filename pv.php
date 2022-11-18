<html>
<head>
<title>Portfolio Viewer</title>
<link rel="stylesheet" type="text/css" href="pv.css" />
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script type="text/javascript" src="http://code.highcharts.com/highcharts.js"></script>



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
$json = file_get_contents('currentPortfolio.json');
// Decode the JSON file
$json_currentportfolio = json_decode($json,true);

// Read the JSON file 
$json = file_get_contents('DailyHistForAssetClass.json');
$json_dailyhist = json_decode($json,true);







echo "<br/>";
echo "<h1>Portfolio Viewer</h1>";
//echo "<h5>Aggiornato al " . date("Y-m-d H:i:s", strtotime($json_dailyhist[0]["EveluationTimeDate"])) . "</h5>";
$currentDate = date_parse($json_dailyhist[count($json_dailyhist)-1]["EvaluationDateTime"]);



echo "<h5>" . dateFormatter($currentDate["day"],$currentDate["month"],$currentDate["year"],$currentDate["hour"],$currentDate["minute"]) . "</h5>";

//echo "<br/>";
//echo "<h2>Suddivione per titolo</h2>";
echo "<div>";
echo "<table class='table-bordered center' width='85%' >";
echo "<thead>";
//echo "<tr class='table-warning'><th colspan='2' align='center'>Security</th><th colspan='3'>Portfolio</th><th colspan='2'>Last</th><th colspan='2'>Yesterday</th><th colspan='2'>WTD</th><th colspan='2'>MTD</th>";
echo "<tr class='table-warning'><th colspan='4' align='center'>Security</th><th colspan='2'>Last</th><th colspan='2'>Yesterday</th><th colspan='2'>WTD</th><th colspan='2'>MTD</th>";
echo "<tr class='table-primary'>";
echo "<th class='text-center' width='9%'>ISIN Code</th>";
echo "<th class='text-center' >Description</th>";
echo "<th class='text-center' >Ticker</th>";
echo "<th class='text-center' >Ref price</th>";
/*echo "<th class='text-center' width='6%'>Quantity</th>";
echo "<th class='text-center' width='6%'>PMC</th>";*/
echo "<th class='text-center' width='6%'>Price</th>";
echo "<th class='text-center' width='6%'>Amount</th>";
echo "<th class='text-center' width='6%'>Delta</th>";
echo "<th class='text-center' width='6%'>Var</th>";
echo "<th class='text-center' width='6%'>Delta</th>";
echo "<th class='text-center' width='6%'>Var</th>";
echo "<th class='text-center' width='6%'>Delta</th>";
echo "<th class='text-center' width='6%'>Var</th>";
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
	$lastWeekTotalAmount = $lastWeekTotalAmount   + (float)$json_currentportfolio[$i]["wtdamount"];
	$lastWeekTotalDelta = $lastWeekTotalDelta    + (float)$json_currentportfolio[$i]["wtddelta"];
	$lastMonthTotalAmount = $lastMonthTotalAmount + (float)$json_currentportfolio[$i]["mtdamount"];
	$lastMonthTotalDelta = $lastMonthTotalDelta   + (float)$json_currentportfolio[$i]["mtddelta"];



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
if ((float)$json_currentportfolio[$i]["wtddelta"] >= 0) 
{
echo "<td class='table-success' align='right'>";
}
else
{
echo "<td class='table-danger' align='right'>";
}
echo number_format((float)$json_currentportfolio[$i]["wtddelta"],2);
echo "</td>";

if ((float)$json_currentportfolio[$i]["wtdvar"] >= 0) 
{
echo "<td class='table-success' align='right'>";
}
else
{
echo "<td class='table-danger' align='right'>";
}
echo number_format((float)$json_currentportfolio[$i]["wtdvar"]*100,2) . "%";
echo "</td>";
if ((float)$json_currentportfolio[$i]["mtddelta"] >= 0) 
{
echo "<td class='table-success' align='right'>";
}
else
{
echo "<td class='table-danger' align='right'>";
}
echo number_format((float)$json_currentportfolio[$i]["mtddelta"],2);
echo "</td>";
if ((float)$json_currentportfolio[$i]["mtdvar"] >= 0) 
{
echo "<td class='table-success' align='right'>";
}
else
{
echo "<td class='table-danger' align='right'>";
}
echo number_format((float)$json_currentportfolio[$i]["mtdvar"]*100,2) . "%";
echo "</td>";
echo "</tr>";
}

echo "<tr class='table-primary'>";
echo "<td colspan='5' align='center'><b>Totale</b></td>";
echo "<td align='right'>" . number_format((float)$totalAmountLast,2) . "</td>";
echo "<td align='right' class='font-weight-bold'>" . number_format((float)$yesterdayTotalDelta,2) . "</td>";
$yesterdaytotalvar = ($totalAmountLast / $yesterdayTotalAmount  - 1)*100;
echo "<td align='right'>" . number_format($yesterdaytotalvar,2) .  "%</td>";
echo "<td align='right'>" . number_format((float)$lastWeekTotalDelta,2) . "</td>";
$lastweektotalvar = ($totalAmountLast / $lastWeekTotalAmount - 1)*100;
echo "<td align='right'>" . number_format($lastweektotalvar,2) .  "%</td>";
echo "<td align='right'>" . number_format((float)$lastMonthTotalDelta,2) . "</td>";
$lastmonthtotalvar = ($totalAmountLast /$lastMonthTotalAmount- 1)*100;
echo "<td align='right'>" . number_format($lastmonthtotalvar,2) .  "%</td>";
echo "</table>";
echo "<br>";

echo "<div id='container' style='height: 600px' ></div>";

echo "<h2>Storico giornaliero per asset class</h2>";
echo "<table class='table-bordered center' width='85%' >";
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
		$maxB = (float)$json_dailyhist[$i]["BondDelta"];
		$minB = (float)$json_dailyhist[$i]["BondDelta"];
		$maxE = (float)$json_dailyhist[$i]["EquityDelta"];
		$minE = (float)$json_dailyhist[$i]["EquityDelta"];
		$maxG = (float)$json_dailyhist[$i]["GoldDelta"];
		$minG = (float)$json_dailyhist[$i]["GoldDelta"];
		$maxR = (float)$json_dailyhist[$i]["REDelta"];
		$minR = (float)$json_dailyhist[$i]["REDelta"];
		$maxT = (float)$json_dailyhist[$i]["TotalDelta"];
		$minT = (float)$json_dailyhist[$i]["TotalDelta"];
	}
	else
	{
		$maxB = Max((float)$json_dailyhist[$i]["BondDelta"],$maxB);
		$minB = Min((float)$json_dailyhist[$i]["BondDelta"],$minB);
		$maxE = Max((float)$json_dailyhist[$i]["EquityDelta"],$maxE);
		$minE = Min((float)$json_dailyhist[$i]["EquityDelta"],$minE);
		$maxG = Max((float)$json_dailyhist[$i]["GoldDelta"],$maxG);
		$minG = Min((float)$json_dailyhist[$i]["GoldDelta"],$minG);
		$maxR = Max((float)$json_dailyhist[$i]["REDelta"],$maxR);
		$minR = Min((float)$json_dailyhist[$i]["REDelta"],$minR);
		$maxT = Max((float)$json_dailyhist[$i]["TotalDelta"],$maxT);
		$minT = Min((float)$json_dailyhist[$i]["TotalDelta"],$minT);
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
	echo number_format((float)$json_dailyhist[$i]["BondAmount"],2);
	echo "</td>";
	
	echo WriteCell($json_dailyhist[$i]["BondDelta"],0,1,2);
	echo WriteCell($json_dailyhist[$i]["BondVar"],0,100,2);
	echo WriteCell(stocastico($json_dailyhist[$i]["BondDelta"],$maxB,$minB),0.5,100,0);
	echo "<td align='right'>";
	echo number_format((float)$json_dailyhist[$i]["EquityAmount"],2);
	echo "</td>";

	echo WriteCell($json_dailyhist[$i]["EquityDelta"],0,1,2);
	echo WriteCell($json_dailyhist[$i]["EquityVar"],0,100,2);
	echo WriteCell(stocastico($json_dailyhist[$i]["EquityDelta"],$maxE,$minE),0.5,100,0);


	echo "<td align='right'>";
	echo number_format((float)$json_dailyhist[$i]["GoldAmount"],2);
	echo "</td>";
	
	echo WriteCell($json_dailyhist[$i]["GoldDelta"],0,1,2);
	echo WriteCell($json_dailyhist[$i]["GoldVar"],0,100,2);
	echo WriteCell(stocastico($json_dailyhist[$i]["GoldDelta"],$maxG,$minG),0.5,100,0);

		
	
	echo "<td align='right'>";
	echo number_format((float)$json_dailyhist[$i]["REAmount"],2);
	echo "</td>";
	
	echo WriteCell($json_dailyhist[$i]["REDelta"],0,1,2);
	echo WriteCell($json_dailyhist[$i]["REVar"],0,100,2);
	echo WriteCell(stocastico($json_dailyhist[$i]["REDelta"],$maxR,$minR),0.5,100,0);

	
	echo "<td align='right'>";
	echo number_format((float)$json_dailyhist[$i]["TotalAmount"],2);
	echo "</td>";
	
		
	echo WriteCell($json_dailyhist[$i]["TotalDelta"],0,1,2);
	echo WriteCell($json_dailyhist[$i]["TotalVar"],0,100,2);
	echo WriteCell(stocastico($json_dailyhist[$i]["TotalDelta"],$maxT,$minT),0.5,100,0);


	echo "</tr>";
	
	
}
echo "</tbody>";
echo "</table>";
echo "<br/>";
echo "<h2>Storico giornaliero per titolo</h2>";
echo "<div id='container2' style='height: 800px' ></div>";




?>

<script type="text/javascript">

var series;
$.ajax({
	url: 'DailyHistForAssetClass.json',
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
			series[0].data[i] = [x, json[i].BondDelta];
			series[1].data[i] = [x, json[i].EquityDelta];
			series[2].data[i] = [x, json[i].GoldDelta];
			series[3].data[i] = [x, json[i].REDelta];
			series[4].data[i] = [x, json[i].TotalDelta];
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
            enabled: false
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

<script type="text/javascript">

    var series;
    $.ajax({
        url: 'DailyDataSecurityGraph.json',
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
                { name:"EMI", data: [],lineWidth: 3}, 
                { name:"ENI",data: [],lineWidth: 3},
                { name:"EPRA",data: [],lineWidth: 3},
                { name:"SW2CHB",data: [],lineWidth: 3},
                { name:"SWRD",data: [],lineWidth: 3},
                { name:"HYS",data: [],lineWidth: 3},
                { name:"ISP",data: [],lineWidth: 3},
                { name:"MSFT",data: [],lineWidth: 3},
                { name:"MWRD",data: [],lineWidth: 3},
                { name:"SGLD",data: [],lineWidth: 3},
            ];
               
            for (var i = 1; i < json.length; i++) {
                x = json[i].EveluationTime
                series[0].data[i-1] = [x, json[i].emi];
                series[1].data[i-1] = [x, json[i].eni];
                series[2].data[i-1] = [x, json[i].epra];
                series[3].data[i-1] = [x, json[i].sw2chb];
                series[4].data[i-1] = [x, json[i].swrd];
                series[5].data[i-1] = [x, json[i].hys];
                series[6].data[i-1] = [x, json[i].isp];
                series[7].data[i-1] = [x, json[i].msft];
                series[8].data[i-1] = [x, json[i].mwrd];
                series[9].data[i-1] = [x, json[i].sgld];
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
                enabled: false
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
