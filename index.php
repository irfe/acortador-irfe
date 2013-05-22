<?php
ob_start("ob_gzhandler");
header('Content-Language: es');
$domain = "http://i.irfe.cl";
$title = 'Acortador de links';
$postitle = 'IRFE.cl';
$descript = 'El acortador de links del Instituto Regional Federico Errázuriz';
$fulltitle = $title.' | '.$postitle;
$version = '0.7.2';
$version_date = '21052013';
$version_int = $version.'.'.$version_date;
?>
<!doctype html>
<html lang="es">
	<head>
		<title><?php echo $fulltitle;?></title>
		<meta charset="utf-8"/>
		<link rel="stylesheet" href="assets/style.css" media="screen" type="text/css"/>
		<link rel="canonical" href="<?php echo $domain;?>"/>
		<link rel="shortcut icon" href="assets/images/favicon.png"/>
		<link rel="apple-touch-icon-precomposed" href="assets/images/apple-icon.png"/>
		<meta name="description" content="<?php echo $descript;?>"/>
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0"/>
		<meta name="format-detection" content="telephone=no"/>
		<meta name="robots" content="all"/>
		<meta name="google" content="notranslate"/>
		<meta property="og:image" content="<?php echo $domain.'/assets/images/og.png';?>" />
	</head>
	<body><?php
/*
*
*	Ignacio Trujillo
*	Alpha - v.1
*	copyright (c) 2010
*	Creative Commons Attribution 3.0
*
*/
$file = 'links.dat';

// use mod_rewrite: 0 - no or 1 - yes
$use_rewrite = 1;

// language/style/output variables

function random_data($one,$two,$three,$four,$five,$six) {
	$results = array($one,$two,$three,$four,$five,$six);
	return $results[rand(0, count($results) - 1)];
}

$l_url = 'URL';
$no_url = random_data('&iexcl;Falta el link!','No te preocupes, a todos nos puede pasar. &iquest;Y si lo intentas otra vez?','Creo que olvidaste un peque&ntilde;o detalle.','No s&eacute; qu&eacute; hacer... ERROR ERROR ERROR.','Estoy confundido, &iquest;d&oacute;nde est&aacute; el link?','Ups, creo que falta algo aqu&iacute;.');
$url_success = random_data('OK!','&iexcl;&Eacute;xito!','&iexcl;Buen viaje!','&iexcl;Todo bien!','&iexcl;As&iacute; se hace!','&iexcl;Result&oacute;!') .' El link corto es:';
$stop = random_data('&iexcl;Detente!','&iexcl;Alto ah&iacute;!','Algo malo pas&oacute;.','Ups.','Whoooops.','STOP.');
$url_invalid = $stop .' Hay un error en el link que ingresaste y no puedo procesarlo.';
$url_invalid2 = $stop .' No me ense&ntilde;aron a acortarme a m&iacute; mismo.';
$l_createurl = 'Acortar';
$input_url_text = '&iquest;Qu&eacute; link deseas acortar&#63;';
$first_year = '2010';
$this_year = date("Y");

if(!is_writable($file) || !is_readable($file)) die('Revisa la key.');

$action = trim($_GET['id']);
$action = (empty($action) || $action == '') ? 'create' : 'redirect';

$output = '';

if($action == 'create') {
	if(isset($_POST['create'])) {
		$url = trim($_POST['url']);
		if ($url == '') $output = $no_url;
		else {
			if (preg_match("#https?://#", $url) === 0) $url = 'http://'.$url;
			// if ( (filter_var($url, FILTER_VALIDATE_URL) == true) and (strpos($url,'.') == true) ) {
			if (preg_match("/(([\w-]+:\/\/?|[\w\d]+[.])?[^\s()<>]+[.](?:\([\w\d]+\)|([^`!()\[\]{};:'\".,<>?«»“”‘’\s]|\/)+))/", $url) ) {
				if (preg_match('/(?:f|ht)tps?:\/\/i\.irfe\.cl\/\d+$/i', $url) ) {
					$output = $url_invalid2;
				}
				else {
					$fp = fopen($file, 'a');
					fwrite($fp, "{$url}\r\n");
					fclose($fp);
					$id	= count(file($file));
					$dir = dirname($_SERVER['PHP_SELF']);
					$filename = explode('/', $_SERVER['PHP_SELF']);
					$filename = $filename[(count($filename) - 1)];

					$shorturl = ($use_rewrite == 1) ? "http://{$_SERVER['HTTP_HOST']}{$dir}{$id}" : "http://{$_SERVER['HTTP_HOST']}{$dir}/{$filename}?id={$id}";

					$output = $url_success .' <a href="'.$shorturl.'" target="_blank">'.$shorturl.'</a>';
				}
			}
			else $output = $url_invalid;
		}
	}
}

