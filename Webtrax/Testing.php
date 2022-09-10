<?php

$url="https://webtrax.formulatrix.com/IotInject.php";

//Machine=MVP10&Bit=1&DevID=ESP32&Batt=89

$data=array("Machine"=>"YCM EV1020A","Bit"=>"TRUE","DevID"=>"ESP32-B","Batt"=>"76.58943");
$options = array(
            "http"=> array(
                "method"=>"POST",
                "header"=>"Content-Type: application/x-www-form-urlencoded",
                "content"=>http_build_query($data)
            )
);
$page=file_get_contents($url,false,stream_context_create($options));
echo $page;

?>