<?php

use support\Response;
use AloneWebMan\Js\Facade;

function alone_js_route(): void {
    Facade::route(config('plugin.alone.js.app', []));
}


/**
 * @param string $title 标题
 * @param string $body  内容
 * @param string $head  头部
 * @param string $entry 入口js
 * @return Response
 */
function alone_js_html(string $title, string $body, string $head = "", string $entry = ""): Response {
    return response(Facade::tag(@file_get_contents(__DIR__ . '/index.html'), [
        'title' => $title,
        'head'  => $head,
        'body'  => ($body . (!empty($js) ? '<script type="text/javascript" src="' . $entry . '"></script>' : "")),
    ]));
}