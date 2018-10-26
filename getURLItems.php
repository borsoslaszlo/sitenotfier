<?php


class  SiteNotifierDB  extends SQLite3
{
    function __construct($par_sqlitedbpath)
    {
        $this->open($par_sqlitedbpath);
    }
}


$jsonparameter = $_POST ['parameterjson'];

$array_parameter = json_decode ($jsonparameter,true);

$queryurl =$array_parameter ['queryurl'];



$db= new SiteNotifierDB("sitenotifier.db");
$result_main = $db->query("SELECT   querytag , querytagattributefilters, contentquery , queryattribute  FROM queryfilters WHERE queryurl='".$queryurl."'");

$results = array();


//echo json_encode($result_main->fetchArray(SQLITE3_ASSOC));

//echo $jsonparameter;

//echo var_dump ($result_main);



while ($row = $result_main->fetchArray(SQLITE3_ASSOC)) {
    
    array_push($results,json_encode($row));
    
 }

echo implode(";", $results); 
 
 
?>



