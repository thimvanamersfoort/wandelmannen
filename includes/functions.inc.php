<?php

function chopStringToRoot($fullPath)
{
    $rootPathName = '';

    $rootPath = substr($fullPath, 0, strpos($fullPath, $rootPathName)) . $rootPathName;
    return $rootPath;
}
function chopStringToImageName($fullPath)
{
    $rootpath = substr($fullPath, strrpos($fullPath, "\\") + 1, strlen($fullPath));
    return $rootpath;
}