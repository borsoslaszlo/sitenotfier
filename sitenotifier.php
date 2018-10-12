<?php





class  SiteNotifierDB  extends SQLite3
{
    function __construct($par_sqlitedbpath)
    {
        $this->open($par_sqlitedbpath);
    }
}


class Parameters {
    
    public static $checkinterval =60;  //in seconds
}


class Mailing
{
    private static $addresses=array();
    private static $subject;
    private static $text;
    
//     static function __construct($par_addresse,$par_subject){
//         self::$addresse=$par_addresse;
//         self::$subject=$par_subject;
//     }
    
    static function  addText ($par_txt) {
        self::$text = self::$text . $par_txt ;
    }
    
    static function  addAddresse ($par_addresse){
       array_push(self::$addresses, $par_addresse);
    }
    
    static function setAddresse ($par_addresse)
    {
      self::$addresses=array();
      array_push(self::$addresses, $par_addresse);
    }
    
    static function setSubject ($par_subject) 
    {
        self::$subject = $par_subject;
    }
    
    static function sendMails (){
        foreach (self::$addresses as $address) {
            
            if (mail($address, self::$subject, self::$text)){
                echo ("Mail has been sent with the following datas:\n");
                echo ("Addresse: ".$address."\n");
                echo ("Subject:".self::$subject."\n");
                echo ("Text: ".self::$text."\n");
            }
            else {
                echo ("There was problem in sending mail.\n");
            }
          
        }
        
    }
        
    
    
    static function getText () {
        return self::$text ;
    }
    
}




$db= new SiteNotifierDB($argv[1]);



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
    $query = 'select count(*) as sumresults  from  queryresults where queryurl = \''.$par_url.'\' and queryresult = \''.$par_result.'\'';
    $result = $par_db->query ($query);
    while ($row = $result->fetchArray (SQLITE3_ASSOC)){
        $count = $row['sumresults'];
    }
    
    return $count;
    
}





