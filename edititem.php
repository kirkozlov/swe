<?php session_start(); 
	if(!$_SESSION['login']==true) {
		header("Location: index.php");
	}
	$error = "";
	
	$offerID = "";
	
	if(!isset($_POST['edit'])){
		if($_POST['save'])
			$offerID = $_POST['offerID'];
		else
			header("Location: index.php");
	}
	else{
		$offerID = $_POST['edit'];
	}
	include_once("includes/imgResize.php");
	include_once("includes/ConectionOpen.php");
	
    if(isset($_POST['save'])) {
        $counter = $_GET['c'];
        $image = "";
        if(!empty($_FILES['mainImage']['tmp_name']))
            $image = addslashes(resize_image($_FILES['mainImage']['tmp_name'], 600, 600));//addslashes(file_get_contents($_FILES['mainImage']['tmp_name']));
        $query = "";
		$price = $_POST['price'];
		$amount = $_POST['amount'];

		if (true || !isset($error)) {
/*	        $query = $query."
		                INSERT INTO `swegehos_swe`.`offers`
		                (`userid`,`maintext`, `mainimage`,
		                 `price`, `latitude`, `longtitude`,
		                  `amount`)
		                VALUES(". $_SESSION['idu'] .", '". $_POST['mainTitle'] ."', '". $image ."', " . $price . ",
		                    ". $_POST['txtLat'] .",". $_POST['txtLng'] .", " . $amount . "
		                )
		            ;";
*/
			
			$query = "UPDATE `offers` 
						SET `maintext` = '". $_POST["mainTitle"] ."', 
						`price` = '". $price ."', 
						`latitude` = '". $_POST['txtLat'] ."', 
						`longtitude` = '". $_POST['txtLng'] ."', 
						`amount` = '". $amount ."' ";
			if($image != "")
				$query = $query .", `mainimage` = '". $image ."' ";
			
			$query = $query ."WHERE `offers`.`id` = ". $_POST["offerID"] .";";
			
			$res = $conn->query($query);
			
			$query = "UPDATE `offers_tags` 
						 SET `tagsid` = '". $_POST['kat'] ."' 
					   WHERE `offers_tags`.`offersid` = ". $offerID .";";
			$conn->query($query);
/*/			echo $query;
			$query = "";// var_dump($_FILES);
			for($i = 1; $i <= $counter; $i++){
				if(isset($_FILES['file' . $i]) && !empty($_FILES['file' . $i]['tmp_name'])){
					//echo $_FILES['file' . $i]['tmp_name']. " " . $i . "<br />";
				    $file = addslashes(resize_image($_FILES['file' . $i]['tmp_name'],300,300));//addslashes(file_get_contents($_FILES['file' . $i]['tmp_name']));
				    $query = " INSERT INTO images(offersid, image, insideid) 
				                        VALUES(". $offerID .", '". $file ."', ". $i .");";
				    $conn->query($query);
				    //echo $query;
				}
				if(isset($_POST['txt' . $i])){
					//echo "somethingTXT" .$i;
				    $query = " INSERT INTO detailedtexts(offersid, detailledtext, insideid)
				                      VALUES(". $offerID .", '". $_POST['txt'.$i] ."', ". $i .") ;";
				    $conn->query($query);
		//                var_dump($query);
					//echo $query;
				}
			}
			$query = "INSERT INTO `offers_tags` (`id`, `offersid`, `tagsid`) VALUES (NULL, '".$offerID."', '".$_POST['kat']."');";
			$conn->query($query);
*/
		}
    }
	
	$query = "SELECT * FROM `offers` WHERE id = ". $offerID;
	$res = $conn->query($query);

	$offer;

	for($i = 0;$row = $res->fetch_row(); $i++){
		$offer[0] = $row[0];
		$offer[1] = $row[1];
		$offer[2] = $row[2];
		$offer[3] = $row[3];
		$offer[4] = $row[4];
		$offer[5] = $row[5];
		$offer[6] = $row[6];
		$offer[7] = $row[7];
		$offer[8] = $row[8];
		$offer[9] = $row[9];
	}
	
	$query = "SELECT * FROM `offers_tags` WHERE offersid =". $offerID;
	$res = $conn->query($query);

	$offerKat;

	for($i = 0;$row = $res->fetch_row(); $i++){
		$offerKat[0] = $row[0];
		$offerKat[1] = $row[1];
		$offerKat[2] = $row[2];
	}
	
	$query = "SELECT * FROM `detailedtexts` WHERE offersid = ". $offerID ." ORDER BY 4";
	$res = $conn->query($query);

	$elements = false;
	$elemCounter = 0;

	while($row = $res->fetch_row()){
		$elements[$row[3]][0] = $row[0];
		$elements[$row[3]][1] = $row[1];
		$elements[$row[3]][2] = $row[2];
		$elements[$row[3]][3] = $row[3];
		$elements[$row[3]]["img"] = false;
		if($elemCounter < $elements[$row[3]][3]){
			$elemCounter = $elements[$row[3]][3];
		}
	}
	
	$query = "SELECT * FROM images WHERE offersid = ". $offerID ." ORDER BY 4";
	$res = $conn->query($query);
	
	while($row = $res->fetch_row()){
		$elements[$row[3]][0] = $row[0];
		$elements[$row[3]][1] = $row[1];
		$elements[$row[3]][2] = $row[2];
		$elements[$row[3]][3] = $row[3];
		$elements[$row[3]]["img"] = true;
		if($elemCounter < $elements[$row[3]][3]){
			$elemCounter = $elements[$row[3]][3];
		}
	}
	
	$query = "SELECT * FROM `tags` ORDER BY 2";
	$res = $conn->query($query);
	$katList;

	for($i = 0;$row = $res->fetch_row(); $i++){
		$katList[$i][0] = $row[0];
		$katList[$i][1] = $row[1];
	}
	
	$conn->close(); 

