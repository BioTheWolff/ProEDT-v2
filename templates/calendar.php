<?php

$this->layout('_template', ['page_title' => 'Calendrier']) ?>

<div class="columns">
    <!-- Left third left empty -->
    <div class="column col-4"></div>


    <!-- Index column -->
    <div class="column col-4 login">

        <p>
            <?php var_dump($cal ?? "test") ?>
        </p>

    </div>


    <!-- Right third left empty -->
    <div class="column col-4"></div>
</div>