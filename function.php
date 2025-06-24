<?php

use Webman\Route;
use support\Request;
use support\Response;
use AloneWebMan\Js\Facade;
use AloneWebMan\Js\VueClass;

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
        $file = rtrim(rtrim($dir, '\\'), '/') . "/" . trim(trim($name, '\\'), '/');// . ($format ? ("." . $format) : "");
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

/**
 * vue转换js
 * @param string $file
 * @return string
 */
function alone_vue_to_js(string $file): string {
    return VueClass::get($file)->getCode();
}

function alone_js_route(): void {
    Facade::route(config('plugin.alone.js.app', []));
}

/**
 * @param string|array $title 标题
 * @param string       $body  内容
 * @param string       $head  头部
 * @param string       $entry 入口js
 * @return Response
 */
function alone_js_html(string|array $title, string $body = '<div id="app"></div>', string $head = "", string $entry = "/alone.js"): Response {
    return response(Facade::tag(@file_get_contents(__DIR__ . '/index.html'), is_array($title)
        ? array_merge($title, ['entry' => '<script type="text/javascript" src="' . $entry . '"></script>'])
        : [
            'title' => $title,
            'head'  => $head,
            'body'  => $body,
            'entry' => '<script type="text/javascript" src="' . $entry . '"></script>',
        ]));
}