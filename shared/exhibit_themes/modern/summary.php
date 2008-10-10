<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title><?php echo settings('site_title'); ?></title>

<!-- Meta -->
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<!-- Stylesheets -->
<link rel="stylesheet" media="screen" href="<?php echo exhibit_css('screen'); ?>" />
<link rel="stylesheet" media="screen" href="<?php echo layout_css('layout'); ?>" />
<link rel="stylesheet" media="print" href="<?php echo css('print'); ?>" />

<!-- JavaScripts -->
<?php echo js('prototype'); ?>

<!-- Plugin Stuff -->
<?php plugin_header(); ?>

</head>
<body id="<?php echo h($exhibit->theme); ?>">
	<div id="wrap">
	<h5><a href="<?php echo uri('exhibits'); ?>">Back to Exhibits</a></h5>
		
		<div id="content">
		<?php echo flash(); ?>				
		<h1><?php echo link_to_exhibit($exhibit); ?></h1>

		<p><?php echo h($exhibit->description); ?></p>

		<div id="exhibit-sections">	
			<div>
			<?php foreach($exhibit->Sections as $section) {
			$uri = exhibit_uri($exhibit, $section);
			echo '<a href="' . $uri . '"' . (is_current($uri) ? ' class="current"' : ''). '><span class="section-title">' . h($section->title) . '</span>' . '<span class="section-desc">' . h($section->description) . '</span></a>';
			} ?>
			</div>
		</div>

		<div id="exhibit-credits">	
			<h3>Credits</h3>
			<ul><li><?php echo h($exhibit->credits); ?></li></ul>
		</div>

<?php exhibit_foot(); ?>