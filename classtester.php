<?php 

include 'RecordOperations.php';


class Valami 
{
    public  $a= "Első text mező ";
    public  $b = 22;

    
    
}

$valami1 = new Valami ();
$valami2 = new Valami ();


echo RecordOperations::addRecord($valami1);

echo RecordOperations::updateRecord($valami1,$valami2);


?>