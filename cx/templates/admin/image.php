<?php cx_template_base('base'); ?>

<form action="<?= cx_url_admin('/images/update?id=' . $image_id); ?>" method="post" enctype="multipart/form-data">

<p>title: <input name="post_title" type="text" value="<?= $post_title ?>"></p>
<p>slug: <input name="post_slug" type="text" value="<?= $post_slug ?>"></p>

<?php if (isset($image_id) == false): ?>
<p>file: <input name="image_file" type="file"></p>
<?php else: ?>
<p></p>
<?php endif; ?>

<p><input type="submit" value="update"></p>
</form>
