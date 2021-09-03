<?php

$this->layout('_template', ['page_title' => "Se connecter"]);
?>

<div class="card p-centered" style="max-width: 400px;">
    <div class="card-header">
        <div class="card-title h5">Se connecter</div>
        <div class="card-subtitle text-gray">
            <div class="toast bg-warning" style="border: none;">
                Cette page est nécessaire seulement pour les étudiants ayant la permission de gerer les devoirs et informations importantes.
            </div>
        </div>
    </div>
    <div class="card-body">
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
    </div>
</div>
