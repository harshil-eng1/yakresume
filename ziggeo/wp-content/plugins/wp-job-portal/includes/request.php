<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

class WPJOBPORTALrequest {
    /*
     * Check Request from both the Get and post method
     */

    static function getVar($variable_name, $method = null, $defaultvalue = null, $typecast = null) {
        $value = null;
        if ($method == null) {
            if (isset($_GET[$variable_name])) {
                if(is_array($_GET[$variable_name])){
                    $value = filter_var_array($_GET[$variable_name]);
                }else{
                    $value = filter_var($_GET[$variable_name],FILTER_SANITIZE_STRING);
                }
            } elseif (isset($_POST[$variable_name])) {
                if(is_array($_POST[$variable_name])){
                    $value = filter_var_array($_POST[$variable_name]);
                }else{
                    $value = filter_var($_POST[$variable_name],FILTER_SANITIZE_STRING);
                }
            } elseif (get_query_var($variable_name)) {
                $value = get_query_var($variable_name);
            } elseif (isset(wpjobportal::$_data['sanitized_args'][$variable_name]) && wpjobportal::$_data['sanitized_args'][$variable_name] != '') {
                $value = wpjobportal::$_data['sanitized_args'][$variable_name];
            }
        } else {
            $method = strtolower($method);
            switch ($method) {
                case 'post':
                    if (isset($_POST[$variable_name]))
                        if (is_array($_POST[$variable_name])) {
                            $value = filter_var_array($_POST[$variable_name]);
                        }else{
                            $value = filter_var($_POST[$variable_name],FILTER_SANITIZE_STRING);
                        }
                    break;
                case 'get':
                    if (isset($_GET[$variable_name]))
                        if (is_array($_GET[$variable_name])) {
                            $value = filter_var_array($_GET[$variable_name]);
                        }else{
                            $value = filter_var($_GET[$variable_name],FILTER_SANITIZE_STRING);
                        }
                    break;
            }
        }
        if ($typecast != null) {
            $typecast = strtolower($typecast);
            switch ($typecast) {
                case "int":
                    $value = (int) $value;
                    break;
                case "string":
                    $value = (string) $value;
                    break;
            }
        }
        if ($value == null)
            $value = $defaultvalue;
        //echo print_r($value); exit;
        return $value;
    }

    /*
     * Check Request from both the Get and post method
     */

    static function get($method = null) {
        $array = null;
        if ($method != null) {
            $method = strtolower($method);
            switch ($method) {
                case 'post':
                    $array = filter_var_array($_POST);
                    break;
                case 'get':
                    $array = filter_var_array($_GET);
                    break;
            }
        }
        return $array;
    }

    /*
     * Check Request from both the Get and post method
     */

    static function getLayout($layout, $method, $defaultvalue) {
        $layoutname = null;
        if ($method != null) {
            $method = strtolower($method);
            switch ($method) {
                case 'post':
                    $layoutname = filter_var($_POST[$layout],FILTER_SANITIZE_STRING);
                    break;
                case 'get':
                    $layoutname = filter_var($_GET[$layout],FILTER_SANITIZE_STRING);
                    break;
            }
        } else {
            if (isset($_POST[$layout]))
                $layoutname = filter_var($_POST[$layout],FILTER_SANITIZE_STRING);
            elseif (isset($_GET[$layout]))
                $layoutname = filter_var($_GET[$layout],FILTER_SANITIZE_STRING);
            elseif (get_query_var($layout))
                $layoutname = get_query_var($layout);
            elseif (isset(wpjobportal::$_data['sanitized_args'][$layout]) && wpjobportal::$_data['sanitized_args'][$layout] != '')
                $layoutname = wpjobportal::$_data['sanitized_args'][$layout];
        }
        if ($layoutname == null) {
            $layoutname = $defaultvalue;
        }
        if (is_admin()) {
            $layoutname = 'admin_' . $layoutname;
        }
        return $layoutname;
    }

}

?>
