<?php
use writeOverImage\writeOverImage;

include('writeOverImage.php');

$image = new writeOverImage();

$image->backgroundImagePath = ''; // image path with trailing slash
$image->backgroundImage = 'img.jpg'; // image name
$image->stringColorRed = 0;
$image->stringColorGreen = 0;
$image->stringColorBlue = 0;
$image->fontSize = 300;
$image->stringAngle = 0;
$image->startX = 100;
$image->startY = 600;
$image->fontName = 'helvetica.ttf'; // other free fonts here: http://www.free-fonts-ttf.org/true-type-fonts/
$image->stringToWrite = 'test'; // UTF-8 encoded string
$image->newFile = true; // if false overwrite base file, if true create a new file with provided filename
$image->newFileName = 'test_file'; // just the filename. extension will be added automatically
$image->outputDirectly = false; // if true send image directly, if false save image file

$image->createImage();