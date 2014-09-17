<?php

if (isset($_POST['action'])  && $_POST['action']=='saveMarker' ){ //If this was triggered by AJAX, then insert the point inside the database

	//Connect to DB
	$link = mysql_connect('localhost', 'root', 'root'); //just in case if this doesn't work try '' password

	if (!$link) { die('Could not connect: ' . mysql_error());}

	mysql_select_db("jenn_map",$link) or die(mysql_error());
	$POIS_TBL		= 'pois';

	//This should insert the marker inside the database
	$row = '\''.$_POST['marker_text'].'\' , \''.$_POST['lat'].'\', \''.$_POST['lng'].'\'';
	$sql = sprintf('INSERT INTO '.$POIS_TBL.' (%s) VALUES (%s)', 'name, lat,lng', $row );
	echo $sql;
	mysql_query($sql);

	mysql_close($link);

}


function getAllPOIS(){

	$link = @mysql_connect('localhost', 'root', 'root'); //just in case if this doesn't work try '' password
	if (!$link) { die('Could not connect: ' . mysql_error());}
	@mysql_select_db("jenn_map",$link) or die(mysql_error());
	$POIS_TBL		= 'pois';

	$sql_poi	= sprintf('SELECT * FROM '.$POIS_TBL);
	$result = @mysql_query($sql_poi);

	while ($row = mysql_fetch_assoc($result)) {
	    $markers[]	= $row;
	}

	return $markers;
}
?>