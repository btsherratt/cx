<?php $post = cx_posts_find_post($post_id); ?>
<?php $post_metadata = $post->get_metadata(); ?>
<?php $post_permalink = '/' . date('Y', $post->date) . '/' . date('m', $post->date) . '/' . $post->slug; ?>
<?php cx_template_base('base', ['cx_post_title' => $post->title, 'cx_post_meta' => $post_metadata, 'cx_post_permalink' => $post_permalink]); ?>
<main>
<?php if ($post): ?>
	<article>
		<h1><a href="<?= cx_url($post_permalink) ?>"><?= $post->title ?></a></h1>
		<p class="updated"><?= date('l, F jS, Y',$post->date) ?></p>
		<?= $post->html_content ?>
	</article>
<?php else: ?>
	<p>Nothing found.</p>
<?php endif; ?>

</main>
