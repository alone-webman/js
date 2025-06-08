<?php

function alone_js_route(): void {
    \AloneWebMan\Js\Facade::route(config('plugin.alone.js.app', []));
}