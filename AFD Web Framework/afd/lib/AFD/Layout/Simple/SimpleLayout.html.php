<!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<title><?= $title ?></title>
<link href="<?= $favicon ?>" rel="shortcut icon">
<!--[if lt IE 9]>
<?= $this->script('//html5shim.googlecode.com/svn/trunk/html5.js'); ?>
<![endif]-->
<?php foreach ($css as $url): echo $this->css($url); endforeach; ?>
<?php foreach ($scripts as $url): echo $this->script($url); endforeach; ?>
<?= $script_calls?>
</head>
<body>
<!-- 
	<div id="fb-root"></div>
	<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/de_DE/sdk.js#xfbml=1&version=v2.0&status=0";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));
	</script>
-->
	<div id="pagecontainer">
		<header>
		  <?= $header?>
			<h1><?= $title ?></h1>
			<h3><?= $this->esc($subtitle); ?></h3>
		</header>
		<nav><?= $topnavigation ?></nav>
		<table id="content-table">
			<tr>
				<td id="content-column">
				<?= $breadcrumb?>
				<div id="content"><?= $content ?></div>
				</td>
				<td id="rpane-column">
					<div id="rpane"><?= $rightpane ?></div>
				</td>
			</tr>
		</table>
		<footer><?= $footer ?></footer>
	</div>
</body>
</html>