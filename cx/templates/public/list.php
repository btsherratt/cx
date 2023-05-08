<?php cx_template_base('base'); ?>
<main>
<?php foreach (cx_site_get_posts($site_id) as $site_post): ?>
<?php $post_permalink = '/' . date('Y', $site_post->date) . '/' . date('m', $site_post->date) . '/' . $site_post->post->slug; ?>
	<article>
		<h1><a href="<?= cx_url($post_permalink) ?>"><?= $site_post->post->title ?></a></h1>
		<p class="updated"><?= date('l, F jS, Y',$site_post->date) ?></p>
		<?php if ($site_post->post->html_excerpt): ?>
		<?= $site_post->post->html_excerpt ?>
		<p><a href="<?= cx_url($post_permalink) ?>">Read more...</a></p>
		<?php else: ?>
		<?= $site_post->post->html_content ?>
		<?php endif; ?>
	</article>
<?php endforeach; ?>
</main>
