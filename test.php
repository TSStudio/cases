<?php 
include "contestinfo.php";
$con=new \mysqli($dbhost,$dbuser,$dbpawd,$dbname);
$text1=htmlspecialchars("文本 TEXT <>");
$text2=mysqli_real_escape_string($con,$text1);
echo $text2;