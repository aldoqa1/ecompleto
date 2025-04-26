<?php

    function database(){

        $db = mysqli_connect($_ENV["DB_HOST"], $_ENV["DB_USER"], $_ENV["DB_PASS"], $_ENV["DB_NAME"]);
        
        if($db){
            return $db;
        }else{
            echo "Error: It wasnt possible to connect to Mysql.";
            echo "Error running mysql connection: " . mysqli_connect_errno();
            echo "Error running mysql connectio: " . mysqli_connect_error();
            exit;
        }
        
    }

?>