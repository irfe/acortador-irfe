<?php
ob_start();
?>
<!DOCTYPE html>
<head>
<link href='http://fonts.googleapis.com/css?family=PT+Sans+Narrow' rel='stylesheet' type='text/css'>
<link href='http://fonts.googleapis.com/css?family=Neucha' rel='stylesheet' type='text/css'>
<title>Acortador de links</title>
<meta content="text/html; charset=iso-utf-8" http-equiv="Content-Type"/>

<style type="text/css">
<!--
	body {
		font-family:'PT Sans Narrow', sans-serif;
		font-size:0.9em;
                -webkit-font-smoothing: antialiased;
	}
	div {
		top: 0; 
		left: 0; 
		width: 100%; 
		height: 100%;
		position: fixed; 
		display: table
	}
	span {
		display: table-cell; 
		vertical-align: middle
	}
	a, a:hover, a:visited {
		color:#d20000;
	}
	form {
		padding:15px;
		margin:0;
		border:1px solid #dddddd;
		width:50%;
                border-top-left-radius: 10px;
                border-bottom-right-radius: 10px;

	}
	form label {
		font-weight:bold;
		padding-right:10px;
	}
	form input {
		border:1px solid #dddddd;
		border-right:1px solid #cccccc;
		border-bottom:1px solid #cccccc;
		padding:4px;
		font-family:'PT Sans Narrow', sans-serif;
		width:70%;
	}
	form input.button {
		background-color:#2d2d2d;
		font-weight:bold;
		font-size:1.2em;
		color:#ffffff;
		border:1px solid #2d2d2d;
		border-right-color:#424242;
		border-bottom-color:#424242;
		font-family:'PT Sans Narrow', sans-serif;
		width:30%;
	}
//-->
</style>



</head>
<body>

<?php
/*
*
*	Ignacio Trujillo
*	Alpha - v.0.3
*	copyright (c) 2010
*	Creative Commons Attribution 3.0
*
*/

/*
el sitio donde se guardan las URLs es:
*/
$file = 'links.dat';

/* 
use mod_rewrite: 0 - no or 1 - yes
*/
$use_rewrite = 1;

/*
language/style/output variables
*/

$l_url			= 'URL';
$l_nourl		= '<strong>&iexcl;Espera! &iexcl;Falta la URL!</strong>';
$l_yoururl		= '<strong>&iexcl;Excelente! La URL corta es:</strong>';
$l_invalidurl	= '<strong>Algo malo ocurre. Quiz&aacute;s a la URL le falte el "http://"</strong>';
$l_createurl	= 'Acortar';

//////////////////// NO EDITAR ////////////////////

if(!is_writable($file) || !is_readable($file))
{
	die('Vea si los permisos est&aacute;n bien o revise la key.');
}

$action = trim($_GET['id']);
$action = (empty($action) || $action == '') ? 'create' : 'redirect';

$valid = "^(https?|ftp)\:\/\/([a-z0-9+!*(),;?&=\$_.-]+(\:[a-z0-9+!*(),;?&=\$_.-]+)?@)?[a-z0-9+\$_-]+(\.[a-z0-9+\$_-]+)*(\:[0-9]{2,5})?(\/([a-z0-9+\$_-]\.?)+)*\/?(\?[a-z+&\$_.-][a-z0-9;:@/&%=+\$_.-]*)?(#[a-z_.-][a-z0-9+\$_.-]*)?\$";

$output = '';

if($action == 'create')
{
	if(isset($_POST['create']))
	{
		$url = trim($_POST['url']);
		
		if($url == '')
		{
			$output = $l_nourl;
		}
		else
		{
			if(eregi($valid, $url))
			{
				$fp = fopen($file, 'a');
				fwrite($fp, "{$url}\r\n");
				fclose($fp);
				
				$id			= count(file($file));
				$dir		= dirname($_SERVER['PHP_SELF']);
				$filename	= explode('/', $_SERVER['PHP_SELF']);
				$filename   = $filename[(count($filename) - 1)];
				
				$shorturl = ($use_rewrite == 1) ? "http://{$_SERVER['HTTP_HOST']}{$dir}{$id}" : "http://{$_SERVER['HTTP_HOST']}{$dir}/{$filename}?id={$id}";
				
				$output = "{$l_yoururl} <a href='{$shorturl}'>{$shorturl}</a>";
			}
			else
			{
				$output = $l_invalidurl;
			}
		}
	}
}

if($action == 'redirect')
{
	$urls = file($file);
	$id   = trim($_GET['id']) - 1;
	if(isset($urls[$id]))
	{
		header("Location: {$urls[$id]}");
		exit;
	}
	else
	{
		die('ERROR');
	}
}

//////////////////// Se puede editar abajo. ////////////////////
?>



<!-- html -->
<div align=center><span><font face="Neucha" size="24px">Acortador de <a href="http://i.irfe.cl">links</a></font>
<br />
<form action="<?=$_SERVER['PHP_SELF']?>" method="post">
<p class="response"><?=$output?></p>
<p>
	<label for="s-url">URL</label><br />
	<input id="s-url" type="text" name="url" value="http://" />

</p>
<p>
	<input type="submit" class="button" name="create" value="<?=$l_createurl?>" />
</p>
</form>
<p>alpha - 0.3.1
<br /><a href="http://ignac.es">Ignacio Trujillo</a>
<br />2010 - 2011</p>
<a href="http://twitter.com/share" class="twitter-share-button" data-url="http://i.irfe.cl/" data-count="none" data-via="irfe" data-related="irfe">Tweet</a><script type="text/javascript" src="http://platform.twitter.com/widgets.js"></script>
</span></div>
<!-- html -->

</body>
</html>
<?php
ob_end_flush();
?>