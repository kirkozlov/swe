<?php

function resize_image($file, $w, $h, $crop=FALSE) {
    list($width, $height) = getimagesize($file);
    $r = $width / $height;
    if ($crop) {
        if ($width > $height) {
            $width = ceil($width-($width*abs($r-$w/$h)));
        } else {
            $height = ceil($height-($height*abs($r-$w/$h)));
        }
        $newwidth = $w;
        $newheight = $h;
    } else {
        if (($w/$h > $r) && ($width > $w || $height > $h)) {
            $newwidth = $h*$r;
            $newheight = $h;
        } else if(($width > $w || $height > $h)) {
            $newheight = $w/$r;
            $newwidth = $w;
        } else {
			$newheight = $height;
            $newwidth = $width;
		}
    }
    $src = imagecreatefromjpeg($file);
    $dst = imagecreatetruecolor($newwidth, $newheight);
    imagecopyresampled($dst, $src, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);

		// Begin capturing the byte stream
        ob_start();

        // generate the byte stream
        imagejpeg($dst, NULL, 100);

        // and finally retrieve the byte stream
        $rawImageBytes = ob_get_clean();

        
	
    return $rawImageBytes;
}

?>