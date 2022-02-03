<?php
session_start();
if(!isset($_SESSION["language"])){
    $_SESSION["language"]="zh-cn";
}
include "languagefile.php";
include "languageprocessor.php";
$lpcs=new TSSLanguages();
class TSSlog3j1{
    public $logstr="";
    public function getMillisecond(){
        list($t1,$t2)=explode(' ',microtime());
        return (float)sprintf('%.3f',(floatval($t1)+floatval($t2))*1000);
    }
    public function next($str){
        $this->logstr=$this->logstr."[".round($this->getMillisecond(),3)."] ".$str."<br>";
    }
}
$logger=new TSSlog3j1();
$output="";
$st=$logger->getMillisecond();
$logger->next("LOGGING START");
if(!isset($_GET["no"])){
    $logger->next("GET param \"no\" is not defined. Exit 1");
    $serialno=$langUndefined;
    $manifest=$langUnclearCase;
    goto end;
}
include "contestinfo.php";
$logger->next("Loading Database User and connecting");
$con=new \mysqli($dbhost,$dbuser,$dbpawd,$dbname);
$serialno=mysqli_real_escape_string($con,$_GET["no"]);
if($con){
    $logger->next("Connected");
}else{
    $logger->next("Failed to connect to databse:");
    $logger->next(mysqli_error($con));
    goto end;
}
$s1=$logger->getMillisecond();
$result=$con->query("SELECT email,createtime FROM `casesManifest` WHERE id=\"{$serialno}\"");
$s2=$logger->getMillisecond();
$logger->next("Query took ".round($s2-$s1,3)." ms");
if($result->num_rows>0){
    $row=$result->fetch_array();
    $createtime=$row["createtime"];
    $email=$row["email"];
    $logger->next("Found case. Create time ".$createtime." Email ".$email);
    $manifest="<img src=\"https://gravatar.loli.net/avatar/".md5(strtolower(trim($email)))."?d=mp\" alt=\"image of user\" /><a href=\"http://cn.gravatar.org/\" class=\"button\">(?)</a><br>".$langEmail.": ".$email."<br>".$langCreateTime.":".date("Y-m-d H:i:s",$createtime)."<br>";
}else{
    $logger->next("Case not found.");
    $manifest=$langUnclearCase;
    goto end;
}
$logger->next("Looking for replies");
$s1=$logger->getMillisecond();
$result=$con->query("SELECT text,time FROM `casesReply` WHERE caseid=\"{$serialno}\" ORDER BY time DESC;");
$s2=$logger->getMillisecond();
$logger->next("Query took ".round($s2-$s1,3)." ms");
$i=0;
if($result->num_rows>0){
    while($row=$result->fetch_array()){
        $output=$output."<div class=\"message\">".$lpcs->replaceArgs($langReplyat,date("Y-m-d H:i:s",$row["time"]))."<br>".$row["text"]."</div>";
        $logger->next("Reply #".$i." Time:".$row["time"]);
        $i++;
    }
}else{
    $logger->next("No reply found.");
    $output=$langNoReply;
    goto end;
}
end:
$logger->next("Finished Document");
$ed=$logger->getMillisecond();
?>
<html>
    <head>
<style>
.logbox{
    overflow:scroll;height:100px;background-color:#222222;color:#EEEEEE;width:50%;min-width:300px;
    font-family:"Consolas","Courier New",sans-serif;
}
.title{
    font-size:2em;
}
.infobox{
    width:97%;padding:20px;background-color:#000000;color:#ffffff;
}
.message{
    padding:10px;
    margin-top:20px;
    margin-bottom:20px;
    background-color:#a0f8c0;
}
.button{
    padding:5px;
    background-color:#61ac7d;
    color:#ffffff;
    text-decoration:none;
    transition:all .3s;
}
.button:hover{
    background-color:#3d6b4e;
    color:#b9b9b9;
}
.downbarbox{
    margin-top:20px;
}
.languageZone{
    margin-top:20px;
    margin-bottom:20px;
    background-color:#CCCCCC;
    padding:10px;
}
</style>
        <title><?=$langCaseQuery?></title>
    </head>
    <body>
        <div class="title">
            <?=$langCase?> #<?=$serialno?>
        </div>
        <div class="infobox">
        <?=$manifest?>
        </div>
        <div class="languageZone">
            <a class="button" href="languageset.php"><?=$langLanguageSet?>|Language Settings</a><br>
            <?=$langDescribe?>
        </div>
        <?=$langCaseReplies?>
        <div>
            <?=$output?>
        </div>
        <?=$langLog?>
        <div class="logbox">
            <?=$logger->logstr?>
        </div>
        <div>
            <?=$lpcs->replaceArgs($langProcessIn,round($ed-$st,3))?>
        </div>
        <div class="downbarbox">
            <a class="button" href="about.php"><?=$langAbout?></a>
        </div>
    </body>
</html>

