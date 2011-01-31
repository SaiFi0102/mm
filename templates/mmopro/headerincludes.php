<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<!-- Title -->
<title><?php print $TITLE; ?></title>
<!-- Meta's -->
<meta name="description" content="<?php print $META_DESCRIPTION; ?>" />
<meta name="keywords" content="<?php print $META_KEYWORDS; ?>" />

<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/> 
<!-- Stylesheets -->
<link rel="stylesheet" type="text/css" href="<?php print $cms->config['websiteurl']; ?>/templates/mmopro/style.css" />
<link rel="stylesheet" type="text/css" href="<?php print $cms->config['websiteurl']; ?>/templates/mmopro/reset.css" />
<link rel="stylesheet" type="text/css" href="<?php print $cms->config['websiteurl']; ?>/templates/mmopro/jquery.lightbox-0.5.css" media="screen" />
<link rel="icon" href="<?php print $cms->config['websiteurl']; ?>/favicon.ico" />
<?php print $META_EXTRA; ?>
<!-- JS -->
<script type="text/javascript" src="<?php print $cms->config['websiteurl']; ?>/javascripts/core.js"></script>
<script type="text/javascript" src="<?php print $cms->config['websiteurl']; ?>/javascripts/ajax.core.js"></script>
<script type="text/javascript" src="<?php print $cms->config['websiteurl']; ?>/javascripts/jquery.cycle.all.min.js"></script>
<script type="text/javascript" src="<?php print $cms->config['websiteurl']; ?>/javascripts/jquery.lightbox-0.5.js"></script>
<script type="text/javascript" src="<?php print $cms->config['websiteurl']; ?>/javascripts/tooltip.core.js"></script>
<script type="text/javascript" src="<?php print $cms->config['websiteurl']; ?>/javascripts/PageSort.core.js"></script>

<!--[if IE 6]>
<link rel="stylesheet" href="<?php print $cms->config['websiteurl']; ?>/templates/mmopro/ie6.css" type="text/css" media="screen" />
<![endif]-->
<script type="text/javascript">$(function(){$('a.l_box').lightBox();});</script>
</head>