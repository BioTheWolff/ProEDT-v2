<?php

$this->layout('_template') ?>

<div class="card p-centered" style="max-width: 400px;">
    <div class="card-header">
        <div class="card-title h5">Devoir</div>
        <div class="card-subtitle">Pour supprimer le devoir, laissez le champ vide.</div>
    </div>
    <div class="card-body">
        <form method="post" class="form-group">
            <input type="text" name="uid" hidden required value="<?= $this->e($homework_uid ?? '') ?>">
            <input type="text" name="content" class="form-input" value="<?= $this->e($homework_content ?? '') ?>">
            <input type="submit" class="btn" value="Valider">
        </form>
    </div>
</div>