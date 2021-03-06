<?
/*
 * Gallery - a web based photo album viewer and editor
 * Copyright (C) 2000-2002 Bharat Mediratta
 * 
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or (at
 * your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful, but
 * WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 * General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
 */
?>
<?
// Hack prevention.
if (!empty($HTTP_GET_VARS["GALLERY_BASEDIR"]) ||
		!empty($HTTP_POST_VARS["GALLERY_BASEDIR"]) ||
		!empty($HTTP_COOKIE_VARS["GALLERY_BASEDIR"])) {
	print "Security violation\n";
	exit;
}
?>
<? require($GALLERY_BASEDIR . "init.php"); ?>
<?
if (!$gallery->user->isAdmin()) {
	exit;	
}

if ($submit) {
	if (!strcmp($submit, "Create")) {
		$gErrors["uname"] = $gallery->userDB->validNewUserName($uname);
		if ($gErrors["uname"]) {
			$errorCount++;
		}

		if (strcmp($new_password1, $new_password2)) {
			$gErrors["new_password2"] = "Passwords do not match!";
			$errorCount++;
		} else {
			$gErrors["new_password1"] = 
				$gallery->userDB->validPassword($new_password1);
			if ($gErrors["new_password1"]) {
				$errorCount++;
			}
		}

		if (!$errorCount) {
			$tmpUser = new Gallery_User();
			$tmpUser->setUsername($uname);
			$tmpUser->setPassword($new_password1);
			$tmpUser->setFullname($fullname);
			$tmpUser->setCanCreateAlbums($canCreate);
			$tmpUser->setEmail($email);
			$tmpUser->save();
			header("Location: manage_users.php");
		}
	} else if (!strcmp($submit, "Cancel")) {
		header("Location: manage_users.php");
	}
}

$canCreateChoices = array(1 => "yes", 0 => "no");
$canCreate = 1;

?>
<html>
<head>
  <title>Create User</title>
  <?= getStyleSheetLink() ?>
</head>
<body>

<center>
<span class="popuphead">Create User</span>
<br>
<br>
Create a new user here.
<p>


<?= makeFormIntro("create_user.php", 
			array("name" => "usercreate_form", 
				"method" => "POST")); ?>
<p>

<? include($GALLERY_BASEDIR . "html/userData.inc"); ?>
<p>

<input type=submit name="submit" value="Create">
<input type=submit name="submit" value="Cancel">
</form>

<script language="javascript1.2">
<!--
// position cursor in top form field
document.usercreate_form.uname.focus();
//--> 
</script>

</body>
</html>