if($action == 'redirect')
{
	$urls = file($file);
	$id   = trim($_GET['id']) - 1;
	if(isset($urls[$id]))
	{
		header ('HTTP/1.1 301 Moved Permanently');
		header("Location: {$urls[$id]}");
		header("X-From: {$domain}");
		exit;
	}
	else {
		header("Location: {$domain}");
		exit;
	}
}

function hide_email($email,$name) {
	$character_set = '+-.0123456789@ABCDEFGHIJKLMNOPQRSTUVWXYZ_abcdefghijklmnopqrstuvwxyz';
	$character_set2 = ' &#;0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ_abcdefghijklmnopqrstuvwxyz';
	$key = str_shuffle($character_set);
	$key2 = str_shuffle($character_set2);
	$cipher_text = '';
	$cipher_name = '';
	$id = 'e'.rand(1,999999999);
	for ($i=0;$i<strlen($email);$i+=1) $cipher_text.= $key[strpos($character_set,$email[$i])];
	for ($z=0;$z<strlen($name);$z+=1) $cipher_name.= $key2[strpos($character_set2,$name[$z])];
	$script = 'var a="'.$key.'";var b=a.split("").sort().join("");var c="'.$cipher_text.'";var d="";var f="'.$key2.'";var g=f.split("").sort().join("");var h="'.$cipher_name.'";var j="";';
	$script.= 'for(var e=0;e<c.length;e++)d+=b.charAt(a.indexOf(c.charAt(e)));for(var k=0;k<h.length;k++)j+=g.charAt(f.indexOf(h.charAt(k)));';
	$script.= 'document.getElementById("'.$id.'").innerHTML="<a href=\\"mailto:"+d+"\\">"+j+"</a>"';
	$script = "eval(\"".str_replace(array("\\",'"'),array("\\\\",'\"'), $script)."\")";
	$script = '<script type="text/javascript">/*<![CDATA[*/'.$script.'/*]]>*/</script>';
	return '<span id="'.$id.'"></span>'.$script;
}

?>
		
		<div class="main">
			<header>
				<h3><a href="<?php echo $domain; ?>"><?php echo $title;?></a></h3>
			</header>
			<form action="/" method="post">
				<?php if ($output != '') echo '<p class="response">'.$output.'</p>
				';?><label for="theurl"><?php echo $input_url_text;?></label>
				<input id="theurl" type="text" name="url" placeholder="http://" />
				<input type="submit" class="button" name="create" value="<?php echo $l_createurl; ?>" />
			</form>
			<footer>
				<p><?php echo $first_year.' - ' .$this_year.', v'. $version.' <small>('.$version_date.')</small>'; ?></p>
				<p class="footer-text"><small>por</small> <?php echo hide_email('ignaciotrujillo@irfe.cl','Ignacio Trujillo'); ?></p>
				<p><a href="https://twitter.com/share" class="twitter-share-button" data-url="<?php if ($shorturl!='') echo $shorturl; else echo $domain;?>" data-text="<?php if ($shorturl!='') echo '&nbsp;'; else echo $title; ?>" data-via="irfe" data-lang="es" data-related="irfe" data-count="none">Twittear</a> <a href="https://twitter.com/irfe" class="twitter-follow-button" data-show-count="false" data-lang="es">Seguir a @irfe</a><script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script></p>	
			</footer>
		</div>
		<script type="text/javascript">
		var _gaq = _gaq || [];
		_gaq.push(['_setAccount', 'UA-20357096-1']);
		_gaq.push(['_trackPageview']);
		(function() {
			var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
			ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
			var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
		})();
		</script>
	</body>
</html><?php ob_end_flush();?>