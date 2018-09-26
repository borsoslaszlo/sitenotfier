<?php

class  SiteNotifierDB  extends SQLite3
{
    function __construct()
    {
        $this->open('sitenotifier.db');
    }
}


$db= new SiteNotifierDB();



function my_curl_init ($url) {
    
    $ch = curl_init();
    $timeout = 5;
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
    //$html = curl_exec($ch);
    //curl_close($ch);
    
    return $ch;    

}

function my_curl_close ($ch)
{
    curl_close ($ch);
    return ;
}




function countrows ($par_url,$par_result,$par_db)

{
    $query = 'select count (*) as sum  from  queryresults where queryurl = \''.$par_url.'\' and queryresult = \''.$par_result.'\'';
    $result = $par_db->query ($query);
    while ($row = $result->fetchArray (SQLITE3_ASSOC)){
        $count = $row['sum'];
    }
    
    return $count;
    
}





while (true) {

    $result = $db->query('select queryfilters.queryurl as url , queryurls.queryfrequency , queryurls.emailaddress  , queryurls.lastquerytime   from queryfilters  join queryurls  on queryfilters.queryurl = queryurls.queryurl');
    
    //$result_array = $result->fetchArray(SQLITE3_ASSOC);
    
    //var_dump($result_array);
    
    
    while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
         $url = $row['url'];
            echo $url."\n";
    
        
          $attributeresult  = $db->query ('select queryurl  , querytag , querytagattributefilters  , contentquery  , queryattribute  from queryfilters  where queryurl=\''.$url.'\'');
            
          while ($attributerow = $attributeresult->fetchArray(SQLITE3_ASSOC)){
            $querytag= $attributerow ['querytag'];
            $tagfilterattribute= $attributerow ['querytagattributefilters'];
            $contentquery = $attributerow ['contentquery'];
            $contentqueryattribute =  $attributerow ['queryattribute'];
            
              $attribute_filter  = split ('=',$tagfilterattribute);
            
              echo "URL:".$url."\n";
              echo "Querytag:".$querytag."\n";
              echo "Tagfilterattribute:".$tagfilterattribute."\n";
              echo "    ".$attribute_filter[0]."\n";
              echo "    ".$attribute_filter[1]."\n";
              
              echo "Is contentguery?:".$contentquery."\n";
              echo "Contentqueryattribute:".$contentqueryattribute."\n";
            
            
            
            
            
            $curlch= my_curl_init($url);            
            $html = curl_exec($curlch);
            
            
            
            
            
            # Create a DOM parser object
            $dom = new DOMDocument();
            
            # Parse the HTML from Google.
            # The @ before the method call suppresses any warnings that
            # loadHTML might throw because of invalid HTML in the page.
            @$dom->loadHTML($html);
            
            foreach($dom->getElementsByTagName($querytag) as $link) {
                # Show the <a href>
                //echo $link->getAttribute ('class');
                if ($link->getAttribute ($attribute_filter[0]) == $attribute_filter[1]){
                    
                    
                    if ($contentquery){
                        echo $link ."\n";
                        countrows($url, $link, $db);
                        
                        
                        
                        
                        
                    } else {
                        echo $link->getAttribute($contentqueryattribute)."\n";
                    }
                }
                
            }
            my_curl_close($curlch);
              
              
          }
          
          
            
        
         $created_date = date('Y-m-d H:i:s');
         echo $created_date."\n";
         
         $update_cmd = 'update queryurls set lastquerytime=\''.$created_date.'\' where queryurl=\' '.$url .'\'';
         echo $update_cmd."\n";
         
         //$suc=$db->exec('update queryurls set lastquerytime=\''.$created_date.'\' where queryurl=\''.$url .'\'');
         $suc=$db-> exec ($update_cmd);
         echo $suc."\n";
         
    }
    
    
    echo "------------------------------------------------_";
    
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






/*

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
  */  
    
?>

