<?php session_start(); 
	if($_SESSION['admin']!=1)
		header('Location: login.php');
	include("includes/ConectionOpen.php");
	if(isset($_POST['delete'])) {
		$id = "";
		$id = $id.$_POST["delete"];
		$query = "";				  
		$query = "DELETE FROM offers WHERE id='".$id."'";
		//echo $query;
		$res = $conn->query($query);
	}
    $str1 = "SELECT id,maintext FROM offers" ;
    $res = $conn->query($str1);
	while($row=$res->fetch_row()){
		$offers[$row[0]] = $row[1];
	}
	
    $conn->close();?>
<html>
    <head>
        <meta charset="utf-8"/>
        <meta name="viewport" content="width=device-width,initial-scale=1.0"/>
        <link rel="stylesheet" href="css/main.css" type="text/css" />
        <link rel="stylesheet" href="css/menu.css" type="text/css" />

    </head>
    <body>
        <?php 
            include("includes/menu.php");
	
        ?>
        <div class="main">
            <div class="content">
				<table>
					<?php
						if (isset($offers)) {
							echo '<tr><td>Anzeige</td><td></td></tr>';
							foreach ($offers as $key => $value) {
								echo '<tr>';
								echo '<td>'.$value.'</td>';
								echo '<td>
										<form id="delete" action="" method="post" enctype="multipart/form-data" >
											<button value="'.$key.'" name="delete"/>Löschen</button>
										</form>
									</td>';
								echo '</tr>';
							}
						}             
					 ?>
				</table>
            </div>
        </div>
    </body>
</html>

