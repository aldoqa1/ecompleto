<?php 
    require_once 'functions.php';
    require_once 'database.php';
    require_once __DIR__ . '/../vendor/autoload.php';

    use Model\ActiveRecord;
    use Dotenv\Dotenv;

    $dotenv = Dotenv::CreateImmutable(__DIR__);
    $dotenv->safeLoad();

    $db = database();
    $db->set_charset("utf8");
    
    ActiveRecord::setDB($db);
    

?>
