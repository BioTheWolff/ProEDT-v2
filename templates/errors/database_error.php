<?php
    $this->layout('_template', [
        'page_title' => "Database Error"
    ]);
?>

<p class="m-auto w-max bg-error p-2 text-center">
    WARNING: SERVICE RUNNING IN DEGRADED MODE<br>
    The database is not reachable or encountered an error, and causes the service to run in degraded mode.<br>
    As long as the database is unreachable, the service will remain in degraded mode.<br>
    Please come back later.
</p>