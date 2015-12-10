<?php session_start(); 
	if($_SESSION['admin']!=1)
		header('Location: login.php');
	include("includes/ConectionOpen.php");
	if(isset($_POST['delete'])) {
		$id = "";
		$id = $id.$_POST["delete"];
		$query = "";				  
		$query = "DELETE FROM users WHERE id='".$id."'";
		//echo $query;
		$res = $conn->query($query);
	}
	if(isset($_POST['changeFlag'])) {
		$id = "";
		$id = $id.$_POST["changeFlag"];
		$query = "";				  
		$query = "UPDATE users SET goldflag = 1 - goldflag WHERE id='".$id."'";
		//echo $query;
		$res = $conn->query($query);
	}
    $str1 = "SELECT id,email,goldflag FROM users" ;
    $res = $conn->query($str1);
	while($row=$res->fetch_row()){
		$user[$row[0]] = $row[1];
		$flag[$row[0]] = $row[2];
	}	
    $conn->close();
 ?>
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
						if (isset($user)) {
							echo '<tr><td>Benutzer</td><td>Status</td></tr>';
							foreach ($user as $key => $value) {
								echo '<tr>';
								echo '<td>'.$value.'</td>';
								if ($flag[$key] == 1) {
									echo '<td>premium</td>';
								} else {
									echo "<td>normal</td>";
								}
								
								echo '<td>
										<form id="delete" action="" method="post" enctype="multipart/form-data" >
											<button value="'.$key.'" name="delete"/>Löschen</button>
										</form>
									</td>';
								echo '<td>
										<form id="changeFlag" action="" method="post" enctype="multipart/form-data" >
											<button value="'.$key.'" name="changeFlag"/>Status ändern</button>
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

