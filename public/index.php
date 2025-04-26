<?php 
require_once __DIR__ . "/../php/app.php";

use MVC\Router;
use Controller\OrderController;

$router = new Router();


/* ORDER RUTES -------------- START -------------------- */

    $router->get("/", [OrderController::class, "validate"]);
    $router->post("/", [OrderController::class, "validate"]);

/* ORDER RUTES -------------- END -------------------- */



//It checks the valid rutes
$router->checkRutes();