<?php

require_once "controllers/MainController.php";

try {
    $app = new MainController();
    echo $app->run();
} catch (Throwable $e) {
    echo json_encode(Array('error' => $e->getMessage()));
}
