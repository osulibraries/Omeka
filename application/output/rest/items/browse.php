<?php
/* This is really dumb;
// the short <?= php syntax interferes with the xml header!! */
   echo '<?xml version="1.0" encoding="UTF-8"?>'
?>

<items>
<?php foreach ($items as $item): ?>


<item title="<?php echo $item->title; ?>">
	
	
	<tags>
	<?php foreach ($item->Tags as $tag): ?>
		<tag name="<?php echo $tag->Name; ?>"></tag>
	<?php
	endforeach;
	?>
	</tags>
	
	<files>
	<?php foreach ($item->Files as $file): ?>
		<file title="<?php echo $file->Title; ?>"></file>
	<?php
	endforeach;
	?>
	</files>
	
	<collection>
		<?php echo $item->Collection->name; ?>
	</collection>
	
	<type>
		<?php echo $item->Type->name; ?>
	</type>
	
</item>
<?php 
endforeach; 
?>

</items>
