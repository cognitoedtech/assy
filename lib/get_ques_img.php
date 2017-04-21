<?php

$s = "UK and Norwegian officials say Algeria's military operation to end a hostage situation at has ended,\n with a reported 7 hostages killed during the final attack. UK and Norwegian officials say Algeria's military operation to end a hostage situation at has\n ended, with a reported 7 hostages killed during the final attack. UK and Norwegian officials say Algeria's military operation to end a hostage \nsituation at has ended, with a reported 7 hostages killed during the final attack. UK and Norwegian officials say Algeria's military operation to end a hostage situation at has ended, with a reported 7 hostages killed during the final attack. UK and Norwegian officials say Algeria's military operation to end a hostage situation at has ended, with a reported 7 hostages killed during the final attack. UK and Norwegian officials say Algeria's military operation to end a hostage situation at has ended, with a reported 7 hostages killed during the final attack.";

function sendimagetext($text) {
  // Set font size
  $font_size = 4;

  $ts=explode("\n",$text);
  $width=0;
  foreach ($ts as $k=>$string) { //compute width
    $width=max($width,strlen($string));
  }

  // Create image width dependant on width of the string
  $width  = imagefontwidth($font_size)*$width;
  // Set height to that of the font
  $height = imagefontheight($font_size)*count($ts);
  $el=imagefontheight($font_size);
  $em=imagefontwidth($font_size);
  // Create the image pallette
  $img = imagecreatetruecolor($width,$height);
  // Dark red background
  $bg = imagecolorallocate($img, 0xFF, 0xFF, 0xFF);
  imagefilledrectangle($img, 0, 0,$width ,$height , $bg);
  // White font color
  $color = imagecolorallocate($img, 0, 0, 0);

  foreach ($ts as $k=>$string) {
    // Length of the string
    $len = strlen($string);
    // Y-coordinate of character, X changes, Y is static
    $ypos = 0;
    // Loop through the string
    for($i=0;$i<$len;$i++){
      // Position of the character horizontally
      $xpos = $i * $em;
      $ypos = $k * $el;
      // Draw character
      imagechar($img, $font_size, $xpos, $ypos, $string, $color);
      // Remove character from string
      $string = substr($string, 1);      
    }
  }
  // Return the image
  header("Content-Type: image/png");
  imagepng($img);
  // Remove image
  imagedestroy($img);
}

 sendimagetext($s);
?>