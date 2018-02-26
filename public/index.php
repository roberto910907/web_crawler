<?php
/**
 * Implementing front controller design pattern.
 *
 * @author  Roberto Rielo <roberto910907@gmail.com>.
 *
 * @version Crawler v1.0 06/02/18 23:35 PM
 */
require __DIR__ . '/../vendor/autoload.php';

use App\Controller\FilterController;
use App\Helper\ObjectHelper;
use App\Session\Session;

$session = new Session();

$session->start();

/* Simulating Dependency Injection when objects are created and provided to class, now it's easy to be changed to a different one */
$controller = new FilterController($session);

if ($_POST) {
    return $controller->renderFilterTable(ObjectHelper::createObjectFromPOST($_POST));
}

return $controller->renderFilterTable();
