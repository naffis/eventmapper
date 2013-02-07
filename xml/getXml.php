<?php

$type = $_GET['type'];
$e_date = $_GET['event_date'];
$date = str_replace("-", "/", $e_date);

$sql = "select * from events where ";
$sql = $sql."event_date = '".$date."'";

//echo $sql;

$dblink = mysql_connect("localhost", "connexo_connexon", "90a!2$") or die("ERROR: count not connect");
mysql_select_db("connexo_mapdata", $dblink) or die("ERROR: count not select db");

$result = mysql_query($sql, $dblink) or die("ERROR: query failed<br>sql = ".$sql."<br>");
$num = mysql_numrows($result);

$xmlString = "";

if($type == "map") {
	$xmlString .= "<?xml version=\"1.0\"?>";
	$xmlString .= "<page>";
	$xmlString .= "<title></title>";
	$xmlString .= "<query></query>";
	$xmlString .= "<request>";
	$xmlString .= "<url></url>";
	$xmlString .= "<query></query>";
	$xmlString .= "</request>";

	$xmlString .= "<center lat=\"38.891033\" lng=\"-77.035446\"/>";
	$xmlString .= "<span lat=\"0.062134\" lng=\"0.104253\"/>";
	$xmlString .= "<searchcenter lat=\"38.891033\" lng=\"-77.035446\"/>";
	$xmlString .= "<searchspan lat=\"0.062134\" lng=\"0.104253\"/>";

	$xmlString .= "<overlay panelStyle=\"\">";

	$i=0;
	while ($i < $num) {
		$event_id=mysql_result($result,$i,"event_id");
		$name=mysql_result($result,$i,"name");
		$area=mysql_result($result,$i,"area");
		$source_id=mysql_result($result,$i,"source_id");
		$description=mysql_result($result,$i,"description");
		$venue=mysql_result($result,$i,"venue");

		$lat=mysql_result($result,$i,"lat");
		$lon=mysql_result($result,$i,"lon");

		$venue = str_replace("&nbsp;", " ", $venue);
		$venue = str_replace("&", "&amp;", $venue);
		$venue = str_replace("nbsp;", " ", $venue);
		$venue = str_replace("</font>", " ", $venue);
		$venue = str_replace("<font>", " ", $venue);

		$area = str_replace("<font>", " ", $area);
		$area = str_replace("</font>", " ", $area);

		$name = str_replace("&nbsp;", " ", $name);
		$name = str_replace("&", "&amp;", $name);
		$name = str_replace("nbsp;", " ", $name);
		$name = str_replace("<font>", " ", $name);
		$name = str_replace("</font>", " ", $name);

		$description = str_replace("&nbsp;", " ", $description);
		$description = str_replace("<br>", " ", $description);
		$description = str_replace("\n", "", $description);
		$description = str_replace("nbsp;", " ", $description);
		$description = str_replace("&", "&amp;", $description);
		$description = str_replace("<font>", " ", $description);
		$description = str_replace("</font>", " ", $description);

		$xmlString .= "<location infoStyle=\"http://www.naffis.com/eventmapper/xsl/infostyle_map.xsl\" id=\"".$i."\">\n";
		$xmlString .= "<point lat=\"".$lat."\" lng=\"".$lon."\"/>\n";
		$xmlString .= "<icon image=\"http://www.naffis.com/eventmapper/images/society.png\" class=\"local\"/>\n";
		$xmlString .= "<info>\n";
		$xmlString .= "<id>".$i."</id>\n";
		$xmlString .= "<title xml:space=\"preserve\">Event Details</title>\n";

		$details_link = "http://www.washingtonpost.com/ac2/wp-dyn?node=entertainment/profile&id=".$source_id;

		$xmlString .= "<eventid>".$event_id."</eventid>\n";
		$xmlString .= "<name>".$name."</name>\n";
		$xmlString .= "<area>".$area."</area>\n";
		$xmlString .= "<venue>".$venue."</venue>\n";
		$xmlString .= "<source_id>".$source_id."</source_id>\n";
		$xmlString .= "<description>".$description."</description>\n";
		$xmlString .= "<url>".str_replace("&", "&amp;", $details_link)."</url>\n";

		$xmlString .= "</info>\n";
		$xmlString .= "</location>\n";

		$i++;
	}

	$xmlString .= "</overlay>";
	$xmlString .= "</page>";

}
else if($type == "list") {

	$xmlString = "<events>";

	$i=0;
	while ($i < $num) {
		$name=mysql_result($result,$i,"name");
		$area=mysql_result($result,$i,"area");
		$venue=mysql_result($result,$i,"venue");

		$name = trim($name);
		$area = trim($area);
		$venue = trim($venue);

		$name = str_replace("&nbsp;", " ", $name);
		$name = str_replace("<br>", " ", $name);
		$name = str_replace("\n", "", $name);
		$name = str_replace("nbsp;", " ", $name);
		$name = str_replace("&", "&amp;", $name);
		$name = str_replace("</font>", " ", $name);
		$name = str_replace("<font>", " ", $name);

		$area = str_replace("&nbsp;", " ", $area);
		$area = str_replace("<br>", " ", $area);
		$area = str_replace("\n", "", $area);
		$area = str_replace("nbsp;", " ", $area);
		$area = str_replace("&", "&amp;", $area);
		$area = str_replace("</font>", " ", $area);
		$area = str_replace("<font>", " ", $area);

		$venue = str_replace("&nbsp;", " ", $venue);
		$venue = str_replace("<br>", " ", $venue);
		$venue = str_replace("\n", "", $venue);
		$venue = str_replace("nbsp;", " ", $venue);
		$venue = str_replace("&", "&amp;", $venue);
		$venue = str_replace("</font>", " ", $venue);
		$venue = str_replace("<font>", " ", $venue);

		$xmlString .= "<event>\n";
		$xmlString .= "<id>".$i."</id>\n";
		$xmlString .= "<name>".$name."</name>\n";
		$xmlString .= "<venue>".$venue."</venue>\n";
		$xmlString .= "<area>".$area."</area>\n";
		$xmlString .= "</event>\n";

		$i++;
	}
	$xmlString .= "</events>";

}

	header("Content-Type: text/xml");

	print($xmlString)

?>