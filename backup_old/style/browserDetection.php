<?php

function msie()
{
	return preg_match("/MSIE/", $_SERVER['HTTP_USER_AGENT']);
}

?>
