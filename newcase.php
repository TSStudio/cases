<?php
include "contestinfo.php";
if($_POST["adminpassword"]!=$adminpassword){die();}
class genCode{
    private function getYr(){
        $dataset=str_split("ABCDEFGHIJKLMNOPQRSTUVWXYZ");
        $yrs=date("Y")-2019;
        if($yrs>=10){
            $yrs=$dataset[$yrs-10];
        }
        return $yrs;
    }
    private function getMo(){
        return date("m");
    }
    private function getDa(){
        return date("d");
    }
    private function getRandStr($length=1){
        srand(date("s"));
        $possible_charactors = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $string = "";
        while(strlen($string)<$length){
            $string.= substr($possible_charactors, (rand() % (strlen($possible_charactors))) , 1);
        }
        return($string);
    }
    public function gen(){
        $str="";
        $str=$str.$this->getYr().$this->getMo().$this->getDa()."-";
        $rs=$this->getRandStr(5);
        $str=$str.$rs."-";
        $rs=$rs.$this->getYr().$this->getMo().$this->getDa().time();
        $rs=hash("sha256",$rs.microtime());
        $rs=strtoupper(substr($rs,4,5));
        $str=$str.$rs."-";
        $str=$str.strtoupper(substr(hash("sha256",time().microtime()),4,5))."-";
        $r=rand(0,9);
        $bstr=strtoupper(substr(hash("sha256",$str.microtime()),$r,4));
        $str=$str.$r.$bstr;
        return $str;
    }
}
$the=new genCode();
$con=new \mysqli($dbhost,$dbuser,$dbpawd,$dbname);
$email=mysqli_real_escape_string($con,$_POST["email"]);
$time=time();
$serialno=$the->gen();
$con->query("INSERT INTO `casesManifest` (id,email,createtime) values(\"{$serialno}\",\"{$email}\",{$time});");
echo $serialno;


