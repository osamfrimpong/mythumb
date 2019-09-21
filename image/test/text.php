<?php

require_once('../src/PHPImage.php');

$bg = './img/cert.jpg';
$image = new PHPImage();
$image->setDimensionsFromImage($bg);
$image->draw($bg);
$image->setFont('./font/cambriab.ttf');
$image->setTextColor(array(153, 0, 0));
$image->setStrokeWidth(0);
$image->setStrokeColor(array(0, 0, 0));
//$image->rectangle(40, 40, 120, 120, array(0, 0, 0), 0.5);
$image->textbox('PAPA KWESIS OPARE', array(
	'width' => 2096,
	'fontSize' => 45,
	'x' => 1109,
	'y' => 1220,
	'alignHorizontal' => 'center'
));



$image->show();