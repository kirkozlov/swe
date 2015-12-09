<?php session_start(); 
	if(!$_SESSION['login']==true) {
		header("Location: index.php");
	}

    if(isset($_POST['save'])){
        $counter = $_GET['c'];
        $image = "";
        if(!empty($_FILES['mainImage']['tmp_name']))
            $image = addslashes(file_get_contents($_FILES['mainImage']['tmp_name']));
        $query = "";
        $query = $query."
                            INSERT INTO `swegehos_swe`.`offers`
                            (`userid`,`maintext`, `mainimage`,
                             `price`, `latitude`, `longtitude`,
                              `amount`)
                            VALUES(". $_SESSION['idu'] .", '". $_POST['mainTitle'] ."', '". $image ."', " . $_POST['price'] . ",
                                12,21, " . $_POST['amount'] . "
                            )
                        ;";
        //var_dump($_POST);
        include("includes/ConectionOpen.php");
        $res = $conn->query($query);
        $offerID = $conn->insert_id;
        $query = "";// var_dump($_FILES);
        for($i = 1; $i <= $counter; $i++){
            if(isset($_FILES['file' . $i])){
				//echo $_FILES['file' . $i]['tmp_name']. " " . $i . "<br />";
                $file = addslashes(file_get_contents($_FILES['file' . $i]['tmp_name']));
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
            
        $conn->close(); 
    }    

?>
<html>
    <head>
        <meta charset="utf-8"/>
        <meta name="viewport" content="width=device-width,initial-scale=1.0"/>
        <link rel="stylesheet" href="css/main.css" type="text/css" />
        <link rel="stylesheet" href="css/menu.css" type="text/css" />
        <link rel="stylesheet" href="css/additem.css" type="text/css" />
        <script language="javascript" type="text/javascript">
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
                                        '" title="', escape(theFile.name), '" style="max-width: 600px; max-height: 600px; width: auto; height: auto;" />'].join('');
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
					var tmp = table.rows[currentRow].innerHTML;
					table.rows[currentRow].innerHTML = row.innerHTML;
					table.rows[currentRow + 1].innerHTML = tmp;
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
					var tmp = table.rows[currentRow].innerHTML;
					table.rows[currentRow].innerHTML = row.innerHTML;
					table.rows[currentRow - 1].innerHTML = tmp;
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
				cell.innerHTML = cell.innerHTML + '<input type="button" onclick="return deleteElement(this)" value="del" />';
                cell.innerHTML = cell.innerHTML + "<textarea name='txt" + counter + "'></textarea>";
				cell.innerHTML = cell.innerHTML + '<input type="button" onclick="return elementUp(this)" value="up" />';
				cell.innerHTML = cell.innerHTML + '<input type="button" onclick="return elementDown(this)" value="down" />';
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
                                        '" title="', escape(theFile.name), '" style="max-width: 300px; max-height: 300px; width: auto; height: auto;" />'].join('');
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
				cell.innerHTML = '';
				cell.innerHTML = cell.innerHTML + '<input type="button" onclick="return deleteElement(this)" value="del" />';
				cell.innerHTML = cell.innerHTML + '<output id="imgOutput' + counter + '" ></output>';
                cell.innerHTML = cell.innerHTML + '<input hidden="hidden" onchange="" type="file" id="'+ id +'" name="file' + counter + '" />';
				cell.innerHTML = cell.innerHTML + '<input type="button" onclick="return elementUp(this)" value="up" />';
				cell.innerHTML = cell.innerHTML + '<input type="button" onclick="return elementDown(this)" value="down" />';
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
                            <tr><td colspan="2"><input type="submit" value="Speichern" name="save" onclick="sendCounter()" /></td></tr>
                            <tr><td>Beschreibung:</td><td><input type="text" name="mainTitle"/></td></tr>
                            <tr><td>Preis (in €):</td><td><input type="text" name="price" /></td></tr>
                            <tr><td>Anzahl:</td><td><input type="text" name="amount" /></td></tr>
                            <tr><td>Titelbild:</td><td><input id="mainImage" onclick="getElement(this)" onchange="" type="file" name="mainImage" /></td></tr>
							<tr><td colspan="2"><output id="mainOutput"><span id="spanMain"></span></output></td></tr>
                        </table>
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
    </body>
</html>

