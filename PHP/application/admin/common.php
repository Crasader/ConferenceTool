<?php
/**
 * 一个获取图片的简单处理
 * @param string $path
 * @param string $root
 * @return string
 */
function get_img_url($path='',$root=''){
    if(empty($path)) return '';

    return $root.$path;
}