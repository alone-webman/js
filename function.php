<?php

use support\Response;
use AloneWebMan\Js\Facade;

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