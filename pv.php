<html>
<head>
<title>Portfolio Viewer</title>
<link rel="stylesheet" type="text/css" href="pv.css" />
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
<!--link type='text/css' href='js/CookieCompliance/stylesheet.css' rel='stylesheet'-->
<!--#include virtual="include/meta.inc" -->
</head>
<body>
<?php

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






$xml = simplexml_load_file("pv.xml");
echo "<br/>";
echo "<h1>Portfolio Viewer</h1>";

echo $xml->updateAt;

$attr = "updateAt";
//$newDateTime= date("Y-m-d H:i:s", strtotime($originalDate)); 

echo "<h5>Aggiornato al " . date("Y-m-d H:i:s", strtotime($xml->attributes()->$attr)) . "</h5>";

echo "<br/>";
echo "<h2>Suddivione per titolo</h2>";
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

$totalAmountLast = 0;
$yesterdayTotalAmount = 0;
$yesterdayTotalDelta = 0;
$lastWeekTotalAmount = 0;
$lastWeekTotalDelta = 0;
$lastMonthTotalAmount = 0;
$lastMonthTotalDelta = 0;

foreach($xml->securities->security as $item)
{

	$totalAmountLast = $totalAmountLast + (float)$item->amount;
	$yesterdayTotalAmount = $yesterdayTotalAmount + (float)$item->yesterdayamount;
	$yesterdayTotalDelta = $yesterdayTotalDelta + (float)$item->yesterdaydelta;
	$lastWeekTotalAmount = $lastWeekTotalAmount  + (float)$item->lastweekamount;
	$lastWeekTotalDelta = $lastWeekTotalDelta   + (float)$item->lastweekdelta;
	$lastMonthTotalAmount = $lastMonthTotalAmount + (float)$item->lastmonthamount;
	$lastMonthTotalDelta = $lastMonthTotalDelta   + (float)$item->lastmonthdelta;

	

echo "<tr>";
echo "<td>";
echo (string)$item->isincode;
echo "</td>";
echo "<td>";
echo (string)$item->description;
echo "</td>";
echo "<td>";
echo "<a href=detail.php?ticker=". (string)$item->ticker . ">" . (string)$item->ticker . "</a>";
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
echo number_format((float)$item->yesterdayprice,3);
echo "</td>";



echo "<td align='right' class='font-weight-bold' >";
echo number_format((float)$item->marketprice,3);
echo "</td>";




echo "<td align='right'>";
echo number_format((float)$item->amount,2);
echo "</td>";


if ((float)$item->yesterdaydelta >= 0) 
{
echo "<td class='table-success' align='right'>";
}
else
{
echo "<td class='table-danger' align='right'>";
}
echo number_format((float)$item->yesterdaydelta,2);
echo "</td>";

if ((float)$item->yesterdayvar >= 0) 
{
echo "<td class='table-success' align='right'>";
}
else
{
echo "<td class='table-danger' align='right'>";
}
echo number_format((float)$item->yesterdayvar*100,2) . "%";
echo "</td>";
if ((float)$item->lastweekdelta >= 0) 
{
echo "<td class='table-success' align='right'>";
}
else
{
echo "<td class='table-danger' align='right'>";
}
echo number_format((float)$item->lastweekdelta,2);
echo "</td>";

if ((float)$item->lastweekvar >= 0) 
{
echo "<td class='table-success' align='right'>";
}
else
{
echo "<td class='table-danger' align='right'>";
}
echo number_format((float)$item->lastweekvar*100,2) . "%";
echo "</td>";
if ((float)$item->lastmonthdelta >= 0) 
{
echo "<td class='table-success' align='right'>";
}
else
{
echo "<td class='table-danger' align='right'>";
}
echo number_format((float)$item->lastmonthdelta,2);
echo "</td>";
if ((float)$item->lastmonthvar >= 0) 
{
echo "<td class='table-success' align='right'>";
}
else
{
echo "<td class='table-danger' align='right'>";
}
echo number_format((float)$item->lastmonthvar*100,2) . "%";
echo "</td>";
echo "</tr>";
}

echo "<tr class='table-primary'>";
echo "<td colspan='5' align='center'><b>Totale</b></td>";
echo "<td align='right'>" . number_format((float)$totalAmountLast,2) . "</td>";
echo "<td align='right'>" . number_format((float)$yesterdayTotalDelta,2) . "</td>";
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



