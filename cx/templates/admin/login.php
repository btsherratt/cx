<?php cx_template_base('skeleton'); ?>
<form id="login" action="<?= cx_url_admin('/login/'); ?>" method="post">
<p>username: <input name="id" type="text"></p>
<p>password: <input name="password" type="password"></p>

<p><input type="submit"></p>
</form>
