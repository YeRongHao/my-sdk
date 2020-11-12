<?php
namespace YeRongHao\JinritemaiSdk;

class Common{
    /**
     * Notes:GET 请求
     * @param $url
     * @param array $param
     * @return bool|string
     */
    public function http_get($url,$param = []){
        if($param){
            $path = [];
            foreach ($param as $key => $value){
                $path[] = "{$key}=$value";
            }
            $url .= '?' . implode("&",$path);
        }

        $url = str_replace(' ','%20',$url);

        $oCurl = curl_init();
        if(stripos($url,"https://")!==FALSE){
            curl_setopt($oCurl, CURLOPT_SSL_VERIFYPEER, FALSE);
            curl_setopt($oCurl, CURLOPT_SSL_VERIFYHOST, FALSE);
            curl_setopt($oCurl, CURLOPT_SSLVERSION, 1); //CURL_SSLVERSION_TLSv1
        }
        curl_setopt($oCurl, CURLOPT_URL, $url);
        curl_setopt($oCurl, CURLOPT_RETURNTRANSFER, 1 );
        $sContent = curl_exec($oCurl);
        $aStatus = curl_getinfo($oCurl);
        curl_close($oCurl);
        if(intval($aStatus["http_code"])==200){
            return json_decode($sContent,true);
        }else{
            return $sContent;
        }
    }

    /**
     * POST 请求
     * @param string $url
     * @param array $param
     * @param boolean $post_file 是否文件上传
     * @return string content
     */
    public function http_post($url,$param = [],$post_file=false){
        $oCurl = curl_init();
        if(stripos($url,"https://")!==FALSE){
            curl_setopt($oCurl, CURLOPT_SSL_VERIFYPEER, FALSE);
            curl_setopt($oCurl, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($oCurl, CURLOPT_SSLVERSION, 1); //CURL_SSLVERSION_TLSv1
        }
        if (is_string($param) || $post_file) {
            $strPOST = $param;
        } else {
            $aPOST = array();
            foreach($param as $key=>$val){
                $aPOST[] = $key."=".urlencode($val);
            }
            $strPOST =  join("&", $aPOST);
        }
        curl_setopt($oCurl, CURLOPT_URL, $url);
        curl_setopt($oCurl, CURLOPT_RETURNTRANSFER, 1 );
        curl_setopt($oCurl, CURLOPT_POST,true);
        /**if(PHP_VERSION_ID >= 50500){
        curl_setopt($oCurl, CURLOPT_SAFE_UPLOAD, FALSE);
        }*/
        curl_setopt($oCurl, CURLOPT_POSTFIELDS,$strPOST);

        $sContent = curl_exec($oCurl);
        $aStatus = curl_getinfo($oCurl);
        curl_close($oCurl);
        if(intval($aStatus["http_code"])==200){
            return $sContent;
        }else{
            return false;
        }
    }

    /**
     * Notes: 驼峰命名风格转换成下划线命名风格
     * @param $string
     * @return string
     */
    public function parseUnderline($string)
    {
        //替换过程 NameStyle => N | S => _N | _S => _Name_Style => Name_Style => name_style
        $string = strtolower(trim(preg_replace("/[A-Z]/", "_\\0", $string), "_"));
        return $string;
    }
}