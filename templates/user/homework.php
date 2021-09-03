<?php

$this->layout('_template') ?>

<form method="post">
    <input type="text" name="uid" hidden required value="<?= $this->e($homework_uid ?? '') ?>">
    <label>
        Devoirs
        <input type="text" name="content" value="<?= $this->e($homework_content ?? '') ?>">
    </label>
    <input type="submit">
</form>
