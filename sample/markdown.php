<?php
require "../../../../vendor/autoload.php";

$body="#Hello";

// 마크다운 변환
$body = \Jiny\markdown($body);
echo $body."<br>";