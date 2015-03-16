<?php

/*
 * Application and layout framework includes...
 */
include 'siteroot.php';
require_once 'Slim/Slim.php';
require_once 'includes/session.php';
require_once 'Twig/Autoloader.php';
require_once 'includes/DbHandler.php';
include 'includes/TextUtil.php';

\Slim\Slim::registerAutoloader();

require_once 'Views/Twig.php';
require_once 'Views/TwigExtension.php';
/*
 * Initialize Slim to use the TwigView handler
 */
$app = new \Slim\Slim(array('view' => new \Slim\Views\Twig(), 'production' => true));
//Slim::init(array('view' => 'TwigView','debug'=>true,'log.enabled'=>true));
$view = $app->view();
$view->parserExtensions = array(
    new \Slim\Views\TwigExtension()
);
$view->globals = array('session' => $_SESSION);
function authenticate()
{
//check if user is already registered
    $app = \Slim\Slim::getInstance();
    if (isset($_SESSION["user_id"]) && isset($_SESSION["email"])) {
        return;
    }
//redirect to registration page
    $app->redirect('login');
}

function verifyEmail($mail)
{
    validateEmail($mail);
}

function logout()
{
    session_unset();
    $_SESSION = array();
    if (ini_get("session_use_cookies")) {
        $param = session_get_cookie_params();
        setcookie(session_name(), '', time() - 4200, $param["path"], $param["domain"], $param["secure"], $param["httponly"]);
    }
    session_destroy();
    $app = \Slim\Slim::getInstance();
    $app->redirect('/login');
}

function confirm_login()
{
    $app = \Slim\Slim::getInstance();
    if (!isset($_SESSION["user_id"]) && !isset($_SESSION["email"])) {
        $app->redirect('login');
    }
}

/**
 * Validating email address
 */
function validateEmail($email)
{
    $app = \Slim\Slim::getInstance();
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $response["response"] = 0;
        $response["message"] = 'Email address is not valid';
        \Slim\Slim::view()->setData(array('error' => $response['message']));
//        $app->stop();
        $app->render('index.php');
    }
}

function verifyRequiredParams($required_fields)
{
    $error = false;
    $error_fields = "";
    //$request_params = array();
    $request_params = $_REQUEST;
    $app = \Slim\Slim::getInstance();
    // Handling PUT request params
    if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
        parse_str($app->request()->getBody(), $request_params);
    }

    foreach ($required_fields as $field) {
        if (!isset($request_params[$field]) || strlen(trim($request_params[$field])) <= 0) {
            $error = true;
            $error_fields .= $field . ', ';
        }
    }

    if ($error) {
        // Required field(s) are missing or empty
        // echo error json and stop the app
        $response = array();

        $response["response"] = 0;
        $response["message"] = 'Required field(s) ' . substr($error_fields, 0, -2) . ' is missing or empty';
        return $response;
    } else {
        return false;
    }
};
/* this should supply all feeds(notifications of friends likes,comments,messages,show offs,how abouts)*/
$app->get('/home',function() use($app){
        $app->render()
});/* this route is to grant user*/
$app->post('/quick-access',function() use($app){
        $app->render()
});
$app->get('/User/:id',function($id) use($app){
        $app->render()
});
$app->post('/User/:video',function($video) use($app){
        $app->render()
});
$app->get('/User/:video',function($app) use($app){
        $app->render()
});
/*
 * Run the Application
 */
$app->run();
?>