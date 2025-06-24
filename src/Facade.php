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
        $config = $app['config'] ?? [];
        $name = $app['down'] ?? key($config);
        $down = $app['config'][$name] ?? [];
        $path = $app['path'] ?? '';
        $route = $app['route'] ?? '';
        $loading = $app['loading'] ?? [];
        $save = rtrim((($app['save'] ?? "") ?: __DIR__ . '/../file/'), '/') . "/";
        $save = rtrim($save, '/') . "/" . $name . "/";
        if (empty($loading)) {
            $loading = [];
            foreach ($down as $k => $v) {
                if (str_ends_with($k, 'js')) {
                    $loading[] = "/" . trim($path, '/') . "/" . trim($k, '/');
                }
            }
        }

        //app.js
        Route::get("/alone/app.js", function(Request $req) use ($loading) {
            $body = @file_get_contents(__DIR__ . '/../app.js');
            $body = str_replace('["/alone/js/layui/css/layui.css","/alone/js/layui/layui.js"]', json_encode($loading), $body);
            return response($body)->withHeaders(["Content-Type" => "application/javascript"]);
        })->name('alone.js.app');


        Route::get("/alone/vueLoader.js", function(Request $req) {
            return response()->file(__DIR__ . '/../file/aloneApp.js');
        })->name('alone.loadVue');

        foreach ($route as $rout => $arr) {
            $val = is_array($arr) ? $arr : explode(',', $arr);
            Route::get("/" . trim($rout, '/'), function(Request $req) use ($save, $down, $rout, $val) {
                $update = $req->get('update');
                $routFile = $save . 'route/' . trim($rout, '/');
                (empty(is_file($routFile)) || !empty($update)) && static::updateRoute($rout, $val, $down, $save);
                if (!empty(is_file($routFile))) {
                    return response()->file($routFile);
                }
                return response("error", 404);
            })->name('alone.js.route.' . $rout);
        }

        //单独访问
        if (!empty($path)) {
            Route::get("/" . trim($path, '/') . '[{path:.+}]', function(Request $req, mixed $path = "") use ($save, $down) {
                $path = trim($path, '/');
                $update = $req->get('update');
                $filePath = $save . $path;
                if (empty(is_file($filePath)) || !empty($update)) {
                    if (!empty($url = ($down[$path] ?? ''))) {
                        static::downFile([$path => $url], $save, !empty($update));
                    }
                    return response("error", 404);
                }
                return response()->file($filePath);
            })->name('alone.js.path');
        }
    }

    public static function cliUpdate(): void {
        $app = include(__DIR__ . '/../config/app.php');
        $config = $app['config'] ?? [];
        $name = $app['down'] ?? key($config);
        $down = $app['config'][$name] ?? [];
        $save = ($app["save"] ?? '') ?: __DIR__ . '/../file/';
        $save = rtrim($save, '/') . "/" . $name . "/";
        Facade::downFile($down, $save, false, true);
        foreach (($app['route'] ?? []) as $rout => $arr) {
            Facade::updateRoute($rout, $arr, $down, $save);
        }
    }

    /**
     * 生成文件
     * @param string       $rout 路由名
     * @param array|string $arr  列表
     * @param array        $down
     * @param string       $save
     * @return void
     */
    public static function updateRoute(string $rout, array|string $arr, array $down, string $save): void {
        $routFile = $save . 'route/' . trim($rout, '/');
        if (empty(is_file($routFile))) {
            $content = "";
            $val = is_array($arr) ? $arr : explode(',', $arr);
            foreach ($val as $file) {
                if (str_starts_with($file, "/")) {
                    $fileName = $file;
                } else {
                    $file = trim($file, '/');
                    $fileName = $save . $file;
                }
                if (empty(is_file($fileName))) {
                    if (!empty($url = ($down[$file] ?? ''))) {
                        $body = static::curl($url);
                        if (!empty($body)) {
                            @mkdir(dirname($fileName), 0777, true);
                            @file_put_contents($fileName, $body);
                        }
                    }
                } else {
                    $body = @file_get_contents($fileName);
                }
                if (!empty($body)) {
                    $content = $content . $body . "\r\n";
                }
            }
            if (!empty($content)) {
                @mkdir(dirname($routFile), 0777, true);
                @file_put_contents($routFile, $content);
            }
        }
    }

    /**
     * 下载
     * @param array $down   下载列表[保存路径=>下载地址]
     * @param mixed $save   保存路径
     * @param bool  $update 是否强制更新
     * @param bool  $cli    是否cli
     * @return void
     */
    public static function downFile(array $down, mixed $save, bool $update = false, bool $cli = false): void {
        $CssPath = "";
        $CssUrl = "";
        foreach ($down as $k => $v) {
            $file = $save . trim($k, '/');
            if (empty(is_file($file)) || !empty($update)) {
                $path = static::down($file, $v, $cli);
                if (strtolower(basename($v)) == "layui.css") {
                    $CssPath = $path;
                    $CssUrl = $v;
                }
            }
        }
        static::layCss($CssPath, $CssUrl, $cli);
        if ($cli) {
            print_r("============================================================================\r\n");
        }
    }

    /**
     * 下载layui.css里面的文件
     * @param string $path layui.css路径
     * @param string $url  layui.css下载地址
     * @param bool   $cli  是否cli
     * @return void
     */
    public static function layCss(string $path, string $url, bool $cli = false): void {
        if (!empty($path)) {
            $css = @file_get_contents($path);
            if ($css) {
                $pattern = '/url\(\s*[\'"]?\.\.\/font\/([^"\'\s\)?#]+)/i';
                preg_match_all($pattern, $css, $matches);
                if (!empty($list = ($matches[1] ?? []))) {
                    $list = array_unique($list);
                    foreach ($list as $item) {
                        $file = rtrim(dirname($path, 2), '/') . '/font/' . trim($item);
                        if (empty(is_file($file)) || !empty($update)) {
                            static::down($file, trim(dirname($url, 2), '/') . "/font/" . trim($item), $cli);
                        }
                    }
                }
            }
        }
    }

    /**
     * 下载文件
     * @param string $path 保存路径
     * @param string $url  下载地址
     * @param bool   $cli  是否cli
     * @return string
     */
    public static function down(string $path, string $url, bool $cli = false): string {
        $pro = 0;
        if ($cli) {
            print_r("============================================================================\r\n");
            print_r("url: " . $url . "\r\n");
            print_r("path: " . $path . "\r\n");
        }
        $body = static::curl($url, $cli ? (function($progress) use ($url, &$pro) {
            $pro = $progress;
            print_r("progress: $progress%\r");
        }) : null);
        if ($cli) {
            print_r("progress:$pro%\r\n");
        }
        if (!empty($body)) {
            @mkdir(dirname($path), 0777, true);
            @file_put_contents($path, $body);
        }
        return $path;
    }

    /**
     * curl
     * @param string        $url      下载地址
     * @param callable|null $progress 回调包
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
     * 回调
     * @param string $name      方法名
     * @param array  $arguments 参数
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

    /**
     * 替换内容
     * @param string|null $string 要替换的string
     * @param array       $array  ['key'=>'要替换的内容']
     * @param string      $symbol key前台符号
     * @return string
     */
    public static function tag(string|null $string, array $array = [], string $symbol = '%'): string {
        if (!empty($string)) {
            $array = array_combine(array_map(fn($key) => ($symbol . $key . $symbol), array_keys($array)), array_values($array));
            $result = strtr($string, $array);
            $result = preg_replace("/" . $symbol . "[^" . $symbol . "]+" . $symbol . "/", '', $result);
            $string = trim($result);
        }
        return $string ?? '';
    }
}