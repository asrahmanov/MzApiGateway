<?php

function active_class($path, $active = 'active') {
  return call_user_func_array('Request::is', (array)$path) ? $active : '';
}

function is_active_route($path) {
  return call_user_func_array('Request::is', (array)$path) ? 'true' : 'false';
}

function show_class($path)
{
    return call_user_func_array('Request::is', (array)$path) ? 'show' : '';
}


function search_in_array_objects($needleString,$array,$unicField='name')
{
    try {
        foreach ($array as $item){
            if (strtolower($item[$unicField]) === strtolower($needleString)){
                return $item['id'];
            }
        }
    }catch (ErrorException $e){
        dd($needleString,$array,$unicField);
    }

};
