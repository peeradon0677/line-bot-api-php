<?php

$API_URL = 'https://api.line.me/v2/bot/message/reply';
$ACCESS_TOKEN = 'qLPxoBjrV76IYF19JEIqQp39xqgB8vrV74gwcJfR7zs9KTL+CKrD/m8Y25FjOc8mB0r0W3XqSpSM5phUARXTLeemwgmAV7gADYSR74fy8f1YIyHJSZH7POeiUqlmZYHJwAOLcZHUasVZHsBgSRcEGwdB04t89/1O/w1cDnyilFU='; // Access Token ค่าที่เราสร้างขึ้น
$POST_HEADER = array('Content-Type: application/json', 'Authorization: Bearer ' . $ACCESS_TOKEN);

$request = file_get_contents('php://input');   // Get request content
$request_array = json_decode($request, true);   // Decode JSON to Array

if ( sizeof($request_array['events']) > 0 )
{

 foreach ($request_array['events'] as $event)
 {
  $reply_message = '';
  $reply_token = $event['replyToken'];

  if ( $event['type'] == 'message' ) 
  {
   
   if( $event['message']['type'] == 'text' )
   {
		$text = $event['message']['text'];
		
		if(($text == "อุณหภูมิตอนนี้")||($text == "อุณหภูมิวันนี้")||($text == "อุณหภูมิ")){
			$temp = 27;
			$reply_message = 'ขณะนี้อุณหภูมิที่ '.$temp.'°C องศาเซลเซียส';
		}
		else if(($text== "อยากทราบยอด COVID-19 ครับ")||($text== "อยากทราบยอด COVID-19 ค่ะ")||($text== "COVID-19")){
			$reply_message = '
"รายงานสถานการณ์ ยอดผู้ติดเชื้อไวรัสโคโรนา 2019 
(COVID-19) ในประเทศไทย"
ผู้ป่วยสะสม  จำนวน 398,995 ราย
ผู้เสียชีวิต   จำนวน 17,365 ราย
รักษาหาย   จำนวน 103,753 ราย
ผู้รายงานข้อมูล: นายพีรดนย์ ก่อเกิด';
		}else if(($text== "ข้อมูลส่วนตัวของผู้พัฒนาระบบ")||($text== "ข้อมูล")||($text== "ผู้พัฒนาระบบ")){
			$reply_message = '
ชื่อนายพีรดนย์ ก่อเกิด
ศึกษาอยู่ชั้นปี 4
สาขาวิชาวิศวกรรมซอฟต์แวร์
อายุ 22 ปี 
น้ำหนัก 52 kg. 
สูง 175 cm.
ขนาดรองเท้าเบอร์ 10.5 
ใช้หน่วย US';
		}
		else
		{
			$reply_message = 'ระบบได้รับข้อความ ('.$text.') ของคุณแล้ว';
    		}
   
   }
   else
    $reply_message = 'ระบบได้รับ '.ucfirst($event['message']['type']).' ของคุณแล้ว';
  
  }
  else
   $reply_message = 'ระบบได้รับ Event '.ucfirst($event['type']).' ของคุณแล้ว';
 
  if( strlen($reply_message) > 0 )
  {
   //$reply_message = iconv("tis-620","utf-8",$reply_message);
   $data = [
    'replyToken' => $reply_token,
    'messages' => [['type' => 'text', 'text' => $reply_message]]
   ];
   $post_body = json_encode($data, JSON_UNESCAPED_UNICODE);

   $send_result = send_reply_message($API_URL, $POST_HEADER, $post_body);
   echo "Result: ".$send_result."\r\n";
  }
 }
}

echo "OK";

function send_reply_message($url, $post_header, $post_body)
{
 $ch = curl_init($url);
 curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
 curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
 curl_setopt($ch, CURLOPT_HTTPHEADER, $post_header);
 curl_setopt($ch, CURLOPT_POSTFIELDS, $post_body);
 curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
 $result = curl_exec($ch);
 curl_close($ch);

 return $result;
}

?>
