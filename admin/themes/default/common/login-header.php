<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title><?php echo settings('site_title'); ?></title>

<!-- Meta -->
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<!-- Stylesheets -->
<link rel="stylesheet" media="screen" href="<?php echo css('screen'); ?>" />
<link rel="stylesheet" media="screen" href="<?php echo css('login'); ?>" />
<link rel="stylesheet" media="print" href="<?php echo css('print'); ?>" />

<!-- Plugin Stuff -->
<?php admin_plugin_header(); ?>

</head>
<body id="login">
	<div id="wrap">
		<div id="header">
			<div id="site-title"><a href="<?php echo uri(''); ?>"><?php echo settings('site_title'); ?></a></div>
		</div>
		<div id="content">