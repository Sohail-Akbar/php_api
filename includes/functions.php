<?php
// trim function
function tooTrim($str)
{
    return trim($str);
}
// To snake case
function toSnakeCase($str)
{
    if (gettype($str) !== "string") return $str;
    $str = strtolower($str);
    $str = preg_replace('/[ ]/', '_', $str);
    return $str;
}
function fromCamelCase($camelCaseString)
{
    $re = '/(?<=[a-z])(?=[A-Z])/x';
    $a = preg_split($re, $camelCaseString);
    return implode(' ', $a);
}
function fromSnakeCase($string)
{
    return preg_replace('/[_]/', " ", $string);
}
// To Noraml case
function toNormalCase($str)
{
    if (gettype($str) !== "string") return $str;
    $str = fromCamelCase($str);
    $str = fromSnakeCase($str);
    return $str;
}
// To number
function toNumber($str, $isFloat = false)
{
    $str = tooTrim($str);
    $regex = $isFloat ? '/[^0-9.]/m' : '/[^0-9]/m';
    $number = preg_replace($regex, '', $str);
    $number = $isFloat ? floatval($number) : intval($number);
    return $number;
}

function bc_code()
{
    return md5(rand(100, 9999));
}
function is_image_file($file_name)
{
    $allowed_ext = array('jpg', 'jpeg', 'png', 'gif', 'jfif');
    $getExt = explode('.', $file_name);
    $ext = strtolower(end($getExt));
    if (in_array($ext, $allowed_ext)) {
        return $ext;
    } else {
        return false;
    }
}
function get_date_with($term)
{
    return date("Y-m-d", strtotime(date("Y-m-d") . $term));
}
// Get Array Value
function arr_val($arr, $key, $default = false)
{
    $default = isset($arr[$key]) ? $arr[$key] : $default;
    return $default;
}

// Read Text File
function read_text_file($filepath)
{
    if (!file_exists($filepath)) return false;
    $data = "";
    $handle = fopen($filepath, "r");
    if ($handle) {
        while (($line = fgets($handle)) !== false) {
            $data .= $line;
        }
        fclose($handle);
    }
    return $data;
}

// Get Param
function _get_param($param_name, $default_value = "")
{
    $value = $default_value;
    if (isset($_GET[$param_name])) $value = $_GET[$param_name];
    return $value;
}
// get Post Param
function _post_param($param_name, $default_value = "")
{
    $value = $default_value;
    if (isset($_POST[$param_name])) $value = $_POST[$param_name];
    return $value;
}
// get File Param
function _file_param($param_name, $default_value = "")
{
    $value = $default_value;
    if (isset($_FILES[$param_name])) $value = $_FILES[$param_name];
    return $value;
}
// Return Request Error
function _REQUEST_ERROR($msg, $output_msg = false)
{
    if (!$output_msg) $output_msg = error("MSG_HERE");
    $output_msg = str_replace('MSG_HERE', $msg, $output_msg);
    echo $output_msg;
    die();
}
// Get Request Parameter
function _REQUEST($request_type, $param_name, $options)
{
    $required = array_key_exists("required", $options) ? $options['required'] : true;
    $required = array_key_exists("default", $options) ? false : $required;

    $default_value = $required ? false : "";
    // Default value
    $default_value = arr_val($options, "default", $default_value);

    $value = false;
    if ($request_type === "POST")
        $value = _post_param($param_name, $default_value);
    else if ($request_type === "GET")
        $value = _get_param($param_name, $default_value);
    else if ($request_type === "FILES")
        $value = _file_param($param_name, $default_value);

    if (!$required) return $value;
    if (!in_array(gettype($value), ['string', 'boolean', 'integer', 'float'])) return $value;
    $valid = $value === false ? false : true;

    $param = toNormalCase($param_name);
    $param = arr_val($options, "param_name", $param);
    // output message
    $output_msg = arr_val($options, 'output_msg', error("MSG_HERE"));
    if (!$valid) _REQUEST_ERROR("$param is required", $output_msg);
    // check possibel values
    $values = arr_val($options, 'values');
    if ($values) {
        if (!in_array($value, $values)) {
            $msg = "invalid $param value!";
            if (arr_val($options, 'show_values', true))
                $msg = "$param should be " . implode(' || ', $values);
            _REQUEST_ERROR($msg, $output_msg);
        }
    }

    $length = strlen(tooTrim($value));
    // Allow empty
    $empty = arr_val($options, 'empty');
    if ($empty) return $value;
    else if ($length < 1) _REQUEST_ERROR("$param is not allowed to be empty", $output_msg);

    // Check min length
    $min = arr_val($options, 'min');
    if ($min) {
        if (strlen($value) < $min) _REQUEST_ERROR("$param min length should be $min", $output_msg);
    }

    // Check max length
    $max = arr_val($options, 'max');
    if ($max) {
        if (strlen($value) > $max) _REQUEST_ERROR("$param max length should be $max", $output_msg);
    }


    return $value;
}
// get request post param
function _POST($param_name, $options = [])
{
    return _REQUEST("POST", $param_name, $options);
}
// get request get param
function _GET($param_name, $options = [])
{
    return _REQUEST("GET", $param_name, $options);
}

// Genere Random Name
function getRandom($length)
{
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';

    for ($i = 0; $i < $length; $i++) {
        // Use random_int() if available, otherwise fallback to mt_rand()
        $randomIndex = function_exists('random_int')
            ? random_int(0, $charactersLength - 1)
            : mt_rand(0, $charactersLength - 1);

        $randomString .= $characters[$randomIndex];
    }

    return $randomString;
}

// rtrim with whole word
function _rtrim($str, $word)
{
    $str = preg_replace('/(' . $word . ')$/', '', $str);
    return $str;
}
// Delet file
function unlink_($filename)
{
    if (file_exists($filename))
        unlink($filename);
}
