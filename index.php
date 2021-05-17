<?php
/**
 * Laravel - A PHP Framework For Web Artisans
 *
 * @package  Laravel
 * @author   Taylor Otwell <taylor@laravel.com>
 */
header("Access-Control-Allow-Origin: *");

function addB24Auth($form = false)
{
   if($form){
    foreach ($_REQUEST['AR_AUTH'] as $key => $value) {
        echo '<input type="hidden" name="AR_AUTH['.$key.']" value="'.$value.'">';
    }
   }
   else{
       return '?'.http_build_query(['AR_AUTH'=>$_REQUEST['AR_AUTH']],'','&');
   }
}

if(!isset($_REQUEST['AR_AUTH'])){

    $_REQUEST['AR_AUTH'] = $_REQUEST;
}


$uri = urldecode(
    parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH)
);
// This file allows us to emulate Apache's "mod_rewrite" functionality from the
// built-in PHP web server. This provides a convenient way to test a Laravel
// application without having installed a "real" web server software here.
if ($uri !== '/' && file_exists(__DIR__.'/public'.$uri)) {
    return false;
}

require_once __DIR__.'/public/index.php';
