<?php cx_template_base('base'); ?>

<h2>Posts</h2>
<ul role="list">
<?php foreach (cx_posts_get() as $post): ?>
<li><a href="<?= cx_url_admin('/posts/edit?id=' . $post->id); ?>"><?= $post->title ?></a> <a href="<?= cx_url_admin('/posts/delete?id=' . $post->id); ?>">🚮</a></li>
<?php endforeach; ?>
</ul>

<h2>Pages</h2>
<ul role="list">
<?php foreach (cx_pages_get() as $post): ?>
<li><a href="<?= cx_url_admin('/posts/edit?id=' . $post->id); ?>"><?= $post->title ?></a> <a href="<?= cx_url_admin('/posts/delete?id=' . $post->id); ?>">🚮</a></li>
<?php endforeach; ?>
</ul>

<h2>Images</h2>
<ul role="list">
<?php foreach (cx_images_get() as $image): ?>
<li><a href="<?= cx_url_admin('/images/edit?id=' . $image->id); ?>"><?= $image->url ?></a> <a href="<?= cx_url_admin('/images/delete?id=' . $image->id); ?>">🚮</a></li>
<?php endforeach; ?>
</ul>
