<?php
require_once('php-bin/mysql.php');
include('header.php');
?>

<h1>Executive</h1>
<p>
Due to the nature of Memorial's co-op program, we have two Societies within the
student branch, one of which will be in school during any given semester.
</p>

<p>
<a href="http://www.engr.mun.ca/people/masek/" target="_blank">Dr. Vlastimil
Masek</a> is the Student Branch counsellor for the entire Branch.
</p>

<?php
// build up a list (hash map) for each executive
$execA = getExecutive('A');
$execB = getExecutive('B');

$showA = (count($execA) > 0);
$showB = (count($execB) > 0);

// now display them
echo "<center>\n"
     . "\t<table class=\"executive\">\n"
     . "\t\t<tr>\n"
     . ($showA ? "\t\t\t<td colspan=2><h2>Society A</h2></td>\n" : "")
     . ($showB ? "\t\t\t<td colspan=2><h2>Society B</h2></td>\n" : "")
     . "\t\t</tr>\n";

$rows = max(count($execA), count($execB));

for($i = 0; $i < $rows; $i++)
{
	$memberA = $execA[$i];
	$memberB = $execB[$i];

	$evenRow = !$evenRow;

	echo "\t\t<tr" . ($evenRow ? ' class="evenRow"' : '') . ">\n"
	     . ($showA ? "\t\t\t<td>" . $memberA['title'] . "</td>\n"
	     . "\t\t\t<td>"

	     // name (with link to homepage, if appropriate)
	       . ($memberA['homepage']
	         ? ('<a href="' . $memberA['homepage'] . '" target="_blank">'
	            . $memberA['name'] . '</a>')
	         : $memberA['name'])
	       . "<br>(" . $memberA['term'] . ")</td>"
	
	     . ($showB ? "\t\t\t<td>" . $memberB['title'] . "</td>\n"
	     . "\t\t\t<td>" : "")

	     // name (with link to homepage, if appropriate)
	       . ($memberB['homepage']
	         ? ('<a href="' . $memberB['homepage'] . 'target="_blank">'
	            . $memberB['name'] . '</a>')
	         : $memberB['name'])
	     . "</td>\n" : "")
	     ;
}

echo "\t</table>\n"
     . "</center>\n";

include_once('footer.php');
?>
