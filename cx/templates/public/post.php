<?php cx_template_base('base'); ?>
<main>
<?php $post = cx_posts_find_post($post_id); ?>
<?php if ($post): ?>
<?php $post_permalink = '/' . date('Y', $post->date) . '/' . date('m', $post->date) . '/' . $post->slug; ?>
	<article>
		<h1><a href="<?= cx_url($post_permalink) ?>"><?= $post->title ?></a></h1>
		<p class="updated"><?= date('l, F jS, Y',$post->date) ?></p>
		<?= $post->html_content ?>
	</article>
<?php else: ?>
	<p>Nothing found.</p>
<?php endif; ?>

</main>
