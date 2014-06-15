<?php
require_once 'activerecord/ActiveRecord.php';

ActiveRecord\Config::initialize(function($cfg)
{
    $cfg->set_model_directory(dirname(__FILE__) . '/models');
    $cfg->set_connections(array('development' => 'mysql://site_admin:w!@#QWE@localhost/taobao_db?charset=utf8'));
});
?>
