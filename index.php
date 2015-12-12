<?php session_start(); 
	include("includes/ConectionOpen.php");
    $counter = 0;         
          
	if(isset($_POST['next'])) {
		$query = "SELECT COUNT(*) AS NumberOfOffers FROM offers;";
		$res = $conn->query($query);
		$numberofoffers = mysqli_fetch_array($res);
		$counter = ($_POST["next"] + 1) % $numberofoffers[0];
	}
    $sql = "SELECT id, maintext, price, mainimage FROM offers";
    $sth = $conn->query($sql);
    $tmp = 0;
	while($row=$sth->fetch_row()){
		$id[$tmp] = $row[0];
		$interMaintext[$row[0]] = $row[1];	
		$interPrice[$row[0]] = $row[2];	
		$interImage[$row[0]] = $row[3];	
		$tmp++;
	}
    //$result=mysqli_fetch_array($sth);
    
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
                                <td colspan=2><img style="max-width: 600px; max-height: 600px;" src="data:image/jpeg;base64,'.base64_encode( $interImage[$id[$counter]] ).'"/></td>
                            </tr>
                            <tr>
                                <td colspan=2>'.$interMaintext[$id[$counter]].'</td>
                            </tr>
                            <tr>
                            	<td>
                            		<form id="like" action="details.php" method="get" enctype="multipart/form-data" >
                            			<button value="'.$id[$counter].'" name="id"/>♥</button>  
                        			</form>                         	
                            	</td>
                            	<td>
									<form id="next" action="" method="post" enctype="multipart/form-data" >
										<button value="'.$counter.'" name="next"/>✗</button>
									</form>
								</td>
                            </tr>
                          </table>'; 
                 ?>
            </div>
        </div>
    </body>
</html>

