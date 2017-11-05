<?php
/**
 * 公共函数库
 * @date 2017-09-29
 * @date guangzheng
 */
/**
 * 递归创建目录
 */
function mkDirs($dir){
    if(!is_dir($dir)){
        if(!mkDirs(dirname($dir))){
            return false;
        }
        if(!mkdir($dir,0555)){
            return false;
        }
        chmod($dir,0777);
    }
    return true;
}
/**
 * 去除参数两端空格
 */
function trimParams($params = array())
{
    if(is_array($params) && !empty($params))
    {
        foreach ($params as $key=>$val)
        {
            if('' === $val)
            {
                unset($params[$key]);
                continue;
            }
            if(is_array($val))
            {
                $params[$key] = trimParams($val);
            }else
            {
                $params[$key] = trim($val);
            }
        }
        return $params;
    }
    return trim($params);
}