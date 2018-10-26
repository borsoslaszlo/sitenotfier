<?php

class RecordOperations   /*Singleton*/
{
    private $arTableFields=array();
    private $tableName;
    
    private static function init ($parobject)
    {
        self::$arTableFields  = get_object_vars($parobject);
        self::$tableName = get_class($parobject);
        
    }
    
    
    public static  function addRecord ($object)
    {
        self::init($object);
        
        $fieldNames = "(";
        $fieldValues  = "(";
        foreach ($arTableFields as $key=>$value)
        {
           $fieldNames = $fieldNames.$key;
           
            
        }
        
    }
    
    
}

