<?php 

namespace MVC;

class Router{

    //Rute lists
    public $getRutes = [];
    public $postRutes = [];
    
    //It stores the get rute to denote that it exists
    public function get($url, $function){
        $this->getRutes[$url] = $function;
    }

    //It stores the post rute to denote that it exists
    public function post($url, $function){
        $this->postRutes[$url] = $function;
    }
    
    /*https://example.com/path/to/resource?query=123
    This ["REQUEST_URI"] takes everything after the .com so, = /path/to/resource?query=123
    strtok takes off everything after the ? (the ? as well) so, = /path/to/resource
    So in this way we can get the clean path! 
    If the strtok($_SERVER["REQUEST_URI"], "?") is null itll return '/'
    */
    public function checkRutes(){
        $currentRute = strtok($_SERVER["REQUEST_URI"], "?") ?? "/"; 
        $method = $_SERVER["REQUEST_METHOD"];

        //Getting current method
        if($method == "GET"){
            $function = $this->getRutes[$currentRute] ?? null;
        }else{
            $function = $this->postRutes[$currentRute] ?? null;
        }

        //Checking if there is a function related to the currentRute, if thats the case it will be used for that rute
        if(!$function){
            header("Location: /404");
        }else{
            //It runs the function, giving as parameter the current object itself (the current instance)
            call_user_func($function, $this);
        }

    }

    //It renders a specific view and it sets the variables that will be used in our render view
    public function render($view, $variables){

        foreach($variables as $key => $value){
            //Setting variables to be used in our render view
            $$key = $value; 
        }

        //It starts saving code in the memory
        ob_start();

        //The code inside the choosen rute (view) is saved in memory 
        include __DIR__ . "/views/$view.php";
        
        //It gets the saved code into this variable and the memory is cleaned
        $content = ob_get_clean();

        //It doesnt contain a admin rute, so the normal layout will be set
        include __DIR__ . "/views/layout.php";
    

    }
}

    