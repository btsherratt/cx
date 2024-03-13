<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">

	<?php if (isset($cx_post_title)): ?>
	<title><?= cx_site_name(); ?> - <?= $cx_post_title; ?></title>
	<?php else: ?>
	<title><?= cx_site_name(); ?></title>
	<?php endif; ?>

	<link rel="stylesheet" href="<?= cx_url('/design/css/style.css') ?>">
	<link rel="alternate" type="application/rss+xml" title="bts.cx" href="<?= cx_url('/feed/') ?>" />

	<?php if (isset($cx_post_meta)): ?>
	
	<meta property="og:title" content="<?= cx_site_name(); ?> - <?= $cx_post_title; ?>">
	<meta property="og:type" content="article" />

	<?php if (isset($cx_post_meta->hero_image)): ?>
	<meta property="og:image" content="<?= $cx_post_meta->hero_image; ?>">
	<?php endif; ?>

	<meta property="og:url" content="<?= cx_url_site($cx_post_permalink); ?>">
	<meta name="twitter:card" content="summary_large_image">

	<?php if (isset($cx_post_meta->hero_image_alt)): ?>
	<meta name="twitter:image:alt" content="<?= $cx_post_meta->hero_image_alt; ?>">
	<?php endif; ?>

	<?php /*<meta property="og:description" content="Offering tour packages for individuals or groups.">
	<meta property="og:site_name" content="<?= cx_site_name(); ?>">
	<meta name="twitter:image:alt" content="Alt text for image">*/ ?>

	<?php endif; ?>
</head>

<body>
<header>
  <h1><a href="<?= cx_url('/'); ?>"><?= cx_site_name(); ?></a></h1>
  <p>Find me <a href="https://mastodon.gamedev.place/@btsherratt" rel="me">@btsherratt</a> or on <a href="https://bts.itch.io/">itch.io</a></p>
</header>

<?php /*<nav>

<ul role="list">
<li><a href="<?= cx_url('/'); ?>">Home</a></li>

<?php foreach (cx_pages_get() as $page): ?>
<li><a href="<?= cx_url($page->url); ?>"><?= $page->title ?></a></li>
<?php endforeach; ?>
</ul>

</nav> */ ?>

<?= cx_template_content(); ?>

<footer>

<div>
<p>&copy; <?= date('Y'); ?> <?= cx_site_name(); ?> &middot; <?= cx_site_author(); ?></p>
</div>

</footer>

</body>
</html>
