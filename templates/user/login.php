<?php

$this->layout('_template', ['page_title' => "Se connecter"]);
?>

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