//while (true) {   // if it is started from crontab not necessary

    //$result = $db->query('select queryfilters.queryurl as url , queryurls.queryfrequency , queryurls.emailaddress  , queryurls.lastquerytime   from queryfilters  join queryurls  on queryfilters.queryurl = queryurls.queryurl');
    
    $result_main = $db->query('select queryurl as url ,queryfrequency , lastquerytime ,emailaddress from queryurls');
    
    //$result_array = $result_main->fetchArray(SQLITE3_ASSOC);
    //var_dump($result_array);
    
    
    
    
    
    while ($row = $result_main->fetchArray(SQLITE3_ASSOC)) {
         $url = $row['url'];
            //echo $url."\n";
    
                
          $lastquerytime= $row['lastquerytime'];
          $queryfrequency = $row ['queryfrequency'];   //minute
          $actualtimestamp = time();
          $lastquerytimestamp  = strtotime($lastquerytime);
          $emailaddress = $row ['emailaddress'];
          
          echo "URL:".$row['url'] . "\n";
                    
          echo "Actual timestamp:".$actualtimestamp."\n";
          echo "Last query timestamp:".$lastquerytimestamp."\n";
          echo "Difference of timestamps:".($actualtimestamp-$lastquerytimestamp)." seconds.\n";
          echo "Set queryfrequency is :".($queryfrequency*60)." seconds.\n";
          
          
          if (($actualtimestamp-$lastquerytimestamp) > $queryfrequency*60 ) {
            
              $attributeresult  = $db->query ('select queryurl  , querytag , querytagattributefilters  , contentquery  , textbefore ,queryattribute , textafter   from queryfilters  where queryurl=\''.$url.'\'');
                
              while ($attributerow = $attributeresult->fetchArray(SQLITE3_ASSOC)){
                $querytag= $attributerow ['querytag'];
                $tagfilterattribute= $attributerow ['querytagattributefilters'];
                $contentquery = $attributerow ['contentquery'];
                $contentqueryattribute =  $attributerow ['queryattribute'];
                $textbefore = $attributerow ['textbefore'];
                $textafter = $attributerow ['textafter'];
                
                $attribute_filter  = explode('=',$tagfilterattribute);
                
                echo "Timestamp:".  date('Y-m-d H:i:s',$actualtimestamp)."\n";
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
                            //echo $link ."\n";
                            
                            $matches = array();
                            
                            preg_match_all('/<'.$querytag.' .*?>(.*?)<\/'.$querytag.'>/',$link,$matches);
                                             
                            $result = $matches [1] [0];

                            if (!empty($textbefore))  {$result=$textbefore.$result;}
                            if (!empty($textafter))  {$result=$result.$textafter;}
                            
                            
                            
                            if (countrows($url, $matches [1] [0], $db) == 0 ){
                                $stmt = $db->prepare ('INSERT INTO queryresults ( queryurl, queryresult, sentbyemail ) VALUES (:parqurl,:parqresult,:parqsentbymail)');
                                $stmt->bindValue(':parqurl', $url,  SQLITE3_TEXT);
                                $stmt->bindValue(':parqresult', $result,  SQLITE3_TEXT);
                                $stmt->bindValue(':parqsentbymail', 0,  SQLITE3_INTEGER);
                                $result=$stmt->execute();
                            }
                            
                        } else {
                            $result = $link->getAttribute($contentqueryattribute);
                            if (!empty($textbefore))  {$result=$textbefore.$result;}
                            if (!empty($textafter))  {$result=$result.$textafter;}
                            
                            if (countrows($url, $result, $db) == 0 ){
                                $stmt = $db->prepare ('INSERT INTO queryresults ( queryurl, queryresult, sentbyemail) VALUES (:parqurl,:parqresult,:parqsentbymail)');
                                $stmt->bindValue(':parqurl', $url,  SQLITE3_TEXT);
                                $stmt->bindValue(':parqresult', $result,  SQLITE3_TEXT);
                                $stmt->bindValue(':parqsentbymail', 0,  SQLITE3_INTEGER);
                                $result=$stmt->execute();
                            }
                        }
                    }
                    
                }
                my_curl_close($curlch);
                  
                  
              }
                           
             //mailing part on the not sent results
             
             //construct the mail text
         
              
              
              
            
             $created_date = date('Y-m-d H:i:s');
             //echo $created_date."\n";

             
             $stmt = $db->prepare ('UPDATE queryurls SET lastquerytime=:parcreatedtime WHERE queryurl=:parqurl');
             $stmt->bindValue(':parqurl', $url,  SQLITE3_TEXT);
             $stmt->bindValue(':parcreatedtime', $created_date,  SQLITE3_TEXT);
             $stmt->execute();
             
             
             //$update_cmd = 'update queryurls set lastquerytime=\''.$created_date.'\' where queryurl=\' '.$url .'\'';
             
             
             //$suc=$db->exec('update queryurls set lastquerytime=\''.$created_date.'\' where queryurl=\''.$url .'\'');
             //$db-> exec ($update_cmd);
             
             
        
             
             
             
             
             
             
        }
        //send the mail
        
        //echo $url."\n";
        $resulturls = $db->query ('select queryresult from queryresults where queryurl=\''.$url.'\' and sentbyemail=0');
        
        //var_dump($resulturls);
        
        $numrows=0;
        while ($resulturlsrow = $resulturls->fetchArray(SQLITE3_ASSOC)){
            $numrows++;
            
            Mailing::addText($resulturlsrow ['queryresult']."\n");
            //echo ($resulturlsrow ['queryresult']."\n");
            
            
            $db->exec ('update queryresults set sentbyemail=1 where queryurl =\''.$url .'\' AND queryresult = \''.$resulturlsrow ['queryresult'].'\'');
            
        }
        
        //echo ("------------------------------------"\n");
        
        if ($numrows != 0 ){
        Mailing::setSubject("Results of url : ".$url);
        Mailing::setAddresse($emailaddress);
        //echo (Mailing::getText());
        
        Mailing::sendMails();
        
        }
        
        
        
        
        
         
    }
    
    
    echo "---------------------------------------------------------------------\n";
    
//    sleep(Parameters::$checkinterval);  // if it is started from crontab
    
//}  // while (true )


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

