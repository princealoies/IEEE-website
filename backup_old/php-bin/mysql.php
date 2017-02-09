<?php
require_once("php-bin/Activity.php");

if(!mysql_connect("localhost", "webuser"))
{
	echo "<p>Error connecting to database</p>\n";
	trigger_error(mysql_error(), E_USER_ERROR);
}

if(!mysql_select_db("www"))
{
	echo "<p>Error selecting database</p>\n";
	trigger_error(mysql_error(), E_USER_ERROR);
}

function getActivity($id)
{
	$id = (int) $id;
	if($id <= 0) trigger_error("Invalid Activity ID", E_USER_ERROR);

	$sql = "SELECT activities.id, type, title, date, location, details,\n"
	     . "       execOnly, typeString, typeDetails\n"
	     . "	FROM activities, activityTypes\n"
	     . "	WHERE\n"
	     . "		activities.id = $id\n"
	     . "		AND activities.type = activityTypes.id\n"
	     . ";";

	if(!$sqlActivities = mysql_query($sql))
		trigger_error(mysql_error(), E_USER_ERROR);

	return new Activity(mysql_fetch_assoc($sqlActivities));
}

function getActivities()
{
	$sql = "SELECT activities.id, type, title, date, location, details,\n"
	     . "       typeString, typeDetails\n"
	     . "	FROM activities, activityTypes\n"
	     . "	WHERE activities.type = activityTypes.id\n"
	     . "    ORDER BY date DESC\n"
	     . ";";

	if(!$sqlActivities = mysql_query($sql)) die(mysql_error());
	
	while($activity = mysql_fetch_assoc($sqlActivities))
		$activities[] = new Activity($activity);

	return $activities;
}

function getExecutive($society)
{
	if($society != 'A' and $society != 'B')
		trigger_error("getExecutive() was passed an invalid society: "
		              . "\"$society\"", E_USER_ERROR);

	$sql = "SELECT * FROM executive WHERE society = '$society';";
	if(!$result = mysql_query($sql))
		trigger_error(mysql_error(), E_USER_ERROR);

	while($member = mysql_fetch_assoc($result))
		$executive[] = $member;

	return $executive;
}
