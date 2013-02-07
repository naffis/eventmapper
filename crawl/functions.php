<?php

$DB_NAME = "dbname";
$DB_USER = "username";
$DB_PASS = "password";
$DB_HOST = "localhost";

// get the number of records in washington post
function get_num_records_wp($url)
{
	$pageCont = file_get_contents($url);

	$tokenBefore = "of <font color = red><b>";
	$tokenAfter = " matches</font></b>";
	$indexBeforeToken = strpos($pageCont, $tokenBefore) + strlen($tokenBefore);
	$indexAfterToken = strpos($pageCont, $tokenAfter, $indexBeforeToken);

	$numRecords = substr($pageCont, $indexBeforeToken, $indexAfterToken-$indexBeforeToken);

	return (int)$numRecords;
}

function parse_events($pageContent, $dateofevents) {

	$tokenBefore = "<font size=\"-2\" face=\"verdana,sans-serif\" color=\"#000000\">";
	$tokenAfter = "<br>";

	$tokenBeforeId = "<a href=\"/ac2/wp-dyn?node=entertainment/profile&id=";
	$tokenAfterId = "&typeId=";

	$tokenBeforeName = "<font face=\"arial,sans-serif\" size=\"+1\"><b>";
	$tokenAfterName = "</b>";

	$tokenBeforeDescription = "<font color=\"#666666\">";
	$tokenAfterDescription = "</font>";

	$tokenBeforeVenue = "<a href=\"/ac2/wp-dyn?node=entertainment/profile&id=";
	$tokenAfterVenue = "</b>";

	$tokenBeforeLat = "&lat=";
	$tokenAfterLat = "&lon=";

	$tokenBeforeLon = "&lon=";
	$tokenAfterLon = "\">";

	$tokenBeforeArea = "<!---- Driving Directions ------->";
	$tokenAfterArea = "<br>";

	$currentPos = 0;


	$dblink = mysql_connect("localhost", "naffis_naffis", "naffis04host") or die("ERROR: count not connect");
	mysql_select_db("naffis_mapdata", $dblink) or die("ERROR: count not select db");

	while(strpos($pageContent, $tokenBefore, $currentPos))
	{
		$endPos = strlen($pageContent);
		$tempIndex = strpos($pageContent, $tokenBefore, $currentPos);
		if(strpos($pageContent, $tokenBefore, $tempIndex+5))
			$endPos = strpos($pageContent, $tokenBefore, $tempIndex+5);

		$dateString = "";
		$idString = "";
		$nameString = "";
		$descriptionString = "";
		$venueString = "";
		$latString = "";
		$lonString = "";
		$areaString = "";


		// get the date portion
		if(strpos($pageContent, $tokenBefore, $currentPos)) {
			$indexBeforeToken = strpos($pageContent, $tokenBefore, $currentPos) + strlen($tokenBefore);
			$indexAfterToken = strpos($pageContent, $tokenAfter, $indexBeforeToken);

			$currentPos = $indexAfterToken + strlen($tokenAfter);


			$dateString = substr($pageContent, $indexBeforeToken, $indexAfterToken-$indexBeforeToken);

			$dateString = trim($dateString);
			//echo "dateString = ".$dateString."<br>";
		}

		// get the id of the event
		if(strpos($pageContent, $tokenBeforeId, $currentPos)) {
			$indexBeforeIdToken = strpos($pageContent, $tokenBeforeId, $currentPos) + strlen($tokenBeforeId);
			$indexAfterIdToken = strpos($pageContent, $tokenAfterId, $indexBeforeIdToken);

			$currentPos = $indexAfterIdToken + strlen($tokenAfterId);

			$idString = substr($pageContent, $indexBeforeIdToken, $indexAfterIdToken-$indexBeforeIdToken);

			$idString = trim($idString);
			//echo "idString = ".$idString."<br>";
		}

		// get the name of the event
		if(strpos($pageContent, $tokenBeforeName, $currentPos)) {
			$indexBeforeNameToken = strpos($pageContent, $tokenBeforeName, $currentPos) + strlen($tokenBeforeName);
			$indexAfterNameToken = strpos($pageContent, $tokenAfterName, $indexBeforeNameToken);

			$currentPos = $indexAfterNameToken + strlen($tokenAfterName);

			$nameString = substr($pageContent, $indexBeforeNameToken, $indexAfterNameToken-$indexBeforeNameToken);

			$nameString = trim($nameString);
			//echo "nameString = ".$nameString."<br>";
		}

		// get the description of the event
		if(strpos($pageContent, $tokenBeforeDescription, $currentPos)) {
			$indexBeforeDescriptionToken = strpos($pageContent, $tokenBeforeDescription, $currentPos) + strlen($tokenBeforeDescription);
			$indexAfterDescriptionToken = strpos($pageContent, $tokenAfterDescription, $indexBeforeDescriptionToken);

			if($indexBeforeDescriptionToken > $endPos) {
				$descriptionString = "";
			}
			else {
				$currentPos = $indexAfterDescriptionToken + strlen($tokenAfterDescription);

				$descriptionString = substr($pageContent, $indexBeforeDescriptionToken, $indexAfterDescriptionToken-$indexBeforeDescriptionToken);

				$descriptionString = trim($descriptionString);
				//echo "descriptionString = ".$descriptionString."<br>";
			}
		}

		// get the venue of the event
		if(strpos($pageContent, $tokenBeforeVenue, $currentPos)) {
			$indexBeforeVenueToken = strpos($pageContent, $tokenBeforeVenue, $currentPos) + strlen($tokenBeforeVenue);
			$indexBeforeVenueToken = strpos($pageContent, "<b>", $indexBeforeVenueToken) + strlen("<b>");
			$indexAfterVenueToken = strpos($pageContent, $tokenAfterVenue, $indexBeforeVenueToken);

			$currentPos = $indexAfterVenueToken + strlen($tokenAfterVenue);

			$venueString = substr($pageContent, $indexBeforeVenueToken, $indexAfterVenueToken-$indexBeforeVenueToken);

			$venueString = trim($venueString);
			//echo "venueString = ".$venueString."<br>";
		}

		// get the lat of the event
		if(strpos($pageContent, $tokenBeforeLat, $currentPos)) {
			$indexBeforeLatToken = strpos($pageContent, $tokenBeforeLat, $currentPos) + strlen($tokenBeforeLat);
			$indexAfterLatToken = strpos($pageContent, $tokenAfterLat, $indexBeforeLatToken);

			$currentPos = $indexAfterLatToken;

			$latString = substr($pageContent, $indexBeforeLatToken, $indexAfterLatToken-$indexBeforeLatToken);

			$latString = trim($latString);
			//echo "latString = ".$latString."<br>";
		}

		// get the long of the event
		if(strpos($pageContent, $tokenBeforeLon, $currentPos)) {
			$indexBeforeLonToken = strpos($pageContent, $tokenBeforeLon, $currentPos) + strlen($tokenBeforeLon);
			$indexAfterLonToken = strpos($pageContent, $tokenAfterLon, $indexBeforeLonToken);

			$currentPos = $indexAfterLonToken + strlen($tokenAfterLon);

			$lonString = substr($pageContent, $indexBeforeLonToken, $indexAfterLonToken-$indexBeforeLonToken);

			$lonString = trim($lonString);
			//echo "lonString = ".$lonString."<br>";
		}

		// get the area of the event
		if(strpos($pageContent, $tokenBeforeArea, $currentPos)) {
			$indexBeforeAreaToken = strpos($pageContent, $tokenBeforeArea, $currentPos) + strlen($tokenBeforeArea);
			$indexAfterAreaToken = strpos($pageContent, $tokenAfterArea, $indexBeforeAreaToken);

			$currentPos = $indexAfterAreaToken + strlen($tokenAfterArea);

			$areaString = substr($pageContent, $indexBeforeAreaToken, $indexAfterAreaToken-$indexBeforeAreaToken);

			$areaString = trim($areaString);
			//echo "areaString = ".$areaString."<br>";
		}

		$startDate = escape($startDate);
		$endDate = escape($endDate);
		$idString = escape($idString);
		$nameString = escape($nameString);
		$descriptionString = escape($descriptionString);
		$venueString = escape($venueString);

		$latString = substr($latString, 0, 2).".".substr($latString, 2, strlen($latString));
		$latString = escape($latString);

		$lonString = substr($lonString, 0, 3).".".substr($lonString, 3, strlen($lonString));
		$lonString = escape($lonString);
		$areaString = escape($areaString);

		$sql = "SELECT event_id FROM events WHERE source_id = '$idString' AND event_date = '$dateofevents'";
		//echo "sql = ".$sql."<br>";

		$result = mysql_query($sql, $dblink) or die("ERROR: query failed<br>sql = ".$sql."<br>");
		$num=mysql_numrows($result);
		if($num == 0) {
			// now insert into the database;
			$sql = "INSERT INTO events (
					event_id,
					source_site,
					event_date,
					source_id,
					name,
					description,
					venue,
					lat,
					lon,
					area
				) VALUES (
					'',
					'washingtonpost',
					'$dateofevents',
					'$idString',
					'$nameString',
					'$descriptionString',
					'$venueString',
					'$latString',
					'$lonString',
					'$areaString'
			)";

			//echo $sql."<br>";

			$result = mysql_query($sql, $dblink) or die("ERROR: insert failed<br>sql = ".$sql."<br>");
			echo "inserted row<br>";
		}
		else {
			//echo "record already exists<br>";
			$existingId=mysql_result($result,0,"event_id");

			// now insert into the database;
			$sql = "UPDATE events SET
					event_id = '$existingId',
					source_site = 'washingtonpost',
					event_date = '$dateofevents',
					source_id = '$idString',
					name = '$nameString',
					description = '$descriptionString',
					venue = '$venueString',
					lat = '$latString',
					lon = '$lonString',
					area = '$areaString'
					WHERE event_id = '$existingId'
			";

			//echo $sql."<br>";
			$result = mysql_query($sql, $dblink) or die("ERROR: update failed<br>sql = ".$sql."<br>");
			echo "updated row, id = ".$existingId."<br>";
		}

		// reset the strings
		$idString = "";
		$nameString = "";
		$descriptionString = "";
		$venueString = "";
		$latString = "";
		$lonString = "";
		$areaString = "";
	}
}

function escape($str) {
	return addslashes($str);
}

function current_time($type, $gmt = 0) {
	switch ($type) {
		case 'mysql':
			if ($gmt) $d = gmdate('Y-m-d H:i:s');
			else $d = gmdate('Y-m-d H:i:s', (time() + -5 * 3600));
			return $d;
			break;
		case 'timestamp':
			if ($gmt) $d = time();
			else $d = time() + -5 * 3600;
			return $d;
			break;
	}
}

?>
