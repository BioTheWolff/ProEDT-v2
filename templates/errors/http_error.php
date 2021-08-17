<?php
    if (isset($error_title)) {
        $arr = explode(' ', $error_title); // explode the error
        $name = implode(" ", array_slice($arr, 1)); // reconstruct the name
        $error = "HTTP $arr[0]: $name"; // glue the code and the name together with a colon
    } else $error = 'Unknown HTTP Error';

    $this->layout('_template', [
            'page_title' => 'Erreur'
    ]);
?>

<div class="columns">

    <div class="column col-4"></div>

    <div class="column col-4 http-error text-center">
        <h2 class="text-error"><?= $error ?></h2>
        <p class="text-warning">
            If you believe this is an error, please contact your administrator.
        </p>
    </div>

    <div class="column col-4"></div>


</div>