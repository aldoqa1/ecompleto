<?php 

namespace Model;

class ActiveRecord{ 
    
    static $db;
    public $id;
    public $alerts = [];
    static $table = "";
    public $columns = [];

    public function __construct(){
        
    }

    public static function setDB($db){
        self::$db = $db;
    }

    public function getAlerts(){
        return $this->alerts;
    }

    public function setAlert($type, $message){
        $type = sanitize($type);
        $message = sanitize($message);
        //It sets alerts by type
        $this->alerts[$type][] = $message;
    }


    public function sanitizeAttributes(){
        
        $attributes = [];

        foreach($this->columns as $column){
            if( $this->$column){
                $attributes[$column] =  self::$db->escape_string($this->$column);

            }
        }
        return $attributes;
    }

    public function sincronize($args){

        foreach($args as $key => $value){
            if(($key!=NULL) && isset($this->$key)){
                $this->$key = trim($value);
            }
        }
    }

    public function saveUpdate(){

        $attributes = $this->sanitizeAttributes();
       
        $updatedValues = "";

        foreach($attributes as $key => $value){
            if($key!="id"){
                $updatedValues.= " $key = '$value',";
            }

        }

        $updatedValues = substr($updatedValues, 0, -1);

        $query = "UPDATE " . static::$table ." SET$updatedValues WHERE id = $this->id;";
        
        return self::$db->query($query);
    }
    
    public static function find($type, $value){
        $type = self::$db->escape_string($type);
        $value = self::$db->escape_string($value);
        $query = "SELECT * FROM " . static::$table . " WHERE $type = '$value' LIMIT 1;";
        
        $consult = self::$db->query($query)->fetch_assoc();
        return new static($consult);

    }

    public function saveProperty($property){
        $query = "UPDATE " . static::$table . " SET $property = '". $this->$property ."' WHERE id = $this->id;";
        
        return self::$db->query($query);
    }


}

?>