<?= '<?xml version="1.0" encoding="utf-8"?>'."\n" /* So some installs of PHP don't get confused... */ ?>
<feed xmlns="http://www.w3.org/2005/Atom">
	<id><?= cx_url_site('/'); ?></id>

	<title><?= cx_site_name(); ?></title>
	<subtitle><?= cx_site_byline(); ?></subtitle>

	<?php foreach (cx_posts_get(limit: 1) as $post): ?>
	<updated><?= date(DATE_RFC3339, $post->date) ?></updated>
	<?php endforeach; ?>

	<link href="<?= cx_url_site('/feed/'); ?>" rel="self" type="application/atom+xml" />
	<link href="<?= cx_url('/'); ?>" rel="alternate" type="text/html" />

	<author>
		<name><?= cx_site_author() ?></name>
	</author>

<?php foreach (cx_posts_get() as $post): ?>
<?php $post_permalink = '/' . date('Y', $post->date) . '/' . date('m', $post->date) . '/' . $post->slug; ?>
	<entry>
		<id><?= cx_url_site($post_permalink); ?></id>
		<title><?= $post->title ?></title>
		<updated><?= date(DATE_RFC3339, $post->date) ?></updated>
		<link href="<?= cx_url($post_permalink) ?>" rel="alternate" type="text/html" />
		<content type="xhtml">
			<div xmlns="http://www.w3.org/1999/xhtml">
				<?= $post->html_content ?>
			</div>
		</content>
	</entry>
<?php endforeach; ?>

</feed>