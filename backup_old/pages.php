<?php

function pages()
{
	return
		array(
			array(
				'name' => 'About Us',
				'descrip' => 'About MUN\'s IEEE Student Branch',
				'href' => 'about.php',
				'children' => array(
					array(
						'name' => 'Activities',
						'descrip' => 'Things we do during the semester',
						'href' => 'activities.php'
						),
					array(
						'name' => 'Executive',
						'descrip' => 'The leadership of our student branch societies',
						'href' => 'executive.php'
						)
					)
				),
			array('name' => 'Links', 'href' => 'links.php'),
			array('name' => 'McNaughton Centre', 'href' => 'mcnaughton.php')
		);
}

?>
