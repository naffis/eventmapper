<?php
	require_once('functions.php');

	$newline = "<br />";

	$charArray = Array("0", "1", "2", "3", "4", "5", "6", "7", "8", "9", "a", "b",
		"c", "d", "e", "f", "g", "h", "i", "j", "k", "l", "m", "n", "o", "p",
		"q", "r", "s", "t", "u", "v", "w", "x", "y", "z");
	$charString = implode("", $charArray);
	$charStringLength = strlen($charString);

	$cdint = strtotime("08/08/2005");
	$dateString = "a17";

	$cdstring = date("m/d/Y", strtotime("now"));
	$today = strtotime($cdstring);

	$oneday = 60*60*24;

	//echo $cdint."<br>";
	//echo $today."<br>";

	// increment the day code to the current day
	while($cdint < $today) {
		$dateString = getNext($dateString);
		$cdint = $cdint + $oneday;
		//echo $dateString."<br>";
	}

	function getNext($currentString) {
		//echo "currentString = ".$currentString."<br>";

		$char0 = $currentString[0];
		$char1 = $currentString[1];
		$char2 = $currentString[2];

		$pos0 = strpos($GLOBALS["charString"], $char0);
		$pos1 = strpos($GLOBALS["charString"], $char1);
		$pos2 = strpos($GLOBALS["charString"], $char2);

		if($pos2 == $GLOBALS["charStringLength"]-1) {
			if($pos1 == $GLOBALS["charStringLength"]-1) {
				return $GLOBALS["charArray"][$pos0++].$GLOBALS["charArray"][0].$GLOBALS["charArray"][0];
			}
			else
				return $GLOBALS["charArray"][$pos0].$GLOBALS["charArray"][$pos1+1].$GLOBALS["charArray"][0];
		}
		else {
			return $GLOBALS["charArray"][$pos0].$GLOBALS["charArray"][$pos1].$GLOBALS["charArray"][$pos2+1];
		}
	}


/////////////////////

	$url = "http://www.washingtonpost.com/ac2/wp-dyn?node=entertainment/search&type=allevent&sort=name&rdc=";
	$sourcesite = "2";

	for ($count = 0; $count <= 90; $count++) {
		echo "Retrieving data for ";
		echo date("m/d/Y", $cdint+($count*$oneday));
		echo ", code: ".$dateString."<br>";

		//do whatever processing here with URL

		$crawlurl = $url.$dateString;

		// get the number of records found
		$num_records = get_num_records_wp($crawlurl);
		echo "num_records = ".$num_records."<br>";

		// go through each page and parse out the events
		for ($counter = 1; $counter <= $num_records; $counter += 20) {
			echo "count = ".$counter."<br>";

			$pageurl = "http://www.washingtonpost.com/ac2/wp-dyn?node=entertainment/search&type=allevent&rdc=".$dateString."&sort=name&start=".$counter;
			$content = file_get_contents($pageurl);

			parse_events($content, date("m/d/Y", $cdint+($count*$oneday)));
		}

		$dateString = getNext($dateString);
	}

///////////////////////

/*
	for ($daycount = 1; $daycount <= 30; $daycount++) {
		$cdint = strtotime("+".$daycount." day");
		$cdstring = date("m/d/Y", $cdint);

		$crawlurl = $url.$cdstring;

		// get the number of records found
		$num_records = get_num_records_wp($crawlurl);
		echo "num_records = ".$num_records."<br>";

		echo "Getting events for ".$cdstring."<br>";

		// go through each page and parse out the events
		for ($counter = 1; $counter <= $num_records; $counter += 20) {
			echo "count = ".$counter."<br>";

			$pageurl = $url."&start=".$counter;
			$content = file_get_contents($pageurl);

			parse_events($content);
		}

	}
*/

?>