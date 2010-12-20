<?php
ob_start();
?>
<!DOCTYPE html>
<head>
<link href='http://fonts.googleapis.com/css?family=PT+Sans+Narrow' rel='stylesheet' type='text/css'>
<link href='http://fonts.googleapis.com/css?family=Neucha' rel='stylesheet' type='text/css'>
<title>Ac&oacutertame este link</title>
<meta content="text/html; charset=iso-8859-1" http-equiv="Content-Type"/>

<style type="text/css">
<!--
	body {
		font-family:'PT Sans Narrow', sans-serif;
		font-size:0.9em;
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
	}
	form label {
		font-weight:bold;
		padding-right:10px;
	}
	form input {
		border:1px solid #dddddd;
		border-right:2px solid #cccccc;
		border-bottom:2px solid #cccccc;
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
$file = '../../links.dat';

/* 
use mod_rewrite: 0 - no or 1 - yes
*/
$use_rewrite = 1;

/*
language/style/output variables
*/

$l_url			= 'URL';
$l_nourl		= '<strong>&iexcl;Espera! &iexcl;Falta tu URL!</strong>';
$l_yoururl		= '<strong>&iexcl;Excelente! Tu URL corta es:</strong>';
$l_invalidurl	= '<strong>Algo ha salido mal. Quiz&aacute;s a tu URL le falte el "http://".</strong>';
$l_createurl	= '&iexcl;Hazla corta!';

//////////////////// NO EDITAR ////////////////////

if(!is_writable($file) || !is_readable($file))
{
	die('Vea si los permisos están bien o revise la key.');
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
				
				$shorturl = ($use_rewrite == 1) ? "http://i.irfe.cl/{$id}" : "http://i.irfe.cl/{$filename}?id={$id}";
				
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
<div align=center><span><font face="Neucha" size="24px">Ac&oacutertame este <a href="http://i.irfe.cl/prev/v0.3.0~20122010/">link</a></font>
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
<p>0.3 (20122010) - <a href="http://i.irfe.cl">Ir a versi&oacute;n actual</a>
<br />Creado por <a href="http://i.irfe.cl/11">Ignacio Trujillo</a>
<br />2010</p>
<a href="http://twitter.com/share" class="twitter-share-button" data-url="http://i.irfe.cl/" data-count="none" data-via="irfe" data-related="irfe">Tweet</a><script type="text/javascript" src="http://platform.twitter.com/widgets.js"></script>
</span></div>
<!-- html -->

</body>
</html>
<?php
ob_end_flush();
?>