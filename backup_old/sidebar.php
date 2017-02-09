<?php
require_once('php-bin/security.php');
require_once('pages.php');

function sidebar($filename)
{
	$str .= "<div id=\"sidebar\">\n";
	$str .= linkList(pages(), "\t");
	$str .= '<ul><li class="sidebar">' . loginLink() . "</li></ul>\n";
	$str .= "</div>\n";
	return $str;
}

function linkList($links, $pad = '')
{
	$str .= "$pad<ul>\n";
	foreach($links as $link)
	{
		$str .= "$pad\t<li class=\"sidebar\">";
		if($link['href'] != $filename)
			$str .= '<a href="' . $link['href']
			      . '" class="sidebar" title="' . $link['descrip']
			      . '">';

			$str .= $link['name'];

			if($link['href'] != $filename) $str .= "</a>";

			if($link['children'])
				$str .= linkList($link['children']);

		$str .= "</li>\n";
	}
	$str .= "$pad</ul>\n";

	return $str;
}

?>