include_once 'include/aliyun-php-sdk-core/Config.php';
use Dm\Request\V20151123 as Dm;            
$iClientProfile = DefaultProfile::getProfile("cn-hangzhou", $aliaccesskey, $aliaccesssecret);        
$client = new DefaultAcsClient($iClientProfile);    
$request = new Dm\SingleSendMailRequest();     
$request->setAccountName("no-reply@mailsend.tmysam.top");
$request->setFromAlias("TSStudio");
$request->setAddressType(1);
$request->setTagName("TSStudio");
$request->setReplyToAddress("true");
$request->setToAddress($email);
$request->setSubject("工单 #".$serialno." 的回执");
$htmlbody='<div style="width:100%;height:100%;border-width:10px;border-style:solid;border-color:#61ac7d;"><div style="width:97%;height:95%;margin:auto;background-color: #ffffff;top:0;bottom:0;left:0;right:0;"><div style="width:100%;font-size:2em;margin:20px;">TSStudio 工单回执</div><div style="width:97%;padding:20px;background-color:#000000;color:#ffffff;"><img src="data:image/png;base64,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" alt="serial number">'.$serialno.' <a href="https://www.tmysam.top/cases?no='.$serialno.'" style="text-decoration:none;background-color: #61ac7d;color:#ffffff;">点击在系统中查看</a><br><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACAAAAAgEAYAAAAj6qa3AAAABGdBTUEAALGPC/xhBQAAAAFzUkdCAK7OHOkAAAAgY0hSTQAAeiYAAICEAAD6AAAAgOgAAHUwAADqYAAAOpgAABdwnLpRPAAAAAZiS0dEAAAAAAAA+UO7fwAAAAlwSFlzAAAASAAAAEgARslrPgAAA95JREFUaN7tWd9LVEEUnrn5YJoPRWpZGoRtJARlEKyIRKYvPqiZ2bPhX5AoQW+JYQTRYySBC5H0ErRYLQsSgviorCBmiWut5Ua4tgUqLXN6+HbW7t3Z9f7Y6wZ5Xj5m7sw353xzZ+6ZuYzt2f9t3C1iIiIiTWPEiNGxY4wzzvjx43haVob6WAz1376hPhzmnHPONzfzLYz1gAUJEs3NwCdPIEA0SpZsYwM4Ngaenh6Ui4ryHV96wERE5PUCJyasBWrVVlb0guzbl6s4TC8BDMyT7W/fBt69C9S09A6MGC0u4hV/+xaVi4vAaBR48CDw6FFgQwOwrg6oCJQYMQoGwdvVhSUTi+V6gg2BaxrQ58s4SYIEidevUbhwwfZ4ggSJsjLwDA3pl4bRFhbQ/sQJlwWQjhjt509gR4e74588CQyF1H7MzECIAwdyPPD16+oB19aA585Z42tvB66vy83SdP9kgOg/Pq726/nzHAVeWAj89Ek/wO/fcKSpyR7v6KieL5Gwx3PoEPDDB/VSlHuJHQEECRK9vWqFHz50xvvihZHRGV99vVqAqSn7AhAR0dycnvXHDxAfPvyvCKD399Ur9YTV1OzUP/X5goOnTqF05oy+2cuXXOMa175/d+qwOzY8rK5vazMtAL6rV66om/n9+Q4xuwWDwI2NVBUxYnT5snkBiBGjysq0FsSIUSiU7xAzGRIhGfjHj9sP/j57mBGAM874kSPqZqurbgWApXfnDtbs/v3O2L580ZcrKswL4LZxxhmXqbCxXqbUcvO9enXX/JKGgQcG1J8Vj8cxvyBBoqAApH19wHhcvXsLAbx0yZr/xkxxfn6nfoY3IBJJa8EZZ/zsWacC4CuSSGDN3r+PWo8He8zTpygLkWzN9bhT4PK4XF29/YARo8+fLc6Qx6OeEZ/PqQDmAjl9Glhba61fa6vab3lqteyIMRGKxyFQaanbQtjz1+9XC2DMZ0wT3rqlJnz0KN8Bp/xM5vxqPycnHSoqD0PhsJ44kQC2tOQtcCLa+TBUX58jha9dUysciwHPn9+1wAUJEiUlGPfdO7Vfz565pPi9e2qlf/0Cdna6G3h1NQadnVX7MT2NQnGxSwLIK7GREcpqgQAcunjRWcDl5cAHD8Cb6Urs/Xu0q6qyO57l/wIYuL8fpcFBoJYho1xaAr55A/zrUpQYMZKXohUVyDfkRYbXm503EADeuIG8Yn3drgC2Tc509jWZK4tEgN3dwNxdi+dOECIiamwEPn4M/PrVWqDyVZff9Zs3gU4PSZltl36NyVdcHk/Ly1O/xhhj26fN5WWkzFtbbvm1Z3umtz9GUd1ZOrpnFwAAACV0RVh0ZGF0ZTpjcmVhdGUAMjAyMi0wMS0xMFQxMzozNjo0MyswODowMLanQx8AAAAldEVYdGRhdGU6bW9kaWZ5ADIwMjItMDEtMTBUMTM6MzY6NDMrMDg6MDDH+vujAAAASHRFWHRzdmc6YmFzZS11cmkAZmlsZTovLy9ob21lL2FkbWluL2ljb24tZm9udC90bXAvaWNvbl9ucG1nNTlubzMyOS9jbG9jay5zdmdekMlFAAAAAElFTkSuQmCC" alt="time">'.date("Y-m-d H:i:s",$time).' 的回复<br><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACAAAAAgEAYAAAAj6qa3AAAABGdBTUEAALGPC/xhBQAAAAFzUkdCAK7OHOkAAAAgY0hSTQAAeiYAAICEAAD6AAAAgOgAAHUwAADqYAAAOpgAABdwnLpRPAAAAAZiS0dEAAAAAAAA+UO7fwAAAAlwSFlzAAAASAAAAEgARslrPgAAAlpJREFUaN7tVrtKa1EQ3RNvEUkrimAnKkG/QVDIJ9jExuA3aNJosLYIaGP8BAtjYz5C8NEICinERx+1izrrFnP35U5yjjknObljcVaz2PvMzF5rkcA4lyJFihQGAIPBMzOerfX8Z+NbWwAAfH0JMwtXKtb6Rmx8fl6MdjrCn5+aOx1fZ603+QAAAI0GFI6OhOt1fX96aq03OeMMBi8va4Pv78LT0/J9clLOr6+6bnXVWv/gxgEARMIXF9rYzk5w/e7u3xIGg6+u5JDJWPsZMID1dW385UU4lwuuHx8XfnzUfcWitZ+YxrNZ4YcHbWRjI1p/qaT7np58QNb+IgZQLmsDNzfdP2V0Qd9nMsKXl7pqe9vaX7hxBoMnJkRou63/y4VCcFC9AejvKyu66u1N5k1NWfvtDQAAcHioBZ+ff18fHoAOttnU1QcH1n67BAYsOAwGLy0NHQAAIJ8X/vjQnM9b+/8j8OxM26nXo/X1D0AHfXysuxoNO+N9FpzRBPADFiZ5MPqCk1QAus9wYZKHikUt//lZuHfBCZ3DYPDamud47+dy+l2PES5M8kDYglMqxZ43YABaT/fC5HVlsyMKoFLRD/oFZ2xssHnx/wK6P2xhKpeTMx5zwYk39+TE83BzCgUdQLvtdQ8fAACgVtPGm83EEk4IwQtTrdavj6INbrUcOXI0Oyu3e3sODg63t9bG/3FCjhYX5VCtCrdaREREc3Nhbb+iDb6+loMPoFqVe2vX3wAODl53OPoHAAeHzU0xfHcn54UFa3+hIEeO7u/lsL9vLSdFihQpfjR+A3ZxuyHQju4QAAAAJXRFWHRkYXRlOmNyZWF0ZQAyMDIyLTAxLTEwVDEzOjM2OjQzKzA4OjAwtqdDHwAAACV0RVh0ZGF0ZTptb2RpZnkAMjAyMi0wMS0xMFQxMzozNjo0MyswODowMMf6+6MAAABKdEVYdHN2ZzpiYXNlLXVyaQBmaWxlOi8vL2hvbWUvYWRtaW4vaWNvbi1mb250L3RtcC9pY29uX25wbWc1OW5vMzI5L3dhcm5pbmcuc3ZnsbYtWAAAAABJRU5ErkJggg==" alt="caution">回复请带上上方序列号发送至 TSS@mail.tmysam.top<br></div>你的工单已被创建，编号如上。</div></div>';
$request->setHtmlBody($htmlbody);        
try {
    $response = $client->getAcsResponse($request);
}
catch (ClientException  $e) {
    print_r($e->getErrorCode());   
    print_r($e->getErrorMessage());   
}
catch (ServerException  $e) {        
    print_r($e->getErrorCode());   
    print_r($e->getErrorMessage());
}