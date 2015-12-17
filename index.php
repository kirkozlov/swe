<?php session_start(); 
	include("includes/ConectionOpen.php");
    if (!isset($_SESSION['anzeigencounter'])) {        
    	$_SESSION['anzeigencounter'] = 0;
    }
	if(isset($_GET['next'])) {
		$query = "SELECT COUNT(*) AS NumberOfOffers FROM offers;";
		$res = $conn->query($query);
		$numberofoffers = mysqli_fetch_array($res);
		$tmp = ($_SESSION['anzeigencounter'] + 1) % $numberofoffers[0];
		$_SESSION['anzeigencounter'] = $tmp;
	}
    $sql = "SELECT id, maintext, price, mainimage FROM offers ORDER BY id LIMIT ".$_SESSION['anzeigencounter'].", 1";
    $row = $conn->query($sql)->fetch_row();
    if ($row) {
		$id = $row[0];
		$interMaintext = $row[1];	
		$interPrice = $row[2];	
		$interImage = $row[3];	
    }
   	/*
	um mit filter zu vergleichen:
	
	SELECT * from offers_tags WHERE (2^tagsid & $_COOKIES['filter']) == 2^tagsid
	*/
    $conn->close();  
?>
<html>
    <head>
        <meta charset="utf-8"/>
        <meta name="viewport" content="width=device-width,initial-scale=1.0"/>
        <link rel="stylesheet" href="css/main.css" type="text/css" />
        <link rel="stylesheet" href="css/menu.css" type="text/css" />
		<script type="text/javascript">
			
			function mdown(e){
				i=e.pageX;
				document.getElementById("temp").innerHTML = "temp"+i;
			}
			function mup(e){
				i=e.pageX;
				document.getElementById("temp").innerHTML = "temp"+i;
			}
		
		</script>
    </head>
    <body onmousedown="mdown(event)" onclick="mup(event)" onmouseup="mup(event)">
	
        <?php 
            include("includes/menu.php");
			//echo $_SESSION['idu']; 
			//$_SESSION['filter']['1'] = 1;
            //var_dump($_SESSION); 
        ?>
		<div id="temp">TEMP</div>
        <div class="main">
            <div class="content">
                <?php
                    
                    
                    echo '<table >
                            <tr>
                                <td colspan=2><img style="max-width: 600px; max-height: 600px;" src="data:image/jpeg;base64,'.base64_encode( $interImage ).'"/></td>
                            </tr>
                            <tr>
                                <td colspan=2>'.$interMaintext.'</td>
                            </tr>
                            <tr>
                            	<td>
                            		<form id="like" action="details.php" method="get" enctype="multipart/form-data" >
                            			<button value="'.$id.'" name="id"/>♥</button>  
                        			</form>                         	
                            	</td>
                            	<td>
									<form id="next" action="" method="get" enctype="multipart/form-data" >
										<button value="" name="next"/>✗</button>
									</form>
								</td>
                            </tr>
                          </table>'; 
                 ?>
            </div>
        </div>
    </body>
</html>