echo "<h2>Storico giornaliero per asset class</h2>";
echo "<table class='table-bordered center' width='85%' >";
echo "<thead>";
echo "<tr class='table-warning text-center'>";
echo "<th></th>";
echo "<th colspan='4'>Bond</th>";
echo "<th colspan='4'>Equity</th>";
echo "<th colspan='4'>Gold</th>";
echo "<th colspan='4'>Real Estate</th>";
echo "<th colspan='4'>Totale</th>";
echo "</tr>";
echo "<tr class='table-primary text-center'>";
echo "<th>Date-time</th>";
echo "<th>Amount</th>";
echo "<th>Delta</th>";
echo "<th>Var(%)</th>";
echo "<th>Sto(%)</th>";
echo "<th>Amount</th>";
echo "<th>Delta</th>";
echo "<th>Var(%)</th>";
echo "<th>Sto(%)</th>";
echo "<th>Amount</th>";
echo "<th>Delta</th>";
echo "<th>Var(%)</th>";
echo "<th>Sto(%)</th>";
echo "<th>Amount</th>";
echo "<th>Delta</th>";
echo "<th>Var(%)</th>";
echo "<th>Sto(%)</th>";
echo "<th>Amount</th>";
echo "<th>Delta</th>";
echo "<th>Var(%)</th>";
echo "<th>Sto(%)</th>";
echo "</tr>";
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
foreach($xml->evaluationsBySecurityType->evaluationsBySecurityType as $item)
{
	if ($count == 1)
{
	$maxB = (float)$item->bondDelta;
	$minB = (float)$item->bondDelta;
	$maxE = (float)$item->equityDelta;
	$minE = (float)$item->equityDelta;
	$maxG = (float)$item->goldDelta;
	$minG = (float)$item->goldDelta;
	$maxR = (float)$item->REDelta;
	$minR = (float)$item->REDelta;
	$maxT = (float)$item->totalDelta;
	$minT = (float)$item->totalDelta;

}
else
{
	
	$maxB = Max((float)$item->bondDelta,$maxB);
	$minB = Min((float)$item->bondDelta,$minB);

	$maxE = Max((float)$item->equityDelta,$maxE);
	$minE = Min((float)$item->equityDelta,$minE);

	$maxG = Max((float)$item->goldDelta,$maxG);
	$minG = Min((float)$item->goldDelta,$minG);

	$maxR = Max((float)$item->REDelta,$maxR);
	$minR = Min((float)$item->REDelta,$minR);

	$maxT = Max((float)$item->totalDelta,$maxT);
	$minT = Min((float)$item->totalDelta,$minT);

}
$count = $count + 1;	
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


$count = 1;
foreach($xml->evaluationsBySecurityType->evaluationsBySecurityType as $item)
{
	if ($count > 0)
	{
	echo "<tr>";
	echo "<td align='center'>";
	echo $item->evaluationDateTime;
	echo "</td>";
	echo "<td align='right'>";
	echo number_format((float)$item->bondAmount,2);
	echo "</td>";
	
	echo WriteCell($item->bondDelta,0,1,2);
	echo WriteCell($item->bondVar,0,100,2);
	echo WriteCell(stocastico($item->bondDelta,$maxB,$minB),0.5,100,0);
	echo "<td align='right'>";
	echo number_format((float)$item->equityAmount,2);
	echo "</td>";

	echo WriteCell($item->equityDelta,0,1,2);
	echo WriteCell($item->equityVar,0,100,2);
	echo WriteCell(stocastico($item->equityDelta,$maxE,$minE),0.5,100,0);


	echo "<td align='right'>";
	echo number_format((float)$item->goldAmount,2);
	echo "</td>";
	
	echo WriteCell($item->goldDelta,0,1,2);
	echo WriteCell($item->goldVar,0,100,2);
	echo WriteCell(stocastico($item->goldDelta,$maxG,$minG),0.5,100,0);

		
	
	echo "<td align='right'>";
	echo number_format((float)$item->REAmount,2);
	echo "</td>";
	
	echo WriteCell($item->REDelta,0,1,2);
	echo WriteCell($item->REVar,0,100,2);
	echo WriteCell(stocastico($item->REDelta,$maxR,$minR),0.5,100,0);

	
	echo "<td align='right'>";
	echo number_format((float)$item->totalAmount,2);
	echo "</td>";
	
		
	echo WriteCell($item->totalDelta,0,1,2);
	echo WriteCell($item->totalVar,0,100,2);
	echo WriteCell(stocastico($item->totalDelta,$maxT,$minT),0.5,100,0);


	echo "</tr>";
	}
	$count = $count + 1;
}





echo "</tbody>";
echo "</table>";
echo "<br/><br/>";



?>






</body>
</html>
