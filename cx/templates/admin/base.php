<?php cx_template_base('skeleton'); ?>

<nav>
<ul role="list">
<li><a href="<?= cx_url_admin('/'); ?>">admin</a></li>
<li><a href="<?= cx_url_admin('/posts/add/'); ?>">add post</a></li>
<li><a href="<?= cx_url_admin('/images/add/'); ?>">add image</a></li>
<li><a href="<?= cx_url_admin('/logout/'); ?>">logout</a></li>
</ul>
</nav>

<main>
<?= cx_template_content(); ?>
</main>
