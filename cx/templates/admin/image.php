<?php cx_template_base('base'); ?>

<form action="<?= cx_url_admin('/images/update?id=' . $image_id); ?>" method="post" enctype="multipart/form-data">

<p>alt-text: <input name="image_alt_text" type="text" value="<?= $image_alt_text ?>"></p>

<?php if ($image_id == 0): ?>
<p>file: <input name="image_file" type="file"></p>
<?php else: ?>
<p></p>
<?php endif; ?>

<p><input type="submit" value="update"></p>
</form>
