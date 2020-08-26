<?php

function convertFromOneDigToTwoDig($dig){
    if(strlen($dig) == 1)
        $dig = '0'.$dig;
    return $dig;
}

function convertUndefinedToNull($request)
{
    foreach ($request->all() as $key => $value) {
        if($value == '') $request->$key = "null";
    }
    return $request;
}

?>
