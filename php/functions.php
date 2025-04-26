<?php

    //prints a formatted variable
    function debug($variable) : string {
        echo "<pre>";
        var_dump($variable);
        echo "</pre>";
        exit;
    }

    // it escapes / sanitizes the HTML
    function sanitize($html) : string {
        $sanitize = htmlspecialchars($html);
        return $sanitize;
    }

    //date card
    function formatDateCard($date) : string{
            
        list($year, $month) = explode('-', $date);
        return $month . substr($year, -2);
        
    }

?>
