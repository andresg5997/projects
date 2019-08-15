<?php

function make_slug($string = null, $separator = '-')
{
    if (is_null($string)) {
        return '';
    }

    // Remove spaces from the beginning and from the end of the string
    $string = trim($string);

    // Lower case everything
    // using mb_strtolower() function is important for non-Latin UTF-8 string | more info: http://goo.gl/QL2tzK
    $string = mb_strtolower($string, 'UTF-8');

    // Make alphanumeric (removes all other characters)
    // this makes the string safe especially when used as a part of a URL
    // this keeps latin characters and arabic characters as well
    $string = preg_replace("/[^a-z0-9_\s-ءاأإآؤئبتثجحخدذرزسشصضطظعغفقكلمنهويةى]/u", '', $string);
    // $string = str_slug($string);

    // Remove multiple dashes or whitespaces
    $string = preg_replace("/[\s-]+/", ' ', $string);

    // Convert whitespaces and underscore to the given separator
    $string = preg_replace("/[\s_]/", $separator, $string);

    return $string;
}

function getDomain($url)
{
    $pieces = parse_url($url);
    $domain = isset($pieces['host']) ? $pieces['host'] : '';
    if (preg_match('/(?P<domain>[a-z0-9][a-z0-9\-]{1,63}\.[a-z\.]{2,6})$/i', $domain, $regs)) {
        $result = preg_split('/(?=\.[^.]+$)/', $regs['domain']);

        return $result[0];
    }

    return false;
}

function getAvatarUrl($id)
{
    if(\App::environment('production')){
        if (\Illuminate\Support\Facades\Storage::disk('s3')->exists($id.'.jpg')) {
            return url('uploads/avatars/'.$id.'.jpg');
        } else {
            return url('uploads/avatars/default-avatar.png');
        }
    }else{
        if (\Illuminate\Support\Facades\Storage::disk('avatars')->exists($id.'.jpg')) {
            return url('uploads/avatars/'.$id.'.jpg');
        } else {
            return url('uploads/avatars/default-avatar.png');
        }
    }
}

function command_exist($cmd)
{
    $return = shell_exec($cmd);

    if ($return) {
        return true;
    }

    return false;
}

// check if PHP function is enabled, eg "shell_exec" and "exec"
function isEnabled($func)
{
    return is_callable($func) && false === stripos(ini_get('disable_functions'), $func);
}

function setEnvironmentValue($environmentName, $configKey, $newValue)
{
    file_put_contents(\App::environmentFilePath(), str_replace(
        $environmentName . '=' . \Config::get($configKey),
        $environmentName . '=' . $newValue,
        file_get_contents(\App::environmentFilePath())
    ));

    \Config::set($configKey, $newValue);

    // Reload the cached config
    if (file_exists(\App::getCachedConfigPath())) {
        \Artisan::call("config:cache");
    }
}
