<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<style>
.colorblockout{
    display:inline-block;width:100px;height:150px;
    text-align: center;
    background-color: #bbbbbb;
}
.colorblockin{
    display:block;width:100px;height:100px;
}
.color0{
    background-color: #3d6b4e;
}
.color1{
    background-color: #61ac7d;
}
.color2{
    background-color: #a0f8c0;
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
</style>
        <title>关于</title>
    </head>
    <body>
        <h2>开发者</h2>
        <span>本网站由 Sam 独立开发。</span>
        <h2>开源项目</h2>
        <p>该项目本身是一个<a href="https://github.com/TSStudio/cases" class="button">开源项目</a>。</p>
        <span>该网站使用的开源项目包括但不限于：（按开源协议分类）</span>
        <h3>协议：The PHP License</h3>
        <p>PHP <a href="https://www.php.net/">www.php.net</a></p>
        <h3>协议：Apache</h3>
        <p>阿里云 PHP SDK <a href="https://developer.aliyun.com/sdk">developer.aliyun.com/sdk</a></p>
        <h3>协议：GNU GPLv3</h3>
        <p>TSSlog3j1，该项目未公开上传至开源平台，代码如下</p>
<pre style="background-color: #a0f8c0;">
class TSSlog3j1{
    public $logstr=&quot;&quot;;
    public function getMillisecond(){
        list($t1,$t2)=explode(&#x27; &#x27;,microtime());
        return (float)sprintf(&#x27;%.3f&#x27;,(floatval($t1)+floatval($t2))*1000);
    }
    public function next($str){
        $this-&gt;logstr=$this-&gt;logstr.&quot;[&quot;.round($this-&gt;getMillisecond(),3).&quot;] &quot;.$str.&quot;&lt;br&gt;&quot;;
    }
}
</pre>
        <h2>设计标准</h2>
        <p>本项目按实际平日最大承载 QPS（秒请求次数）600 设计。峰值保证 10000 QPS 持续 0.5 秒 不崩溃。（本网站硬件条件下）5 分钟内可快速扩容至稳定处理 2000 QPS。此架构下，双机工作可达到 4000 QPS。工单码分区后，理论上可无限扩容。</p>
        <h2>配色</h2>
        <p>除黑、白、灰（即 R G B 通道亮度相同的颜色）外，该项目配色如下</p>
        <div>
            <div class="colorblockout">
                <div class="colorblockin color0"> </div>#3d6b4e
            </div>
            <div class="colorblockout">
                <div class="colorblockin color1"> </div>#61ac7d
            </div>
            <div class="colorblockout">
                <div class="colorblockin color2"> </div>#a0f8c0
            </div>
        </div>
    </body>
</html>