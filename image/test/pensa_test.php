<?php


function doImage($namemy)
{
require_once('../src/PHPImage.php');

//putenv('GDFONTPATH='.realpath('.'));

$font = "cambriab.TTF";
$bg = './img/finalistcert_2019_borderless.jpg';
$path = "saved/";
//$overlay = './img/paw.png';
$image = new PHPImage();
$image->setDimensionsFromImage($bg);
$image->draw($bg);
//$image->resize(486, 540, true, true);
//$image->draw($overlay);
$image->setAlignHorizontal('center');
$image->setAlignVertical('center');
$image->setFont('./font/cambriab.ttf');
$image->setTextColor(array(153, 0, 0));
$image->setStrokeWidth(0);
$image->setStrokeColor(array(0, 0, 0));
$gutter = 50;
//$image->rectangle($gutter, $gutter, $image->getWidth() - $gutter * 2, $image->getHeight() - $gutter * 2, array(255, 255, 255), 0.5);
$filename = preg_split("/\s+/", $namemy);
	
$image->textBox(strtoupper($namemy), array(
	'width' => $image->getWidth() - $gutter * 2,
	'height' => $image->getHeight() - $gutter * 2,
	'fontSize' => 90,
	'x' => $gutter+30,
	'y' => 42
));


if
($image->save($path.$filename[0]."-".$filename[1].".jpg",false,true))
{return true;}
else{return false;}

}

doImage("Kofi Mensah Andoh");
	





