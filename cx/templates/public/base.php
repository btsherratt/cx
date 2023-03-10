<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title><?= cx_site_name(); ?></title>
	<link rel="stylesheet" href="<?= cx_url('/design/css/style.css') ?>">
	<link rel="alternate" type="application/rss+xml" title="bts.cx" href="<?= cx_url('/feed/') ?>" />
</head>

<body>
<header>
  <h1><?= cx_site_name(); ?></h1>
</header>

<nav>

<ul role="list">
<li><a href="<?= cx_url('/'); ?>">Home</a></li>

<?php foreach (cx_pages_get() as $page): ?>
<li><a href="<?= cx_url($page->url); ?>"><?= $page->title ?></a></li>
<?php endforeach; ?>
</ul>
</nav>

<?= cx_template_content(); ?>

<footer>

<div>
<p>&copy; <?= date('Y'); ?> <?= cx_site_name(); ?> &middot; <?= cx_site_author(); ?></p>
</div>

</footer>

</body>
</html>
