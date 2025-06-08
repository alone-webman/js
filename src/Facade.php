<?php

namespace AloneWebMan\Js;

use Webman\Route;
use support\Request;

class Facade {
    protected static array $progressCallback = [];

    /**
     * 路由
     * @param $app
     * @return void
     */
    public static function route($app): void {
        $path = $app['path'] ?? '';
        $down = $app['down'] ?? '';
        $route = $app['route'] ?? '';
        Route::get("/alone/app.js", function(Request $req) use ($down) {
            $body = @file_get_contents(__DIR__ . '/../app.js');
            $body = str_replace('%loaderIng%', json_encode(array_keys($down)), $body);
            return response($body)->withHeaders(["Content-Type" => "application/javascript"]);
        })->name('alone.js.app');
        foreach ($route as $rout => $arr) {
            $val = is_array($arr) ? $arr : explode(',', $arr);
            Route::get("/" . trim($rout, '/'), function(Request $req) use ($down, $rout, $val) {
                $routFile = __DIR__ . '/../file/route/' . trim($rout, '/');
                if (empty(is_file($routFile))) {
                    $content = "";
                    foreach ($val as $file) {
                        if (!empty($url = ($down[$file] ?? ''))) {
                            $file = (__DIR__ . '/../file/' . trim($file, '/'));
                            if (empty(is_file($file))) {
                                $body = static::curl($url);
                                if (!empty($body)) {
                                    @mkdir(dirname($file), 0777, true);
                                    @file_put_contents($file, $body);
                                }
                            } else {
                                $body = @file_get_contents($file);
                            }
                            if (!empty($body)) {
                                $content = $content . $body . "\r\n";
                            }
                        }
                    }
                    if (!empty($content)) {
                        @mkdir(dirname($routFile), 0777, true);
                        @file_put_contents($routFile, $content);
                    }
                }
                if (!empty(is_file($routFile))) {
                    return response()->file($routFile);
                }
                return response("error", 404);
            })->name('alone.js.route.' . $rout);
        }
        if (!empty($path)) {
            Route::get("/" . trim($path, '/') . '[{path:.+}]', function(Request $req, mixed $path = "") use ($down) {
                $path = trim($path, '/');
                $update = $req->get('update');
                $filePath = __DIR__ . '/../file/' . $path;
                if (empty(is_file($filePath)) || !empty($update)) {
                    if (!empty($url = ($down[$path] ?? ''))) {
                        $body = static::curl($url);
                        if (!empty($body)) {
                            @mkdir(dirname($filePath), 0777, true);
                            @file_put_contents($filePath, $body);
                            return response()->file($filePath);
                        }
                    }
                    return response("error", 404);
                }
                return response()->file($filePath);
            })->name('alone.js.path');
        }
    }

    /**
     * 生成文件
     * @param array $route
     * @param array $down
     * @return void
     */
    public static function updateRoute(array $route, array $down): void {
        foreach ($route as $rout => $arr) {
            $routFile = __DIR__ . '/../file/route/' . trim($rout, '/');
            if (empty(is_file($routFile))) {
                $content = "";
                $val = is_array($arr) ? $arr : explode(',', $arr);
                foreach ($val as $file) {
                    if (!empty($url = ($down[$file] ?? ''))) {
                        $file = (__DIR__ . '/../file/' . trim($file, '/'));
                        if (empty(is_file($file))) {
                            $body = static::curl($url);
                            if (!empty($body)) {
                                @mkdir(dirname($file), 0777, true);
                                @file_put_contents($file, $body);
                            }
                        } else {
                            $body = @file_get_contents($file);
                        }
                        if (!empty($body)) {
                            $content = $content . $body . "\r\n";
                        }
                    }
                }
                if (!empty($content)) {
                    @mkdir(dirname($routFile), 0777, true);
                    @file_put_contents($routFile, $content);
                }
            }
        }
    }

    /**
     * 下载
     * @param array $array
     * @param bool  $update
     * @return void
     */
    public static function downFile(array $array, bool $update = false): void {
        $layuiCssUrl = "";
        $layuiCssPath = "";
        foreach ($array as $k => $v) {
            $file = (__DIR__ . '/../file/' . trim($k, '/'));
            if (empty(is_file($file)) || !empty($update)) {
                $path = static::down($file, $v);
                if (basename($v) == "layui.css") {
                    $layuiCssPath = $path;
                    $layuiCssUrl = dirname($v, 2);
                }
            }
        }
        if (!empty($layuiCssPath)) {
            $css = @file_get_contents($layuiCssPath);
            if ($css) {
                $pattern = '/url\(\s*[\'"]?\.\.\/font\/([^"\'\s\)?#]+)/i';
                preg_match_all($pattern, $css, $matches);
                if (!empty($list = ($matches[1] ?? []))) {
                    $list = array_unique($list);
                    foreach ($list as $item) {
                        $file = rtrim(dirname($layuiCssPath, 2), '/') . '/font/' . trim($item);
                        if (empty(is_file($file)) || !empty($update)) {
                            static::down($file, trim($layuiCssUrl, '/') . "/font/" . trim($item));
                        }
                    }
                }
            }
        }
        print_r("============================================================================\r\n");
    }

    /**
     * @param $url
     * @param $path
     * @return string
     */
    public static function down($path, $url): string {
        $pro = 0;
        print_r("============================================================================\r\n");
        print_r("url: " . $url . "\r\n");
        print_r("path: " . str_replace(__DIR__ . '/../file/', "", $path) . "\r\n");
        $body = static::curl($url, function($progress) use ($url, &$pro) {
            $pro = $progress;
            print_r("progress: $progress%\r");
        });
        print_r("progress:$pro%\r\n");
        if (!empty($body)) {
            @mkdir(dirname($path), 0777, true);
            @file_put_contents($path, $body);
        }
        return $path;
    }

    /**
     * @param string        $url
     * @param callable|null $progress
     * @return string
     */
    public static function curl(string $url, callable|null $progress = null): string {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_AUTOREFERER, true);
        if (!empty($progress)) {
            $count = count(static::$progressCallback) + 1;
            static::$progressCallback[$count] = $progress;
            curl_setopt($ch, CURLOPT_NOPROGRESS, false);
            curl_setopt($ch, CURLOPT_PROGRESSFUNCTION, [static::class, 'callProgress_' . $count]);
        }
        $response = curl_exec($ch);
        $response = curl_errno($ch) ? curl_error($ch) : $response;
        curl_close($ch);
        return !empty($response) ? $response : "";
    }

    /**
     * @param string $name
     * @param array  $arguments
     * @return int
     */
    public static function __callStatic(string $name, array $arguments): int {
        $uuid = substr($name, strlen('callProgress_'));
        if (strlen($uuid) > 0 && !empty($callback = (static::$progressCallback[$uuid] ?? ''))) {
            $callProgress = function($callback, $resource, $download_size, $downloaded, $upload_size, $uploaded): void {
                if ($download_size > 0) {
                    $progress = round($downloaded / $download_size * 100, 2);
                    $callback(number_format($progress, 2, ".", ""), $download_size, $resource, $downloaded, $upload_size, $uploaded);
                }
            };
            call_user_func($callProgress, $callback, ...$arguments);
        }
        return 0;
    }
}