<?php
// Init Twig
require_once 'vendor/Twig/Autoloader.php';
Twig_Autoloader::register();
$loader = new Twig_Loader_Filesystem('templates');
$twig = new Twig_Environment($loader);

// Discover the page and call it
if (!isset($templateValues)) {
    $templateValues = [];
}

$ninjaVars = [];

$page = (isset($_GET['page']) ? $_GET['page'] : 'home');
if (in_array($page, ['home', 'help', 'search', 'item', 'phone'])) {
    include($page . '.php');
} else {
    include('home.php');
}

include('ninja.php');

// Render
echo $twig->render('index.html', $templateValues);
