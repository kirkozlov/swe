<?php session_start(); 
	include("includes/ConectionOpen.php");
	$id = "";
	$gekauft = false;
	$contact = "";
	if (isset($_POST['id'])) {
		$id = $_POST['id'];
	}

	if (isset($_POST['like'])) {
		$id = $_POST['like'];
		if (!isset($_SESSION['idu'])) {
			header("Location: login.php");
		} else {

//			$_SESSION['anzeigencounter'] = $_SESSION['anzeigencounter'] + 1;

			$userid = $_SESSION['idu'];
			$sql = "SELECT * FROM interests WHERE userid='".$userid."' AND offersid='".$id."'";
			$res = $conn->query($sql);
			if ($res->num_rows === 0){
				$sql = "INSERT INTO interests (offersid, userid) VALUES ('$id', '$userid')";
				$res = $conn->query($sql);
				
				//Anzahl verringern wenn größer 0
				$sql = "UPDATE offers SET amount = amount - 1 WHERE id='".$id."' and amount > 0";
				$res = $conn->query($sql);
			}
			$sql = "SELECT email FROM users INNER JOIN offers ON offers.userid = users.id WHERE offers.id='".$id."'";
			$res = $conn->query($sql);
			$email = mysqli_fetch_array($res);
			$contact = $email[0];
			$gekauft = true;
		}
	}
	
    $sql = "SELECT maintext, price, mainimage, amount, latitude, longtitude FROM offers WHERE id='".$id."'";
	$res = $conn->query($sql);
	if($row=$res->fetch_row()) {
		$interMaintext = $row[0];	
		$interPrice = $row[1];	
		$interImage = $row[2];	
		$interAmount = $row[3];
		$lat = $row[4];
		$lng = $row[5];
	}
	$sql = "SELECT calculateTheDistance(" . $_COOKIE['pos'] . ", ".$lat.", ".$lng.")  as dis FROM offers WHERE calculateTheDistance(" . $_COOKIE['pos'] . ", ".$lat.", ".$lng.") <=  ". (isset($_COOKIE['km']) ? $_COOKIE['km']."000" : "50000");"";
	$res = $conn->query($sql);
	if($res != null && $row = $res->fetch_row()) {
		$km = $row[0];
	}
	
	$sql = "SELECT insideid, detailledtext FROM detailedtexts WHERE offersid='".$id."'";
	$res = $conn->query($sql);
	while($row=$res->fetch_row()) {	
		$interTexts[$row[0]] = $row[1];
	}

	$sql = "SELECT insideid, image FROM images WHERE offersid='".$id."'";
	$res = $conn->query($sql);
	while($row=$res->fetch_row()){	
		$interImages[$row[0]] = $row[1];
	}

	$sql = "SELECT tags.name FROM tags INNER JOIN offers_tags ON tags.id = offers_tags.tagsid WHERE offers_tags.offersid = '".$id."'";
	$res = $conn->query($sql);
	if($res != null && $row = $res->fetch_row()) {
		$category = $row[0];
	}
    
    $conn->close();
    
