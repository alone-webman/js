<?php

use Webman\Route;
use support\Request;
use AloneWebMan\Js\VueClass;

/**
 * vue转换js
 * @param string $file
 * @return string
 */
function alone_vue_to_js(string $file): string {
    return VueClass::get($file)->getCode();
}

/**
 * @param string $path   访问路径
 * @param string $dir    文件目录
 * @param string $type   类型 vue text json
 * @param string $format 自带格式
 * @return void
 */
function alone_vue_route(string $path, string $dir, string $type = "vue", string $format = ""): void {
    $path = trim(trim($path, '\\'), '/');
    Route::get('/' . $path . '/[{path:.+}]', function(Request $req) use ($path, $dir, $type, $format) {
        $name = (substr($req->path(), strlen("/" . $path . "/")));
        $file = rtrim(rtrim($dir, '\\'), '/') . "/" . trim(trim($name, '\\'), '/');
        $layout = pathinfo($file, PATHINFO_EXTENSION);
        $file = empty($layout) ? $file . ($format ? ("." . $format) : "") : $file;
        $layout = empty($layout) ? $format : $layout;
        if (empty($layout) || in_array(strtolower($layout), ['vue', 'js', 'html'])) {
            return match ($type) {
                "text"  => response(alone_safe_url_en(@file_get_contents($file)), 200, ['Content-Type' => 'text/plain']),
                "json"  => json(alone_safe_mov_en(@file_get_contents($file))),
                default => response()->file($file)->withHeaders(['Content-Type' => 'text/html']),
            };
        }
        return response()->file($file);
    })->name("alone_vue_" . $path);
}