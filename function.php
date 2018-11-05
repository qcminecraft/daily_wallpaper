<?php
/**
 *   ___   ____ _____ _____ ____ _   _
 * / _ \ / ___|_   _| ____/ ___| | | |
 *| | | | |     | | |  _|| |   | |_| |
 *| |_| | |___  | | | |__| |___|  _  |
 *\__\_\\____| |_| |_____\____|_| |_|
 * wallpaper - function.php
 * Copyright (c) 2015 - 2018.,QCTech ,All rights reserved.
 * Created by: QCTech
 * Created Time: 2018-11-04 - 20:58
 */

/**
 * 获取bing每日图片
 * @param int $number 图片编号(1-7)
 * @return string
 */
function getBingPictureURL($number=1){
    $str = file_get_contents('https://cn.bing.com/HPImageArchive.aspx?idx='.$number.'&n=1');
    if(preg_match("/<urlBase>(.+?)<\/urlBase>/ies",$str,$matches)){
        return $imgurl='http://cn.bing.com'.$matches[1].'_1920x1080.jpg';
    }
}

/**
 * 从URL下载图片
 * @param string $url 图片地址
 * @param string $local_address 本地保存地址
 * @return string
 */
function getImageFromURL($url, $local_address='./image/'){
    if($url == '') {
        return false;
    }
    $ext_name = strrchr($url, '.'); //获取图片的扩展名
    if($ext_name != '.gif' && $ext_name != '.jpg' && $ext_name != '.bmp' && $ext_name != '.png') {
        return false; //格式不在允许的范围
    }
    $filename = $local_address.md5($url).'.jpg';
    if(file_exists($filename)){
        return false;
    }
    ob_start();
    readfile($url);
    $img_data = ob_get_contents();
    ob_end_clean();
    $local_file = fopen($filename, 'a');
    fwrite($local_file, $img_data);
    fclose($local_file);
    return $filename;
}

/**
 * 下载bing每日图片
 * @param $download_dir 保存路径
 * @return bool
 */
function bingWallpaper($download_dir='image/bing/'){
    $result = true;
    if(!file_exists($download_dir)) {
        mkdir($download_dir, '0777', true);
        for($i=1;$i<=7;$i++){
            if(!getImageFromURL(getBingPictureURL($i),$download_dir)) $result = false;
        }
    }
    return $result;
}

/**
 * 获取本地壁纸
 * @param string $type
 */
function getImagesFromLocal ($type = 'all')
{
    if ($handle = opendir('./image/bing/')) {
        echo "Files:\n";
        while (false !== ($file = readdir($handle))) {
            echo "/" . "$file\n<br />";
        }
        closedir($handle);
    }
}