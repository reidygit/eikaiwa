<?
//Start a session so we can store the captcha code as a session variable. 
session_start();
$string="";
 // Decide what characters are allowed in our string // Our captcha will be case-insensitive, and we avoid some // characters like 'O' and 'l' that could confuse users 
 $charlist = '23456789ABCDEFGHJKMNPQRSTVWXYZ'; 
 // Trim string to desired number of characters - 5, say 
 $chars = 5; $i = 0; while ($i < $chars) { $string .= substr($charlist, mt_rand(0, strlen($charlist)-1), 1); $i++; } 
 // Create a GD image from our background image file 
 $captcha = imagecreatefrompng('captcha.png'); // Set the colour for our text string // This is chosen to be hard for machines to read against the background, but // OK for humans 
 $col = imagecolorallocate($captcha, 240, 200, 240); 
 // Write the string on to the image using TTF fonts 
 imagettftext($captcha, 17, 0, 13, 22, $col, 'Whimsy.TTF', $string); 
 // Store the random string in a session variable 
 $_SESSION['secret_string'] = $string; // Put out the image to the page 
 header("Content-type: image/png"); imagepng($captcha);

?>