<?php
require_once('php-bin/Activity.php');
require_once('php-bin/mysql.php');

include_once('header.php');
?>

<h1>Activities</h1>

<table>
<?php foreach(getActivities() as $activity) echo $activity->toTableRow(); ?>
</table>

<?php include_once('footer.php'); ?>
