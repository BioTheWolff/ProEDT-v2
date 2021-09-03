<?php

$this->layout('_template', ['page_title' => "Se connecter"]);
?>

<div class="toast">
    Cette page est nécessaire seulement pour les étudiants ayant la permission de gerer les devoirs et informations importantes.
</div>
<form method="post" class="form-group">
    <label class="form-label">
        Username
        <input class="form-input" name="username" type="text" required>
    </label>

    <label class="form-label">
        Password
        <input class="form-input" name="password" type="password" required>
    </label>

    <input type="submit" class="btn" value="Login">
</form>