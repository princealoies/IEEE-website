<?php
require_once('php-bin/security.php');

class Activity
{
	function Activity($sqlResult)
	{
		$this->id = $sqlResult['id'];
		$this->myType = $sqlResult['type'];
		$this->myTypeString = $sqlResult['typeString'];
		$this->myTypeDetails = $sqlResult['typeDetails'];
		$this->myTitle = $sqlResult['title'];
		$this->myDate = $sqlResult['date'];
		$this->myLocation = $sqlResult['location'];
		$this->myDetails = $sqlResult['details'];
		$this->execOnly = $sqlResult['execOnly'];
	}

	function toHTML()
	{
		if($this->execOnly and !execLogin())
			return "<h1>Executive Only</h1>\n"
			       . "<p>\n"
			       . "Sorry, you have to be an executive member "
			       . "to view this page. Did you forget to "
			       . "<a href=\"/login.php\">log in</a>?\n"
			       . "</p>";

		$str .= "<h1 class=minutes>" . $this->myTypeString . "</h1>\n";
		$str .= "<h2 class=minutes>" . $this->myTitle . "</h2>\n\n";

		$str .= '<p class="date">'
		      . $this->dateString() . "<br>\n"
		      . $this->timeString() . " - " . $this->myLocation
		      . "</p>\n";

		$str .= "<p>\n" . $this->details() . "\n</p>\n\n";

		return $str;
	}

	function toTableRow()
	{
		$str .= "<tr>"
		      . "<td><b>";

		if($this->myDetails)
			$str .= '<a href="/activity.php?id=' . $this->id . '">';

		$str .= $this->myTypeString;

		if($this->myDetails) $str .= '</a>';

		$str .= "</b></td>"
		      . "<td>" . $this->myTitle . "</td>"
		      . "<td>" . $this->dateTimeString() . "</td>"
		      . "<td>" . $this->myLocation . "</td>"
		      . "</tr>";

		return $str;
	}

	function details()
	{
		$magic = array('/\$date/' => $this->dateString(),
		               '/\$time/' => $this->timeString(),
		               '/\$dateTime/' => $this->dateTimeString(),
			       '/\$location/' => $this->myLocation
		              );

		$details = $this->myDetails;
		foreach($magic as $pattern => $replace)
			$details = preg_replace($pattern, $replace, $details);

		return $details;
	}

	function dateTimeString()
	{
		if(!$this->myDate) return;

		$timestamp = strToTime($this->myDate);
		return date("M d @ Hi\h", $timestamp);
	}

	function dateString()
	{
		if(!$this->myDate) return;

		$timestamp = strToTime($this->myDate);
		return date("F j, Y", $timestamp);
	}

	function timeString()
	{
		if(!$this->myDate) return;

		$timestamp = strToTime($this->myDate);
		return date("Hi\h", $timestamp);
	}

	var $id;
	var $myType;
	var $myTitle;
	var $myDate;
	var $myLocation;
	var $myDetails;
}

?>
