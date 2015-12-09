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
        //var_dump($query);
        include("includes/ConectionOpen.php");
        $res = $conn->query($query);
        $offerID = $conn->insert_id;
        $query = "";// var_dump($_FILES);
        for($i = 1; $i <= $counter; $i++){
            if(isset($_FILES['files' . $i])){
                $file = addslashes(file_get_contents($_FILES['file' . $i]['tmp_name']));
                $query = " INSERT INTO images(offersid, image, insideid) 
                                    VALUES(". $offerID .", '". $file ."', ". $i .");";
                $conn->query($query);
                echo $query;
            }
            if(isset($_POST['txt' . $i])){
                $query = " INSERT INTO detailedtexts(offersid, detailledtext, insideid)
                                  VALUES(". $offerID .", '". $_POST['txt'.$i] ."', ". $i .") ;";
                $conn->query($query);
//                var_dump($query);
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
            function insertRow(){
                var table = document.getElementById("anzeige");
                var row = table.insertRow(-1);
                var cell = row.insertCell(-1);
                cell.setAttribute("id", counter++);
                cell.setAttribute("colspan", 2);
                return cell;
            }
            function insertList(){
                var cell = insertRow();

            }
            function insertText(){
                var cell = insertRow();
                cell.innerHTML = "<textarea name=txt" + counter + "></textarea>";

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
                                        '" title="', escape(theFile.name), '"/>'].join('');
                      document.getElementById('imgList').insertBefore(span, null);
                    };
                  })(f);

                  // Read in the image file as a data URL.
                  reader.readAsDataURL(f);
                }
              }
            
            function insertImg(){
                var cell = insertRow();
                var id = "file" + counter;
                cell.innerHTML = '<input onchange="" type="file" id="'+ id +'" name="file' + counter + '" multiple="multiple" />';
                document.getElementById(id).addEventListener('change', handleFileSelect, false);
                openFileDialog(id);
                cell.innerHTML = cell.innerHTML + '<output id="imgList"></output>';
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
                            <tr><td colspan="3"><input type="submit" value="Speichern" name="save" onclick="sendCounter()" /></td></tr>
                            <tr><td>Beschreibung:</td><td><input type="text" name="mainTitle"/></td></tr>
                            <tr><td>Preis (in €):</td><td><input type="text" name="price" /></td></tr>
                            <tr><td>Anzahl:</td><td><input type="text" name="amount" /></td></tr>
                            <tr><td>Titelbild:</td><td><input type="file" name="mainImage" /></td></tr>
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

