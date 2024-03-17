<?php cx_template_base('base'); ?>
<main>
<?php $posts_per_page = cx_site_posts_per_page(); ?>
<?php foreach (cx_posts_get(limit: $posts_per_page, offset: $page_number * cx_site_posts_per_page()) as $post): ?>
<?php $post_permalink = '/' . date('Y', $post->date) . '/' . date('m', $post->date) . '/' . $post->slug; ?>
	<article>
		<h1><a href="<?= cx_url($post_permalink) ?>"><?= $post->title ?></a></h1>
		<p class="updated"><?= date('l, F jS, Y',$post->date) ?></p>
		<?php if ($post->html_excerpt): ?>
		<?= $post->html_excerpt ?>
		<p><a href="<?= cx_url($post_permalink) ?>">Read more...</a></p>
		<?php else: ?>
		<?= $post->html_content ?>
		<?php endif; ?>
	</article>
<?php endforeach; ?>

	<p><a href="<?= cx_url('/page/' . ($page_number + 1) . '/') ?>">Next Posts</a></p>
</main>
