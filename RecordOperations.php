<?php

class RecordOperations   /*Singleton*/
{
    private static $arNewTableFields=array();
    private static $tableName;
    
    private  static $arOldTableFields=array();

    
    private function init ()
    {
        if ( func_num_args() === 1) { self::init_1(func_get_args() [0]); }
        if ( func_num_args()=== 2) { self::init_2(func_get_args() [0] , func_get_args() [1] ) ;}

        
    }
    
    
    private static function init_1 ($parnewobject)
    {
        self::$arNewTableFields  = get_object_vars($parnewobject);
        self::$tableName = get_class($parnewobject);
    }
    
    
    
    
    private static function init_2 ($parnewobject,$paroldobject)
    {
        
        if   ( get_class($parnewobject) !== get_class($paroldobject) ) {
            throw new Exception("Different classes as parameters!");
        }
            
        
        
        self::$arNewTableFields  = get_object_vars($parnewobject);
        self::$tableName = get_class($parnewobject);
        self::$arOldTableFields  = get_object_vars($paroldobject);
        
        
        
    }
    
    
    
    
    
    public static  function addRecord ($object)
    {
        self::init($object);
        
        $fieldNames = "(";
        $fieldValues  = "(";
        foreach (self::$arNewTableFields as $key=>$value)
        {
           $fieldNames = $fieldNames.$key;
           $fieldNames = $fieldNames.",";
           
           if (gettype($value) == "string" ) {
               $fieldValues =$fieldValues ."'". $value ."'";
           } else {
               $fieldValues =$fieldValues .$value;
           }
           $fieldValues = $fieldValues.",";
           
        }
        
        $fieldNames = rtrim($fieldNames,",");
        $fieldValues = rtrim($fieldValues,",");
        $fieldNames =  $fieldNames .")";
        $fieldValues =$fieldValues . ")";
        
        
        
        return  "INSERT INTO ". self::$tableName ." " . $fieldNames ." " ."VALUES ".  " ".  $fieldValues;
    }
    
    public  static function  updateRecord ($objectOld,$objectNew)
    {
        self::init($objectOld,$objectNew);
        
        $newValues = "";
        $oldValues = "";
        
        foreach (self::$arNewTableFields as $key=>$value)
        {
                echo $key;
                echo $value . "\n";
            $newValues = $newValues . $key . "=" . (gettype($value) === "string")?"'":"" . $value .  (gettype($value) === "string")?"'":"" . ",";
        }
        
        foreach (self::$arOldTableFields as $key=>$value)
        {
            echo $key;
            echo $value ;
            
            $oldValues = $oldValues . $key . "=" . (gettype($value) === "string")?"'":"" . $value .  (gettype($value) === "string")?"'":"" . " AND ";
        }
        
        
        $newValues = rtrim($newValues,",");
        $oldValues = rtrim ($oldValues , " AND ");        
        
        
        return "UPDATE " . self::$tableName . " SET "  . $newValues .  " WHERE " . $oldValues; 
    }
    
    
    
    
}

