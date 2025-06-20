<?php

use Webman\Route;
use support\Response;
use AloneWebMan\Js\Facade;
use AloneWebMan\Js\VueClass;

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

/**
 * js转换成js
 * @param string $file
 * @return string
 */
function alone_vue_to_js(string $file): string {
    return VueClass::get($file)->getCode();
}

/**
 * @param string $path 访问路径
 * @param string $dir  文件目录
 * @return void
 */
function alone_vue_route(string $path, string $dir): void {
    $path = trim(trim($path, '\\'), '/');
    Route::get('/' . $path . '/[{path:.+}]', function($req) use ($path, $dir) {
        $name = (substr($req->path(), strlen("/" . $path . "/")));
        $file = base_path(trim(trim($dir, '\\'), '/') . "$name");
        $res = response()->file($file);
        return strtolower(pathinfo($name, PATHINFO_EXTENSION)) == 'vue' ? $res->withHeaders(['Content-Type' => 'text/html']) : $res;
    });
}

/**
 * @param string $path 访问路径
 * @param string $dir  文件目录
 * @return void
 */
function alone_url_route(string $path, string $dir): void {
    $path = trim(trim($path, '\\'), '/');
    Route::get('/' . $path . '/[{path:.+}]', function($req) use ($path, $dir) {
        $name = (substr($req->path(), strlen("/" . $path . "/")));
        $file = base_path(trim(trim($dir, '\\'), '/') . "$name");
        $body = @file_get_contents($file);
        return response(alone_safe_url_en($body), 200, ['Content-Type' => 'text/plain']);
    });
}

/**
 * @param string $path 访问路径
 * @param string $dir  文件目录
 * @return void
 */
function alone_mov_route(string $path, string $dir): void {
    $path = trim(trim($path, '\\'), '/');
    Route::get('/' . $path . '/[{path:.+}]', function($req) use ($path, $dir) {
        $name = (substr($req->path(), strlen("/" . $path . "/")));
        $file = base_path(trim(trim($dir, '\\'), '/') . "$name");
        $body = @file_get_contents($file);
        return response(alone_safe_mov_en($body), 200, ['Content-Type' => 'text/plain']);
    });
}