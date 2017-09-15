<?php
function asciifyImage($img,$asciiscale,$asciicolor,$asciialpha,$asciiblock,$asciiinvert,$asciiresolution,$asciichars){
    $strChars = "";
    $strFont = "courier new";
    $aDefaultCharList = str_split(" .,:;i1tfLCG08@");
    $aDefaultColorCharList = str_split(" CGO08@");
    $iScale = $asciiscale?$asciiscale:1;
    $bColor = $asciicolor;
    $bAlpha = $asciialpha;
    $bBlock = $asciiblock;
    $bInvert = $asciiinvert;
    $strResolution = $asciiresolution?$asciiresolution:"low";
    $aCharList = $asciichars?$asciichars:($bColor ? $aDefaultColorCharList : $aDefaultCharList);
    $fResolution = 0.5;
    switch ($strResolution) {
        case "low" :     $fResolution = 0.25; break;
        case "medium" : $fResolution = 0.5; break;
        case "high" :     $fResolution = 1; break;
    }
    $ext = strtolower(pathinfo(trim($img))['extension']);
    switch($ext){
	case 'jpg':
	case 'jpeg':
    		$im = imagecreatefromjpeg($img);
		break;
	case 'gif':
    		$im = imagecreatefromgif($img);
		break;
	case 'png':
    		$im = imagecreatefrompng($img);
		break;
	case 'bmp':
		$im = imagecreatefromwbmp($img);
		break;
    }
    $iWidth = ceil(imagesx($im) * $fResolution);
    $iHeight = ceil(imagesy($im) * $fResolution);
    for($y=0;$y<$iHeight;$y+=2){
        for($x=0;$x<$iWidth;$x++){
            $color_index = imagecolorsforindex($im,imagecolorat($im, ceil($x/$fResolution), ceil($y/$fResolution)));
            $iRed = $color_index['red'];
            $iGreen = $color_index['green'];
            $iBlue = $color_index['blue'];
            $iAlpha = $color_index['alpha'];
            if ($iAlpha == 100) {
                $iCharIdx = 0;
            } else {
                $fBrightness = (0.3*$iRed + 0.59*$iGreen + 0.11*$iBlue) / 255;
                $iCharIdx = (count($aCharList)-1) - ceil($fBrightness * (count($aCharList)-1));
            }
            if ($bInvert) {
                $iCharIdx = (count($aCharList)-1) - $iCharIdx;
            }
            $strThisChar = $aCharList[$iCharIdx];
            if ($strThisChar == " ") 
                $strThisChar = " ";
            if ($bColor) {
                $strChars .= "<span style='"
                    . "color:rgb($iRed,$iGreen,$iBlue);"
                    . ($bBlock ? "background-color:rgb($iRed,$iGreen,$iBlue);" : "")
                    . ($bAlpha ? "opacity:" . ($iAlpha/255) . ";" : "")
                    . "'>" . $strThisChar . "</span>";
            } else {
                $strChars .= $strThisChar;
            }
        }
        $strChars .= "\r\n";
    }
    $fFontSize = (2/$fResolution)*$iScale;
    $fLineHeight = (2/$fResolution)*$iScale;
    $fLetterSpacing = 0;
    if ($strResolution == "low") {
        switch ($iScale) {
            case 1 : $fLetterSpacing = -1; break;
            case 2 : 
            case 3 : $fLetterSpacing = -2.1; break;
            case 4 : $fLetterSpacing = -3.1; break;
            case 5 : $fLetterSpacing = -4.15; break;
        }
    }
    if ($strResolution == "medium") {
        switch ($iScale) {
            case 1 : $fLetterSpacing = 0; break;
            case 2 : $fLetterSpacing = -1; break;
            case 3 : $fLetterSpacing = -1.04; break;
            case 4 : 
            case 5 : $fLetterSpacing = -2.1; break;
        }
    }
    if ($strResolution == "high") {
        switch ($iScale) {
            case 1 : 
            case 2 : $fLetterSpacing = 0; break;
            case 3 : 
            case 4 : 
            case 5 : $fLetterSpacing = -1; break;
        }
    }
    $width = ceil($iWidth/$fResolution)*$iScale;
    $height = ceil($iHeight/$fResolution)*$iScale;
    $style = "display:inline;width:$width px;height:$height px;white-space:pre;margin:0px;padding:0px;font:$strFont";
    $style .= "letter-spacing:$fLetterSpacing px;font-size:$fFontSize px;text-align:left;text-decoration:none";
    echo  $strChars;
}
if( $argc<=1 ){
    $fs = true; 
    do{
        if($fs){
            fwrite(STDOUT,'请输入图片文件名：');
            $fs = false;
        }else{
            fwrite(STDOUT,'抱歉，图片文件名不能为空，请重新输入图片文件名：');
        }     
        $filename = trim(fgets(STDIN)); 
    }while(!$filename);
    $px            =    1;
}else{
    $filename    =    $argv[1];
    $px            =    isset($argv[2]) ? $argv[2] : 1;
}
switch($px){
    case 1:    $pxval    =    'low';break;
    case 2: $pxval    =    'medium';break;
    case 3: $pxval    =    'high';break;
}

asciifyImage($filename, 3, false, 0, false, false, $pxval, null);