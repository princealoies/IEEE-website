<?php include('header.php'); ?>

<h1>Links</h1>
<ul>
	<li>
	<?php printLink('The Institute of Electrical and Electronic Engineers',
	                'www.ieee.org'); ?>
		<ul>
			<li>
			<?php printLink('IEEE Canada', 'www.ieee.ca'); ?>
				<ul>
					<li>
					<?php printLink('IEEE Canadian Foundation',
					                'www.ieeecanadianfoundation.org'); ?>
					</li>
					<li>
					<?php printLink('Newfoundland & Labrador Section',
					                'www.ieee.nfld.net'); ?>
					</li>
					<li>
					<?php printLink('Student Activities',
					                'ewh.ieee.org/reg/7/students/index.html');
					?>
					</li>
				</ul>
			</li>
		</ul>
	</li>
</ul>

<?php include('footer.php'); ?>

<?php
function printLink($title, $href)
{
	echo "<a href=\"http://$href\" target=\"_blank\">$title</a>";
}
