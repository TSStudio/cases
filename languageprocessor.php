<?php 
class TSSLanguages{
    public function constructAvailableLanguages(){
        $count=0;
        return $count;
    }
    public $availableLanguages;
    //replaceArgs($langstr : original language file string, $parameter : parameter that need to be filled)
    public function replaceArgs($langstr,$parameter){
        return str_replace("%s",$parameter,$langstr);
    }
}