<?php

$this->layout('_template', ['page_title' => "Se connecter"]);
    $this->palladium = new App\Services\Session\Palladium();
?>

<form method="post">
    <label>
        Username
        <input name="username" type="text" required>
    </label>

    <label>
        Password
        <input name="password" type="password" required>
    </label>

    <input type="submit">
</form>