?>
<html>
    <head>
        <meta charset="utf-8"/>
        <meta name="viewport" content="width=device-width,initial-scale=1.0"/>
        <link rel="stylesheet" href="css/main.css" type="text/css" />
        <link rel="stylesheet" href="css/details.css" type="text/css" />
        <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
        <link href="css/materialize.css" type="text/css" rel="stylesheet" media="screen,projection"/>
        <link href="css/materialize_own.css" type="text/css" rel="stylesheet" media="screen,projection"/>
		<script src="includes/jquery.js"></script>
		<script src="includes/cookies.js"></script>
		<script>
			function setCookie(v){
				createCookie('idList', readCookie('idList') ? readCookie('idList') + ", " + v : v , 30);
			}
		</script>
		
		<script>
			$(document).ready(function(){
			    PopUpHide();
			<?php
				if($gekauft == 0) {
					
				}
				else {
					echo "document.getElementById('ppt').innerHTML='Sie haben diesen Artikel erworben. Die Kontaktdaten des Verkäufers: ".$contact."';
					PopUpShow();";
				}	
				?>			
			});
			
			function PopUpShow(){
				$("#popup").show();
			}
			function PopUpHide(){
				$("#popup").hide();
			}
			function showContact() {
				PopUpShow();
			}
		</script>
    </head>
    <body>
        <?php 
            include("includes/menu.php");
        ?>
        <div class="main">
            <div class="content"> 
                
                <div class="row"><div class="col s12 m12 l12"><p></p></div></div>
                
                <div class="row">
                    <div class="col s12 m12 l3 offset-l4">
                         <?php echo '<img class="img_responsivness" src="data:image/jpeg;base64,'.base64_encode( $interImage ).'"/>';?>
                    </div>
                </div>
                
                <div class="row">
                    <?php echo '<div class="col s12 m12 l12 text_center"><h5 style="word-break:break-all;word-wrap:break-word">'.$interMaintext.'</h5></div>';?>
                </div>
                    
                <?php
                    for ($i = 1; $i <= 10; $i++) {
                        if (isset($interTexts[$i])) {
                            echo '<div class="row">
                                    <div class="col s12 m12 l4 offset-l4"><p style="word-break:break-all;word-wrap:break-word">'.$interTexts[$i].'</p></div></div>';
                        }
                        if (isset($interImages[$i])) {
                            echo '<div class="row">
                                    <div class="col s12 m12 l4 offset-l4"><img class="img_responsivness" src="data:image/jpeg;base64,'.base64_encode( $interImages[$i] ).'"/></img></div></div>';
                        }
                    }?>
                
                    <table class="bordered">
                        <tbody>
                            <tr><td></td><td></td></tr>
                            <tr>
                                <td>Ort:</td>
                                <?php 
                                    $url = "https://maps.googleapis.com/maps/api/geocode/json?latlng=".$lat.",".$lng."&sensor=true";
                                    $json = file_get_contents($url);
                                    $obj = json_decode($json, true);
                                    $adr = $obj['results'][0]['formatted_address'];
                                    echo '<td>'.$adr.'</td>';
                                ?>
                            </tr>
                            <tr>
                                <td>Entfernung:</td><td><?php if(isset($km)) echo $km;?>m</td>
                            </tr>
                            <tr>
                                <td>Anzahl:</td><td><?php echo $interAmount?></td>
                            </tr>
                            <tr>
                                <td>Preis:</td><td><?php echo $interPrice; ?>€</td>
                            </tr>
                            <tr>
                                <td>Kategorie:</td><td><?php echo $category?></td>
                            </tr>
                        </tbody>
                    </table>
                
  
                <div class="row"><div class="col s12 m12 l12"><p></p></div></div>
                
                <div class="row">
                    <div class="col s1 m1 l2"><h1/></div>
                    <div class="col s3 m3 l3">
                        <form class="center_dings" id="like" action="" method="post" enctype="multipart/form-data" > 
                            <?php echo '<button onclick="showContact()" value="'.$id.'" name="like" class="btn-floating btn-large waves-effect waves-light green accent-3">
                                <i class="material-icons">favorite</i>
                            </button>'; ?>
                        </form>    
                    </div>
                    <div class="col s4 m4 l2"><h1/></div>
                    <div class="col s3 m3 l3">
                        <form class="center_dings" id="back" action="index.php" method="get" enctype="multipart/form-data">
                            <button value="" name="next" onclick="setCookie('<?php echo $id; ?>')" class="btn-floating btn-large waves-effect waves-light red accent-3 center_dings">
                                <i class="material-icons">delete</i>
                            </button>
                        </form>                        	
                    </div>
                    <div class="col s1 m1 l2"><h1/></div>
                </div>
               
                <div class="row"><div class="col s12 m12 l12"><p></p></div></div>
                
            </div>
        </div>
        <div id="popup" onclick="PopUpHide()" style=" width:100%;
                                        height: 2000px;
                                        background-color: rgba(0,0,0,0.5);
                                        overflow:hidden;
                                        position:fixed;
                                        top:0px;">
             <div id="ppc" style="margin:40px auto 0px auto;
                                            width:250px;
                                            height: auto;
                                            padding:10px;
                                            background-color: #c5c5c5;
                                            border-radius:5px;
                                            box-shadow: 0px 0px 10px #000;">
                 <div id="ppt" style="align:center;" ></div>

             </div>
          </div>
        <script type="text/javascript" src="https://code.jquery.com/jquery-2.1.1.min.js"></script>
        <script type="text/javascript" src="js/materialize.min.js"></script>
        <script>$(".button-collapse").sideNav();</script>
    </body>
</html>

