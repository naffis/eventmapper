<?php

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


	for ($count = 0; $count <= 30; $count++) {
		echo date("m/d/Y", $cdint+($count*$oneday))."<br>";
		echo $dateString."<br>";

		//do whatever processing here with URL


		$dateString = getNext($dateString);
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
?>