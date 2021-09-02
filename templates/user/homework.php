<?php

$this->layout('_template') ?>

<form method="post">
    <label>
        UID
        <input type="text" name="uid" disabled required value="<?= $this->e($homework_uid ?? '') ?>">
    </label>
    <label>
        Devoirs
        <input type="text" name="content" required value="<?= $this->e($homework_content ?? '') ?>">
    </label>
</form>
