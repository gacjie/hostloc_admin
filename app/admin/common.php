<?php


/**
 * PHP格式化字节大小
 * @param  number $size      字节数
 * @param  string $delimiter 数字和单位分隔符
 * @return string            格式化后的带单位的大小
 */
function format_bytes($size, $delimiter = '')
{
    $units = array('B', 'KB', 'MB', 'GB', 'TB', 'PB');
    for ($i = 0; $size >= 1024 && $i < 5; $i++) $size /= 1024;
    return round($size, 2) . $delimiter . $units[$i];
}

/**
 * 获取目录里的文件，不包括下级文件夹
 * @param string $dir  路径
 * @return array
 */
function get_dir($dir)
{
    $file = @scandir($dir);
    foreach ($file as $key) {
        if ($key != ".." && $key != ".") {
            $files[] = $key;
        }
    }
    return $files;
}

/**
 * 获取文件夹中的文件,含目录
 * @param $path
 * @param string $exts
 * @param array $list
 * @return array
 */
function dir_list($path, $exts = '', $list = array())
{
    $path = dir_path($path);
    $files = glob($path . '*');
    foreach ($files as $v) {
        $fileext = fileext($v);
        if (!$exts || preg_match("/\.($exts)/i", $v)) {
            $list[] = $v;
            if (is_dir($v)) {
                $list = dir_list($v, $exts, $list);
            }
        }
    }
    return $list;
}

/**
 * 补齐目录后的/
 * @param $path 目录
 * @return string
 */
function dir_path($path)
{
    $path = str_replace('\\', '/', $path);
    if (substr($path, -1) != '/') $path = $path . '/';
    return $path;
}

/**
 * 查找文件后缀
 * @param $filename 文件名称
 * @return string 后缀名称（如：html）
 */
function fileext($filename)
{
    return strtolower(trim(substr(strrchr($filename, '.'), 1, 10)));
}

/**
 * 删除目录及文件
 * @param $dir
 * @return bool
 */
function dir_delete($dir)
{
    $dir = dir_path($dir);
    if (!is_dir($dir)) return FALSE;
    $list = glob($dir . '*');
    foreach ($list as $v) {
        is_dir($v) ? dir_delete($v) : @unlink($v);
    }
    return @rmdir($dir);
}



/***
 * 日期筛选格式化
 * @param $dateran
 * @return array
 */
function get_dateran($dateran)
{
    if ($dateran) {
        $dateran = explode(" 至 ", $dateran);
    }
    if (is_array($dateran) && count($dateran) == 2) {
        $dateran[0] = strtotime($dateran[0]);
        $dateran[1] = strtotime($dateran[1]) + 24 * 60 * 60 - 1;
    }
    return $dateran;
}

/**
 * 根据数组中某个字段重新分组
 * @param {dataArr:需要分组的数据；keyStr:分组依据}
 * @return: array
 */
function array_group(array $dataArr, string $keyStr): array
{
    $newArr = [];
    foreach ($dataArr as $k => $val) {
        $newArr[$val[$keyStr]][] = $val;
    }
    return $newArr;
}
