<?php
ob_start();
?>
<!DOCTYPE html><head><title>IRFE.cl | Acortador de links</title><meta name="viewport"content="width=device-width, initial-scale=1, maximum-scale=1" /><meta name="apple-mobile-web-app-capable"content="yes" /><script type="text/javascript"src="https://www.google.com/jsapi"></script><script type="text/javascript">google.load("webfont", "1");google.setOnLoadCallback(function() {WebFont.load({ google: {families: [ 'Amatic+SC:700:latin', 'Open+Sans+Condensed:300:latin' ] }}); });</script><meta charset=utf-8 /><style type="text/css"><!-- body{font-family:'Open Sans Condensed';font-size:0.9em;-webkit-font-smoothing:antialiased;}div.centro{margin-top:136px;width:85%;margin-left:auto;margin-right:auto;text-align:center;height:340px;}span{vertical-align:middle}a, a:hover, a:visited {color:#d20000;}form{padding:15px;margin:0;border:1px solid #dddddd;border-top-left-radius:10px;border-bottom-right-radius:10px;box-shadow:rgba(200,200,200,0.7) 0 4px 10px -1px;}form label{letter-spacing:2px;text-transform: uppercase;}form input{margin-top:12px;border:1px solid #dddddd;padding:8px;font-family:'Open Sans Condensed';width:80%;box-shadow:inset 3px 3px 5px rgba(200,200,200,0.2);font-size:1.5em;border-radius: 0.2em;}form input.button{width:6em;font-size:1em;color:rgb(5, 5, 5);background:-moz-linear-gradient( top, white 0%, whiteSmoke);background:-webkit-gradient( linear, left top, left bottom, from(white), to(whiteSmoke));border-radius:.5em;-moz-border-radius:14px;border:1px solid rgb(196, 196, 196);text-shadow: 0px -1px 0px rgba(000, 000, 000, 0.2), 0px 1px 0px rgba(255, 255, 255, 0.4);text-transform:uppercase;text-align:center;height:2em;line-height:90%;}#titulo{font-family:'Amatic SC';font-size:6em;margin-bottom:-.3em;text-shadow: 0.5px 0.1px 29px ghostWhite;text-transform:uppercase;}p.response{font-size:1.3em;}p.response a{font-weight:bold;font-size: 1.5em;} footer {margin:25px 0 0 0;} footer p{line-height:40%;}#titulo a{text-decoration:none;}@media only screen and (max-width: 767px) {div.centro {margin-top: 0px;} #titulo {font-size:2.5em;} } //--></style></head><body><?php
/*
*
*	Ignacio Trujillo
*	Alpha - v.0.5
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
$l_nourl		= '<strong>&iexcl;Falta la direcci&oacute;n!</strong>';
$l_yoururl		= '<strong>&iexcl;&Eacute;xito! La direcci&oacute;n corta es:</strong>';
$l_invalidurl	= '<strong>&iexcl;Alto ah&iacute;! A la direcci&oacute;n le falta el prefijo "http://"</strong>';
$l_createurl	= 'Acortar link';
$titulo               = 'Acortador de links';
$ingresourl        = '&iquest;Qu&eacute; direcci&oacute;n deseas acortar&#63;';
$primerano        = '2010';
$ultimoano        = '2013';
$version            = '0.6.2';
$fechaversion    = '22122011';
$linkantiguo      = 'prev/v0.3.0~20122010/';

//////////////////// NO EDITAR ////////////////////

if(!is_writable($file) || !is_readable($file))
{
	die('Ignacio: ve si los permisos est&aacute;n bien o revisa la key.');
}

$action = trim($_GET['id']);
$action = (empty($action) || $action == '') ? 'create' : 'redirect';

/*
$valid = "^(https?|ftp)\:\/\/([a-z0-9+!*(),;?&=\$_.-]+(\:[a-z0-9+!*(),;?&=\$_.-]+)?@)?[a-z0-9+\$_-]+(\.[a-z0-9+\$_-]+)*(\:[0-9]{2,5})?(\/([a-z0-9+\$_-]\.?)+)*\/?(\?[a-z+&\$_.-][a-z0-9;:@/&%=+\$_.-]*)?(#[a-z_.-][a-z0-9+\$_.-]*)?\$";
*/

$valid = "^(https?|ftp)\:\/\/";
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

				$target = '_blank';
				$output = "{$l_yoururl} <a href='{$shorturl}' target='{$target}'>{$shorturl}</a>";
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
		die('<script type=text/javascript >
window.location="http://i.irfe.cl";
</script>
</body>');
	}
}

//////////////////// Se puede editar abajo. ////////////////////
?><div class="centro"><span><p id="titulo"><a href="http://i.irfe.cl"><? echo "$titulo"; ?></a></p><br /><form action="<?=$_SERVER['PHP_SELF']?>" method="post"><p class="response"><?=$output?></p><p><label for="s-url"><?php echo "$ingresourl"; ?></label><br /><input id="s-url"type="text"name="url"placeholder="http://" /></p><p><input type="submit"class="button"name="create"value="<?=$l_createurl?>" /></p></form><footer><p><?php echo "v $version ($fechaversion)"; ?> - <a href=<?php echo "$linkantiguo";?> target="_blank" >Ir a versi&oacute;n anterior</a></p><p>Creado por Ignacio Trujillo. <?php echo "$primerano - $ultimoano"; ?></footer><a href="http://twitter.com/share"class="twitter-share-button"data-url="http://i.irfe.cl/"data-count="none"data-via="ignaces"data-related="irfe">Tweet</a><script type="text/javascript"src="http://platform.twitter.com/widgets.js"></script></span></div></body></html><?php
ob_end_flush();
?>