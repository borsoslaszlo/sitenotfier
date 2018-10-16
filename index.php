<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Frameset//EN">
<html>

<?php 
class  SiteNotifierDB  extends SQLite3
{
    function __construct($par_sqlitedbpath)
    {
        $this->open($par_sqlitedbpath);
    }
}

$db= new SiteNotifierDB("sitenotifier.db");
$result_main = $db->query('select queryurl as url ,queryfrequency , lastquerytime ,emailaddress from queryurls');


?>


<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>Insert title hereaaaaa</title>
</head>
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<link rel="stylesheet" href="css/bootstrap.min.css" >
<script src="js/bootstrap.min.js" ></script>

    <body>
    <div class="container-fluid">
    	<div class="row">
			<form action="">    	
        		<div class="form-group">
        			<label for="queryurl">Query URL:</label>
        			<input type="url" id="queryurl" class="form-control" placeholder="http://company.com/query.php?a=1&b=2"  list="queryurls">
					<datalist id="queryurls">
					
					
					<?php 
					while ($row = $result_main->fetchArray(SQLITE3_ASSOC)) {
                    $url = $row['url'];
                    echo '<option value = "'.$url.'"  />';
					}
					?>
					
					</datalist>

        			
        		</div>
        		<div class="form-group">
        			<label for="queryfrequency">Query frequency (min):</label>
        			<input type="number" class="form-control" placeholder="1" step ="1" min="1">
        		</div>
        		
        	</form>
    	</div>
    
    
    <?php
    echo "klklk";
	?>
	</div>
    </body>

</html>