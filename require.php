<?php 

include ('functions.php');

// TODO: delete string /blog after deployment
// homepage url as constant
$url = getAddress();
define('ROOT', $url . '/blog');
// define("DS", DIRECTORY_SEPARATOR);
// define("ROOT_PATH", realpath(dirname(__FILE__) . DS."..".DS));

require_once 'php-activerecord/ActiveRecord.php';
ActiveRecord\Config::initialize(function($cfg)
{
    $cfg->set_model_directory('models');
    $cfg->set_connections(array(
        'development' => 'mysql://root:@localhost/blogisek?charset=utf8'));
});