?>
<html>
    <head>
        <meta charset="utf-8"/>
        <meta name="viewport" content="width=device-width,initial-scale=1.0"/>
        <link rel="stylesheet" href="css/main.css" type="text/css" />
        <link rel="stylesheet" href="css/menu.css" type="text/css" />
        <link rel="stylesheet" href="css/additem.css" type="text/css" />
		<script src="includes/jquery.js"></script>
		<script src="includes/googleMap.js"></script>
		<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAAizLFKOw4W4Pb7juAOcSpUR6t41c_yQY&libraries=places&callback=initAutocomplete" async defer></script>
        <script language="javascript" type="text/javascript">
		
			//setLocation(document.getElementById("txtLat").value, document.getElementById("txtLng").value);
		
			$(document).ready(function(){
			    PopUpHide();	
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

			//var changed = [false, false, false, false]; //[0] = title, [1] = price, [2] = amount, [3] = img
			
			function getErrors(){
				var errorMainText = document.getElementById("errorMainText");
				var errorPrice = document.getElementById("errorPrice");
				var errorAmount = document.getElementById("errorAmount");
				var errorMainImg = document.getElementById("errorMainImg");
				var errorLat = document.getElementById("txtLat").value;
				var errorLng = document.getElementById("txtLng").value;
				if( errorMainText.style.visibility == "visible" ||
					errorPrice.style.visibility == "visible" ||
					errorAmount.style.visibility == "visible" ||
					errorMainImg.style.visibility == "visible" // ||
					//!changed[0] || !changed[1] || !changed[2] || !changed[3]
					) {
						document.getElementById('ppt').innerHTML='Bitte alle Pflichtfelder ausfüllen: Beschreibung, Preis, Anzahl und Bild.';
						PopUpShow();
						return false;
					}
				else if(!errorLat || !errorLng){
					document.getElementById('ppt').innerHTML='Bitte einen Ort in der Karte eingeben.';
					PopUpShow();
					return false;
				}
				else{
					sendCounter();
					return true;
				}
			}
			function checkErrors(elem){
				switch (elem.name){
					case "mainTitle":
						var errorMainText = document.getElementById("errorMainText");
						if(elem.value.length > 100){
							errorMainText.style.visibility = "visible";
							document.getElementById('ppt').innerHTML='Der Titel darf nicht länger als 100 Zeichen sein.';
							PopUpShow();
						}
						else if(!elem.value.trim()){
							errorMainText.style.visibility = "visible";
							document.getElementById('ppt').innerHTML='Der Titel darf nicht leer sein.';
							PopUpShow();
						}
						else{
							errorMainText.style.visibility = "hidden";
						}
					break;
					
					case "price":
						var errorPrice = document.getElementById("errorPrice");
						if(isNaN(elem.value)){
							errorPrice.style.visibility = "visible";
							document.getElementById('ppt').innerHTML='Bitte eine Zahl in der Form \"12.34\" für den Preis eingeben!';
							PopUpShow();
						}
						else if(!elem.value.trim()){
							errorPrice.style.visibility = "visible";
							document.getElementById('ppt').innerHTML='Preis darf nicht leer sein.';
							PopUpShow();
						}
						else{
							errorPrice.style.visibility = "hidden";
						}
					break;
					
					case "amount":
						var errorAmount = document.getElementById("errorAmount");
						if(!(elem.value == parseInt(elem.value, 10))){
							errorAmount.style.visibility = "visible";
							document.getElementById('ppt').innerHTML='Bitte eine ganze Zahl für die Anzahl eingeben!';
							PopUpShow();
						}
						else{
							errorAmount.style.visibility = "hidden";
						}
					break;
					
					case "mainImage":
						var errorMainImg = document.getElementById("errorMainImg");
						if(!elem.value.trim()){
							errorMainImg.style.visibility = "visible";
						}
						else{
							errorMainImg.style.visibility = "hidden";
						}
					break;
				}
			}
			
            var counter = 0;
			var imgTmp = "";
			
			function handleMainSelect(evt){
					var files = evt.target.files; // FileList object

                // Loop through the FileList and render image files as thumbnails.
                for (var i = 0, f; f = files[i]; i++) {

                  // Only process image files.
                  if (!f.type.match('image.*')) {
                    continue;
                  }

                  var reader = new FileReader();

                  // Closure to capture the file information.
                  reader.onload = (function(theFile) {
                    return function(e) {
                      // Render thumbnail.
                      //var span = document.createElement('span');
                      imgTmp.innerHTML = ['<img name="img" class="thumb" src="', e.target.result,
                                        '" title="', escape(theFile.name), '" style="max-width: 200px; max-height: 200px; width: auto; height: auto;" />'].join('');
                      document.getElementById('mainOutput').insertBefore(imgTmp, null);
                    };
                  })(f);

                  // Read in the image file as a data URL.
                  reader.readAsDataURL(f);
                }
            }
			
			function getElement(elem){
				elem.addEventListener('change', handleMainSelect, false);
				imgTmp = document.getElementById('spanMain');
			}
            
            function openFileDialog(elemId) {
               var elem = document.getElementById(elemId);
               if(elem && document.createEvent) {
                  var evt = document.createEvent("MouseEvents");
                  evt.initEvent("click", true, false);
                  elem.dispatchEvent(evt);
               }
            }
            
            function showItems(){
                var list = document.getElementById("elementList");
                if(list.style.display == "none"){
                    list.style.display = "block";
                }
                else{
                    list.style.display = "none";
                }
            }
			
			function deleteElement(elem){
				var row = elem.parentNode.parentNode;
//				alert(row);
				if(input = row.getElementsByTagName('img')[0]){
					var id = input.getAttribute("name");
					id = id.substring(3,id.length);
					var img = document.getElementById("file"+id);
					var images = document.getElementById("images");
					images.removeChild(img);
				}
				row.parentNode.removeChild(row);
				return false;
			}
			
			function elementUp(elem){
				var row = elem.parentNode.parentNode;
				var table = row.parentNode.parentNode;//document.getElementById("anzeige");
				//alert(table);
				var currentRow = 6;
				var tmp = 0;
				for(i = 7; r = table.rows[i]; i++ ){
					//alert(r);
					if(r == row){ tmp = i; break;}
					currentRow = i;
				}
				//alert(table.rows[currentRow]);
				if((currentRow + 1) > 6 && tmp > 0){
					//alert(table.rows[currentRow].innerHTML);
					var textOben;
					var textUnten;
					var imgOben;
					var imgUnten;
					
					if(ta = table.rows[currentRow].getElementsByTagName('textarea')[0]) {
						textOben = [ta, ta.value, ta.getAttribute("name")];
						textOben[2] = textOben[2].substring(3,textOben[2].length);
					}
					if(ta = table.rows[currentRow + 1].getElementsByTagName('textarea')[0]) {
						textUnten = [ta, ta.value, ta.getAttribute("name")];
						textUnten[2] = textUnten[2].substring(3,textUnten[2].length);
					}
					if(input = table.rows[currentRow].getElementsByTagName('img')[0]) {
						imgOben = [input, input.getAttribute("name")];
						imgOben[1] = imgOben[1].substring(3,imgOben[1].length);
					}
					if(input = table.rows[currentRow + 1].getElementsByTagName('img')[0]) {
						imgUnten = [input, input.getAttribute("name")];
						imgUnten[1] = imgUnten[1].substring(3,imgUnten[1].length);
					}
					if(textOben && textUnten){
						table.rows[currentRow + 1].getElementsByTagName('textarea')[0].value = textOben[1];
						table.rows[currentRow].getElementsByTagName('textarea')[0].value = textUnten[1];
					}
					if(textOben && imgUnten){
						var tmp = table.rows[currentRow].innerHTML;
						table.rows[currentRow].innerHTML = table.rows[currentRow + 1].innerHTML;
						table.rows[currentRow + 1].innerHTML = tmp;
						imgUnten[0] = table.rows[currentRow].getElementsByTagName('img')[0];
						var file = document.getElementById("file" + imgUnten[1]);
						file.setAttribute("name", "file" + textOben[2]);
						file.setAttribute("id", "file" + textOben[2]);
						imgUnten[0].setAttribute("name", "img" + textOben[2]);
						textOben[0] = table.rows[currentRow + 1].getElementsByTagName('textarea')[0];
						textOben[0].value = textOben[1];
						textOben[0].setAttribute("name","txt"+imgUnten[1]);
					}
					if(imgOben && textUnten){
						var tmp = table.rows[currentRow].innerHTML;
						table.rows[currentRow].innerHTML = table.rows[currentRow + 1].innerHTML;
						table.rows[currentRow + 1].innerHTML = tmp;
						imgOben[0] = table.rows[currentRow + 1].getElementsByTagName('img')[0];
						var file = document.getElementById("file" + imgOben[1]);
						file.setAttribute("name", "file" + textUnten[2]);
						file.setAttribute("id", "file" + textUnten[2]);
						imgOben[0].setAttribute("name", "img" + textUnten[2]);
						textUnten[0] = table.rows[currentRow].getElementsByTagName('textarea')[0];
						textUnten[0].value = textUnten[1];
						textUnten[0].setAttribute("name","txt"+imgOben[1]);
					}
					if(imgOben && imgUnten){
						var tmp = table.rows[currentRow].innerHTML;
						table.rows[currentRow].innerHTML = table.rows[currentRow + 1].innerHTML;
						table.rows[currentRow + 1].innerHTML = tmp;
						imgOben[0] = table.rows[currentRow + 1].getElementsByTagName('img')[0];
						imgUnten[0] = table.rows[currentRow].getElementsByTagName('img')[0];
						imgOben[0].setAttribute("name", "img" + imgUnten[1]);
						imgUnten[0].setAttribute("name", "img" + imgOben[1]);
						var fileOben = document.getElementById("file" + imgOben[1]);
						var fileUnten = document.getElementById("file" + imgUnten[1]);
						fileOben.setAttribute("name", "file" + imgUnten[1]);
						fileOben.setAttribute("id", "file" + imgUnten[1]);
						fileUnten.setAttribute("name", "file" + imgOben[1]);
						fileUnten.setAttribute("id", "file" + imgOben[1]);
					}
				}
				
				return false;
			}
			
			function elementDown(elem){
				var row = elem.parentNode.parentNode;
				var table = row.parentNode.parentNode;//document.getElementById("anzeige");
				//alert(table);
				var currentRow = table.rows.length;
				var tmp = 0;
				for(i = table.rows.length - 1; r = table.rows[i]; i-- ){
					//alert(r);
					if(r == row){ tmp = i; break;}
					currentRow = i;
				}
				//alert(table.rows[currentRow]);
				if((currentRow) > 6 && tmp < table.rows.length - 1){
					//alert(table.rows[currentRow].innerHTML);
					var textOben;
					var textUnten;
					var imgOben;
					var imgUnten;
					
					if(ta = table.rows[currentRow - 1].getElementsByTagName('textarea')[0]) {
						textOben = [ta, ta.value, ta.getAttribute("name")];
						textOben[2] = textOben[2].substring(3,textOben[2].length);
					}
					if(ta = table.rows[currentRow].getElementsByTagName('textarea')[0]) {
						textUnten = [ta, ta.value, ta.getAttribute("name")];
						textUnten[2] = textUnten[2].substring(3,textUnten[2].length);
					}
					if(input = table.rows[currentRow - 1].getElementsByTagName('img')[0]) {
						imgOben = [input, input.getAttribute("name")];
						imgOben[1] = imgOben[1].substring(3,imgOben[1].length);
					}
					if(input = table.rows[currentRow].getElementsByTagName('img')[0]) {
						imgUnten = [input, input.getAttribute("name")];
						imgUnten[1] = imgUnten[1].substring(3,imgUnten[1].length);
					}
					if(textOben && textUnten){
						table.rows[currentRow].getElementsByTagName('textarea')[0].value = textOben[1];
						table.rows[currentRow - 1].getElementsByTagName('textarea')[0].value = textUnten[1];
					}
					if(textOben && imgUnten){
						var tmp = table.rows[currentRow - 1].innerHTML;
						table.rows[currentRow - 1].innerHTML = table.rows[currentRow].innerHTML;
						table.rows[currentRow].innerHTML = tmp;
						imgUnten[0] = table.rows[currentRow - 1].getElementsByTagName('img')[0];
						var file = document.getElementById("file" + imgUnten[1]);
						file.setAttribute("name", "file" + textOben[2]);
						file.setAttribute("id", "file" + textOben[2]);
						imgUnten[0].setAttribute("name", "img" + textOben[2]);
						textOben[0] = table.rows[currentRow].getElementsByTagName('textarea')[0];
						textOben[0].value = textOben[1];
						textOben[0].setAttribute("name","txt"+imgUnten[1]);
					}
					if(imgOben && textUnten){
						var tmp = table.rows[currentRow - 1].innerHTML;
						table.rows[currentRow - 1].innerHTML = table.rows[currentRow].innerHTML;
						table.rows[currentRow].innerHTML = tmp;
						imgOben[0] = table.rows[currentRow].getElementsByTagName('img')[0];
						var file = document.getElementById("file" + imgOben[1]);
						file.setAttribute("name", "file" + textUnten[2]);
						file.setAttribute("id", "file" + textUnten[2]);
						imgOben[0].setAttribute("name", "img" + textUnten[2]);
						textUnten[0] = table.rows[currentRow - 1].getElementsByTagName('textarea')[0];
						textUnten[0].value = textUnten[1];
						textUnten[0].setAttribute("name","txt"+imgOben[1]);
					}
					if(imgOben && imgUnten){
						var tmp = table.rows[currentRow - 1].innerHTML;
						table.rows[currentRow - 1].innerHTML = table.rows[currentRow].innerHTML;
						table.rows[currentRow].innerHTML = tmp;
						imgOben[0] = table.rows[currentRow].getElementsByTagName('img')[0];
						imgUnten[0] = table.rows[currentRow - 1].getElementsByTagName('img')[0];
						imgOben[0].setAttribute("name", "img" + imgUnten[1]);
						imgUnten[0].setAttribute("name", "img" + imgOben[1]);
						var fileOben = document.getElementById("file" + imgOben[1]);
						var fileUnten = document.getElementById("file" + imgUnten[1]);
						fileOben.setAttribute("name", "file" + imgUnten[1]);
						fileOben.setAttribute("id", "file" + imgUnten[1]);
						fileUnten.setAttribute("name", "file" + imgOben[1]);
						fileUnten.setAttribute("id", "file" + imgOben[1]);
					}
				}
				
				return false;
			}
			
            function insertRow(){
                var table = document.getElementById("anzeige");
                var row = table.insertRow(-1);
				row.setAttribute("id", counter)
                var cell = row.insertCell(-1);
                cell.setAttribute("id", counter++);
                cell.setAttribute("colspan", 2);
                return cell;
            }
            function insertText(){
                var cell = insertRow();
				cell.innerHTML = cell.innerHTML + '<input type="button" onclick="return deleteElement(this)" value="Löschen" />';
                cell.innerHTML = cell.innerHTML + "<textarea name='txt" + counter + "'></textarea>";
				cell.innerHTML = cell.innerHTML + '<input type="button" onclick="return elementUp(this)" value="Hoch" />';
				cell.innerHTML = cell.innerHTML + '<input type="button" onclick="return elementDown(this)" value="Runter" />';
				return false;
            }
            
            function handleFileSelect(evt) {
                var files = evt.target.files; // FileList object

                // Loop through the FileList and render image files as thumbnails.
                for (var i = 0, f; f = files[i]; i++) {

                  // Only process image files.
                  if (!f.type.match('image.*')) {
                    continue;
                  }

                  var reader = new FileReader();

                  // Closure to capture the file information.
                  reader.onload = (function(theFile) {
                    return function(e) {
                      // Render thumbnail.
                      var span = document.createElement('span');
                      span.innerHTML = ['<img name="img' + counter++ + '" class="thumb" src="', e.target.result,
                                        '" title="', escape(theFile.name), '" style="max-width: 100px; max-height: 100px; width: auto; height: auto;" />'].join('');
                      document.getElementById('imgOutput' + (counter - 1)).insertBefore(span, null);
                    };
                  })(f);

                  // Read in the image file as a data URL.
                  reader.readAsDataURL(f);
                }
              }
            
            function insertImg(){
                var cell = insertRow();
                var id = "file" + counter;
				var images = document.getElementById("images");
				cell.innerHTML = '';// hidden="hidden"
				cell.innerHTML = cell.innerHTML + '<input type="button" onclick="return deleteElement(this)" value="Löschen" />';
				cell.innerHTML = cell.innerHTML + '<output id="imgOutput' + counter + '" ></output>';
				var img = document.createElement("input");
				img.setAttribute("onchange","");
				img.setAttribute("type","file");
				img.setAttribute("id",id);
				img.setAttribute("name","file"+counter);
				img.setAttribute("accept","image/*" );
				images.insertBefore(img,null);
                //cell.innerHTML = cell.innerHTML + '<input onchange="" type="file" id="'+ id +'" name="file' + counter + '" />';
				cell.innerHTML = cell.innerHTML + '<input type="button" onclick="return elementUp(this)" value="Hoch" />';
				cell.innerHTML = cell.innerHTML + '<input type="button" onclick="return elementDown(this)" value="Runter" />';
                document.getElementById(id).addEventListener('change', handleFileSelect, false);
                openFileDialog(id);
				
                
                //cell.innerHTML = cell.innerHTML + '<img src="' + document.getElementById(id). + '" />';
            }
            function sendCounter(){
                var form = document.getElementById("saveForm");
                form.setAttribute("action", form.getAttribute("action") + '?c=' + counter);
            }            
        </script>
    </head>
    <body>
        <?php
            include("includes/menu.php");
        ?>
        <div class="main">
            <div class="content">
                <div class="additem">
                    <form id="saveForm" action="" method="post" enctype="multipart/form-data" >
                        <table class="anzeige" id="anzeige">
                            <tr><td colspan="3">
									<input type="submit" value="Speichern" name="save" onclick="return getErrors();" />
									<input type="text" name="txtLat" id="txtLat" hidden="hidden" value="<?php echo $offer[5]; ?>" />
									<input type="text" name="txtLng" id="txtLng" hidden="hidden" value="<?php echo $offer[6]; ?>" />
									<input type="text" name="offerID" id="offerID" hidden="hidden" value="<?php echo $offer[0]; ?>" />
								</td>
							</tr>
                            <tr><td>Beschreibung:</td><td><input type="text" name="mainTitle" onblur="checkErrors(this);" value="<?php echo $offer[3]; ?>" /><img id="errorMainText" style="height: 20px; width:20px; visibility: hidden;" src="images/err.png" ></td></tr>
                            <tr><td>Preis (in €):</td><td><input  value="<?php echo $offer[4]; ?>" type="text" name="price" onblur="checkErrors(this);" /><img id="errorPrice" style="height: 20px; width:20px; visibility: hidden;" src="images/err.png" ></td></tr>
                            <tr><td>Anzahl:</td><td><input  value="<?php echo $offer[9]; ?>" type="text" name="amount" onblur="checkErrors(this);" /><img id="errorAmount" style="height: 20px; width:20px; visibility: hidden;" src="images/err.png" ></td></tr>
							<tr><td>Kategorie:</td>
								<td>
									<select name="kat">
										<?php 
										var_dump($katList);
											foreach($katList as $kat){
												if($kat[0] == $offerKat[2]){
													echo "<option selected='selected' value='$kat[0]'>$kat[1]</option>";
													continue;
												}
												echo "<option value='$kat[0]'>$kat[1]</option>"; 
											}
										?>
									</select>
								</td>
							<tr><input id="pac-input" class="controls" type="text" placeholder="Search Box"><td colspan="3"><div id="map" style="width: 100%; height: 200px;" ></div></td></tr>
                            <tr><td>Titelbild:</td><td><input id="mainImage" onclick="getElement(this)" onchange="" type="file" accept="image/*" name="mainImage" onblur="checkErrors(this);" /><img id="errorMainImg" style="height: 20px; width:20px; visibility: hidden;" src="images/err.png" ></td></tr>
							<tr><td colspan="2"><output id="mainOutput"><span id="spanMain">
								<img name="img" class="thumb" src="data:image/jpeg;base64,<?php echo base64_encode($offer[2]);
								?>" title="%5Bwall001.com%5D_blood_rayne_2017_1024.jpg" style="max-width: 200px; max-height: 200px; width: auto; height: auto;">
							</span></output></td></tr>
							<?php 
								for($i = 0;$i <= $elemCounter;$i++){
									if(isset($elements[$i])){
										echo "<tr id = ". ($i - 1) ."><td colspan ='3' id = ". ($i - 1) . ">
											  <input type='button' onclick='return deleteElement(this)' value='Löschen'>";
											if($elements[$i]["img"]){
												echo "<img name=$i src='data:image/jpeg;base64,". base64_encode($elements[$i][2]) ."' class='thumb' style='max-width: 100px; max-height: 100px; width: auto; height: auto;' />";
											}
											else{
												echo "<textarea name='txt$i'>". $elements[$i][2] ."</textarea>";
											}
										echo '<input type="button" onclick="return elementUp(this)" value="Hoch">
										      <input type="button" onclick="return elementDown(this)" value="Runter">';
										echo "</td></tr>";
									}
								}
							?>
                        </table>
						<span id ="images" hidden="hidden"></span>
                    </form>
                    <ul class="elementList" id="elementList" style="display: none;">
                        <!--li><button onclick="insertList()">Liste</button></li-->
                        <li><button onclick="insertText()">Text</button></li>
                        <li><button onclick="insertImg()">Bild</button></li>
                    </ul>
                    <button onclick="showItems()">Element hinzuf&uuml;gen</button>
                </div>
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
												height: 100px;
												padding:10px;
												
												background-color: #c5c5c5;
												border-radius:5px;
												box-shadow: 0px 0px 10px #000;">
				<div id="ppt" style="align:center;" ></div>
			
			</div>
		</div>
    </body>
</html>