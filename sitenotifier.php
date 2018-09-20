<?php

class  SiteNotifierDB  extends SQLite3
{
    function __construct()
    {
        $this->open('sitenotifier.db');
    }
}


$db= new SiteNotifierDB();




while (true) {

    $result = $db->query('select queryfilters.queryurl as url , queryurls.queryfrequency , queryurls.emailaddress  , queryurls.lastquerytime   from queryfilters  join queryurls  on queryfilters.queryurl = queryurls.queryurl');
    
    //$result_array = $result->fetchArray(SQLITE3_ASSOC);
    
    //var_dump($result_array);
    
    
    while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
         $url = $row['url'];
            echo $url."\n";
    
        
        
         $created_date = date('Y-m-d H:i:s');
         echo $created_date."\n";
         
         $update_cmd = 'update queryurls set lastquerytime=\''.$created_date.'\' where queryurl=\' '.$url .'\'';
         echo $update_cmd;
         
         $suc=$db->exec('update queryurls set lastquerytime=\''.$created_date.'\' where queryurl=\''.$url .'\'');
         echo $suc;
         
    }
    
    
    
    
    sleep(60);
    
}


// $result = $db->query('SELECT * FROM queryurls');

// //$result_array = $result->fetchArray(SQLITE3_ASSOC);

// //var_dump($result_array);


// while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
//     echo $row['queryurl']."\n";
// }


// foreach ($result_array as $row){
//     echo "----";
    
//    // var_dump($row);
//     echo $row[1]["queryurl"];
    
    
// }




echo "------------------------------------------------_";



# Use the Curl extension to query Google and get back a page of results
$url = "https://ingatlan.jofogas.hu/fejer/szekesfehervar/sorhaz-ikerhaz-hazresz?st=s";
$ch = curl_init();
$timeout = 5;
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
$html = curl_exec($ch);
curl_close($ch);

# Create a DOM parser object
$dom = new DOMDocument();

# Parse the HTML from Google.
# The @ before the method call suppresses any warnings that
# loadHTML might throw because of invalid HTML in the page.
@$dom->loadHTML($html);

# Iterate over all the <a> tags
foreach($dom->getElementsByTagName('a') as $link) {
    # Show the <a href>
    //echo $link->getAttribute ('class');
     if ($link->getAttribute ('class') == 'subject'){
     echo $link->getAttribute('href')."\n"; 
     }
    
}
    
    
?>

