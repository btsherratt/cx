<?php cx_template_base('base'); ?>

<form action="<?= cx_url_admin('/posts/update?id=' . $post_id); ?>" method="post">

<p>title: <input name="post_title" type="text" value="<?= $post_title ?>"></p>
<p>slug: <input name="post_slug" type="text" value="<?= $post_slug ?>"></p>
<p><textarea name="post_data"  cols="60" rows="40"><?= $post_data ?></textarea></p>

<p><input type="submit" value="update"></p>
</form>
