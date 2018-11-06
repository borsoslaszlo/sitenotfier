<?php 

include 'RecordOperations.php';


class Valami 
{
    public  $string_type;  //string type 
    public  $integer_type ; //integer type 
    public  $boolean_type ;  //boolean type is used as numeric type 1 or 0
    public  $decimal_type ;  //decimal type
    
    
    public  function __construct ($par_a , $par_b , $par_c , $par_d ) {
        $this->string_type = $par_a;
        $this->integer_type = $par_b;
        $this->boolean_type = $par_c;
        $this->decimal_type = $par_d;
    }
    public  function getVars () {
     
        return $this->string_type . $this->integer_type  . $this->boolean_type  . $this->decimal_type;
    }
    
    
}

$valami1 = new Valami ("Első valami text mező" , 1 , 1 , 1.11 );
$valami2 = new Valami ("Második valami text mező" ,2 , 0, 2.22 );

echo $valami1->getVars();

echo "INSERT teszt:";
echo "<br>";
echo RecordOperations::addRecord($valami1);
echo "<br>";

echo "Change record (Update) teszt:";
echo "<br>";
echo RecordOperations::changeRecord($valami1,$valami2);
echo "<br>";


echo "Delete  record  teszt:";
echo "<br>";
echo RecordOperations::deleteRecord($valami1);
echo "<br>";

// DELETE FROM table name where string_type= 




?>