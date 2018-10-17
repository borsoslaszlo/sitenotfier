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
<script src="js/functions.js" ></script>
<script src="js/jquery-3.3.1.min.js" ></script>




    <body>
    <div class="container-fluid">
    	<div class="row">
    	<div class="col-3"></div>
    	<div class="col-6">
			<form action="">    	
        		<div class="form-group">
        			<label for="queryurl">Query URL:</label>
        			<input type="url" id="queryurl" class="form-control" placeholder="http://company.com/query.php?a=1&b=2"  list="queryurls" onchange="addURLItemsToView  ($('#queryurl').val())">
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
        			<label for="queryfrequency">Query frequency (minute):</label>
        			<input id="queryfrequency" type="number" class="form-control" placeholder="1" step ="1" min="1">
        		</div>
        		<div class="form-group">
        			<label for="email">E-mail: </label>
        			<input id="email" type="email" class="form-control" placeholder="debil.denes@gmail.com" >
        		</div>
        		
        		<div class="form-group">
        			<label for="querytag">Querytag: </label>
        			<input id="querytag" type="text" class="form-control" placeholder="a" >
        			<small id="querytaghelp" class="form-text text-muted">Eg.  a  for searching   &lt;a&gt;   tags in page.  </small>
        		</div>
        		
        		<div class="form-group">
        			<label for="queryattributefilter">Querytag: </label>
        			<input id="queryattributefilter" type="text" class="form-control" placeholder="class=result" >
        			<small id="queryattributefilterhelp" class="form-text text-muted">Eg.  a  for searching   &lt;a&gt;   tags  with class="result"  attribute in page.  </small>
        		</div>
        		
        		
        		
        	</form>
    	</div>
    	<div class="col-3"></div>
    </div>
    
    
    <?php
    echo "klklk";
	?>
	</div>
    </body>

</html>