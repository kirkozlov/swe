<?php session_start(); 
include("includes/ConectionOpen.php");
                   
    $sql = "SELECT id, maintext, mainimage FROM offers";
    $sth = $conn->query($sql);
    $result=mysqli_fetch_array($sth);
    
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
                                <td colspan=2>'.$result['maintext'].'</td>
                            </tr>
                            <tr>
                                <td colspan=2><img style="max-width: 600px; max-height: 600px;" src="data:image/jpeg;base64,'.base64_encode( $result['mainimage'] ).'"/></td>
                            </tr>
                            	<td><button value="" name="yes"/>♥</button></td>
                            	<td><button value="" name="no"/>X</button></td>
                          </table>'; 
                 ?>
            </div>
        </div>
    </body>
</html>

