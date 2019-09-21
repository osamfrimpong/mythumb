<?php


function doImage($namemy,$position)
{
require_once('../src/PHPImage.php');

$bg = './img/pax.jpg';
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
	'y' => 150
));

$image->textBox(strtoupper($position), array(
	'width' => $image->getWidth() - $gutter * 2,
	'height' => $image->getHeight() - $gutter * 2,
	'fontSize' => 70,
	'x' => $gutter+30,
	'y' => 720
));
if
($image->save($path.$filename[0]."-".$filename[1].".jpg",false,true))
{return true;}
else{return false;}

}

if(isset($_POST['create']))
{
	$i = 0;
	$file = $_FILES['list']['tmp_name'];
	$handle = fopen($file, "r");
	while(($data = fgetcsv($handle,1000,",")) != FALSE)
{
	if($i++ > 0)
	{
	if(doImage($data[0],$data[1]))
	{
		echo "Created successfully for ".$data[0]."<br>";
	}
		else {
			echo "Could not Create for ".$data[0]."<br>";
		}}
	}
}
	




else{
echo "<form method='post' action='cert.php' enctype='multipart/form-data'>
<input type='file' name='list' value='' required /><input type='submit' name='create' value='create' /></form>";
}
