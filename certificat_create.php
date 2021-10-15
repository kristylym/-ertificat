<?php
// ver 1.0 - 08.04.21

ini_set('max_execution_time', '1700');
set_time_limit(1700);


header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST');
header('Access-Control-Allow-Headers: application/json');


http_response_code(200);

global $globals;






/////////////////////////////////////////////////
////////////  F U N K T I O N S  /////////////////
/////////////////////////////////////////////////



///////////////////////////////

function send_picture($contactId){
global $globals;
	

$link = 'https://api.smartsender.eu/v1/contacts/'.$contactId.'/send';


$request = 'POST';	
		
$descriptor = curl_init($link);

$Data['type'] = 'picture';
$Data['watermark'] = '111';
$Data['media'] = $globals['full_url'].'/'.$contactId.'.png';


$globals['log'][] = $link;
$globals['log'][] = $Data['media'];


 $DataOK = json_encode($Data);
 curl_setopt($descriptor, CURLOPT_POSTFIELDS, $DataOK);
 curl_setopt($descriptor, CURLOPT_RETURNTRANSFER, 1);
 curl_setopt($descriptor, CURLOPT_HTTPHEADER, array('Content-Type: application/json','Authorization: Bearer '.$globals['smart_token'])); 
 curl_setopt($descriptor, CURLOPT_CUSTOMREQUEST, $request);

    $itog = curl_exec($descriptor);
    curl_close($descriptor);

   		 return $itog;
	
	
}

///////////////////////////////

function logfile(){ // записывает информацию в лог файл
global $globals;

$filename = 'log_botsend.txt';

file_put_contents($filename, date('Y-m-d H:i:s') . PHP_EOL, FILE_APPEND);
foreach($globals['log'] as $value){
file_put_contents($filename, $value . PHP_EOL, FILE_APPEND);
}
file_put_contents($filename, '-------------' . PHP_EOL, FILE_APPEND);


}


//**********************************



/////////////////////////////////////////////////
/////////////////////////////////////////////////
/////////////////////////////////////////////////


    $inputJSON = file_get_contents('php://input');
    $input = json_decode($inputJSON, TRUE); //convert JSON into array
    

if($input['userId']) {



$text1 = $input['text1'];
$text2 = $input['text2'];
$text3 = $input['text3'];

$userid = $input['userId'];
$globals['smart_token'] = $input['smart_token'];
$globals['full_url'] = $input['full_url'];
$text_vert1 = $input['text_vert1'];
$text_vert2 = $input['text_vert2'];
$text_vert3 = $input['text_vert3'];




{
"text1":"{{ ФИО }}",
"text2":"Баллы",
"text3":"Воронки продаж в мессенджерах",
"userId":"{{ userId }}",
"link_main_img":"https://drive.google.com/uc?id=1csbpIszdxxsGLGIUOdDxjNMxm5pT1xMp",
"full_url":"https://github.com/kristylym/-ertificat",
"smart_token":"i3YxGE2ICTdMG47El8Y7ipcH9bC3uH5IQfete8W4DchSOsiwfcUvhMVuffc5",
"text_vert1":"342",
"text_vert2":"150",
"text_vert3":"270",
"font_size1":"24",
"font_size2":"12",
"font_size3":"18",
"font_1_color1":"100",
"font_1_color2":"0",
"font_1_color3":"0",
"font_2_color1":"50",
"font_2_color2":"50",
"font_2_color3":"50",
"font_3_color1":"50",
"font_3_color2":"50",
"font_3_color3":"50"
}





//********************************************************************

// Подключаем класс для работы с изображением
include_once 'LImageHandler.php';

// Подключаем выбранный шрифт текста
$fontPath1 = 'Montserrat-SemiBold.ttf';
$fontPath2 = 'Montserrat-Light.ttf';
$fontPath3 = 'Montserrat-Light.ttf';

// Путь к оригинальному изображению
$imagePath = $input['link_main_img'];

// Указываем размер шрифта 
$fontSize1 = $input['font_size1'];
$fontSize2 = $input['font_size2'];
$fontSize3 = $input['font_size3'];

// Задаем цвет
$colorArray1 = array($input['font_1_color1'], $input['font_1_color2'], $input['font_1_color3']);
$colorArray2 = array($input['font_2_color1'], $input['font_2_color2'], $input['font_2_color3']);
$colorArray3 = array($input['font_3_color1'], $input['font_3_color2'], $input['font_3_color3']);

// Создаем экземпляр класса LImageHandler
$ih = new LImageHandler;

// Загружаем изображение
$imgObj = $ih->load($imagePath);



// Выполняем наложение текста на изображение
$imgObj->text($text1, $fontPath1, $fontSize1, $colorArray1, LImageHandler::CORNER_CENTER_TOP, 0, $text_vert1);
$imgObj->text($text2, $fontPath2, $fontSize2, $colorArray2, LImageHandler::CORNER_CENTER_TOP, 0, $text_vert2);
$imgObj->text($text3, $fontPath3, $fontSize3, $colorArray3, LImageHandler::CORNER_CENTER_TOP, 0, $text_vert3);
//$imgObj->text('ментор Иванов', $fontPath, $fontSize2, $colorArray, LImageHandler::CORNER_RIGHT_BOTTOM, 50, 50);


$imgObj->save($userid.'.png', LImageHandler::IMG_PNG, 100, false);

//$imgObj->show(false, 100);

$answer = send_picture($userid);

unlink($userid.'.png');





$globals['log'][] = $answer;

// https://docs.google.com/uc?id=1SJp36buuCtUNsxeYL6lWb80mFfLuEzQW

// https://telegra.ph/Generaciya-izobrazhenij-s-tekstom-na-PHP-04-08

}



logfile();

?>
