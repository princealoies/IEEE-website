<center>
<font size=+2> Gallery Setup: Step 3 </font>
<table width=80%><tr><td>

Here you can customize the default values for new albums that you
create.  Note that these are not <b>master switches</b> (ie, if you
turn a setting on here, you can still turn it off in a given album).
If this is the first time you've customized Gallery, you'll notice
that we've tried to pick reasonable defaults for you.  If you've
already configured Gallery before, then you'll notice that this page
already has your current config information in it so you probably
won't need to do much.

</td></tr></table>
<p>

<table>
<input type=hidden name=this_page value="defaults">
<input type=hidden name=next_page value="confirm">
<input type=hidden name=back_page value="constants">

<?
$i = 0;
foreach ($defaults as $key => $val) {

	/* Support $app-> for backwards compatibility */
	if ($app->default[$key]) {
		$val["value"] = $app->default[$key];
	}

	/* $gallery->app-> is the desired container, however */
	if ($gallery->app->default[$key]) {
		$val["value"] = $gallery->app->default[$key];
	}

	if (!$go_defaults && isset($$key)) {
		$val["value"] = $$key;
	}

	if (!strcmp($val["type"], "hidden")) {
		list($f1, $f2) = make_fields($key, $val);
		echo $f2;
	} else {
		echo evenOdd_row(make_fields($key, $val),
				$i++ % 2);
		echo "<tr><td colspan=2> &nbsp; </td></tr>";
	}

	$onThisPage[$key] = 1;
	$preserve[$key] = 1;
}
?>
</table>
<p>
<input type=submit name="go_back" value="<- Gallery Settings">
<input type=submit name="go_next" value="Save Settings ->">
<input type=submit name="go_defaults" value="Use Defaults">
