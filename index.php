<?php
ob_start();
?>
<!DOCTYPE html>
<head>
<title>Acortador de links</title>
<script type="text/javascript" src="https://www.google.com/jsapi">
</script>
<script type="text/javascript">
  google.load("webfont", "1");

  google.setOnLoadCallback(function() {
    WebFont.load({
      google: {
        families: [ 'Amatic+SC:700:latin', 'Open+Sans+Condensed:300:latin' ]
      }});
  });
</script>
<meta content="text/html; charset=iso-utf-8" http-equiv="Content-Type"/>

<style type="text/css">
<!--
	body {
		font-family:'Open Sans Condensed', sans-serif;
		font-size:0.9em;
                -webkit-font-smoothing: antialiased;
	}
	div.centro {
		width: 70%; 
                margin-left:auto;
                margin-right:auto;
                text-align:center;
                height:340px;
	}
	span {
		vertical-align: middle
	}
	a, a:hover, a:visited {
		color:#d20000;
	}
	form {
		padding:15px;
		margin:0;
		border:1px solid #dddddd;
                border-top-left-radius: 10px;
                border-bottom-right-radius: 10px;
                box-shadow: rgba(200,200,200,0.7) 0 4px 10px -1px;
	}
	form label {
		font-weight:bold;
		padding-right:10px;
	}
	form input {
		border:1px solid #dddddd;
		padding:8px;
		font-family:'Open Sans Condensed', sans-serif;
		width:80%;
                box-shadow: inset 3px 3px 5px rgba(200,200,200,0.2);
                font-size:1.5em;

	}
	form input.button {
                width:6em;
	        font-size: 1.3em;
                color: rgb(5, 5, 5);
                padding: 10px 20px;
                background: -moz-linear-gradient( top, white 0%, whiteSmoke);
                background: -webkit-gradient( linear, left top, left bottom, from(white), to(whiteSmoke));
                border-radius: 14px;
                -moz-border-radius: 14px;
                border: 1px solid rgb(196, 196, 196);
                text-shadow: 0px -1px 0px rgba(000, 000, 000, 0.2), 0px 1px 0px rgba(255, 255, 255, 0.4);
                text-transform: uppercase;
	}
       #titulo {
                font-family: 'Amatic SC', cursive;
                font-size: 4.2em;
                margin-bottom: -.3em;
                text-shadow: 0.5px 0.1px 29px ghostWhite;
                text-transform: uppercase;
        }
       p.response {
                font-size: 1.3em;
        }
        p.response a {
                font-weight:bold;
                font-size: 1.5em;
        }

//-->
</style>



</head>
<body>

<?php
/*
*
*	Ignacio Trujillo
*	Alpha - v.0.3.2
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
<div class="centro"><span><p id="titulo">Acortador de <a style="text-decoration: none;" href="http://i.irfe.cl">links</a></p>
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