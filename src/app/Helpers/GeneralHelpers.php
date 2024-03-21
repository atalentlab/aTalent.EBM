<?php

use Illuminate\Support\Facades\Route;

/**
 * @param string $path
 * @param string|null $sizePath
 * @param string $action ('fit' or 'resize')
 * @return string
 */
function image(string $path = null, string $sizePath = null, string $action = 'fit')
{
    $size = explode('x', $sizePath);

    $width = isset($size[0]) ? $size[0] : null;
    $height = isset($size[1]) ? $size[1] : null;

    return ImageResize::url($path, $width, $height, $action);
}

function str_readable(string $string): string
{
    return ucfirst(str_replace("_", " ", $string));
}

/**
 * Give active CSS class to nav item
 *
 * @param $string
 * @return string
 */
function is_active_menu($string, $class = 'active')
{
    return Route::getCurrentRoute() != null && strpos(Route::getCurrentRoute()->getName(), '.' . $string) !== false || strpos(Request::url(), '/' . $string) !== false ? $class : '';
}
