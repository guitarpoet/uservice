<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
	<title>{$title}</title>
	<link rel="stylesheet" href="{$smarty.const.ROOT}/cache/style.css" />
	<script type="text/javascript" src="{$smarty.const.ROOT}/js/jquery-1.4.2.js"></script>
	<script type="text/javascript" src="{$smarty.const.ROOT}/js/jquery-ui-1.8.custom.min.js"></script>
	<script type="text/javascript">
		initialize = function($){}
		// Initialize for every page
		jQuery(function($){
			initialize($);
			$('#message').dialog({
				autoOpen: false,
				show: "blind",
				hide: "explode",
				modal: true
			});
			$('.button').button();
		});

		function showMessage(message, title, refreshAtClose) {
			jQuery('#message h2').text(message);
			jQuery('#message').dialog('option', 'title', title);
			if(refreshAtClose) {
				jQuery('#message').bind('dialogclose', function(){ window.location = window.location});
			}
			jQuery('#message').dialog('open');
		}
	</script>
</head>
<body>
	<div id="wrapper">
{include file="left.tpl"}
		<div id="message"><h2/></div>
		<div id="main">
