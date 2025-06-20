<?php

namespace AloneWebMan\Js;

class VueClass {
    protected string $file  = "";
    protected string $body  = "";
    protected string $code  = "";
    protected array  $array = ["template" => [], "script" => [], "style" => []];
    protected array  $arr   = ["template" => "", "script" => "", "style" => "", "scoped" => ""];


    /**
     * @param string $file
     * @return static
     */
    public static function get(string $file): static {
        return (new static())->process($file)->handle();
    }

    /**
     * 获取转换代码
     * @return string
     */
    public function getCode(): string {
        return trim($this->code) ?: static::compressed($this->body);
    }

    /**
     * 压缩代码
     * @param string $code
     * @return string
     */
    public static function compressed(string $code): string {
        $code = preg_replace('/\/\*[\s\S]*?\*\//', '', $code);
        $code = preg_replace('/<!--[\s\S]*?-->/', '', $code);
        $code = preg_replace('/\/\*[\s\S]*?\*\//', '', $code);
        $code = preg_replace('/;}/', '}', $code);
        $strings = [];
        $code = preg_replace_callback('/("|\')(?:\\\\\1|.)*?\1/', function($m) use (&$strings) {
            $key = '___STRING' . count($strings) . '___';
            $strings[$key] = $m[0];
            return $key;
        }, $code);
        $code = preg_replace('/\/\/.*/', '', $code);           // 单行注释
        $code = preg_replace('/\/\*[\s\S]*?\*\//', '', $code); // 多行注释
        foreach ($strings as $key => $value) {
            $code = str_replace($key, $value, $code);
        }
        return preg_replace('/\n\s*\n/', "\n", $code);
    }

    /**
     * 压缩代码
     * @param string $code
     * @return string
     */
    public static function compressedOld(string $code): string {
        $code = preg_replace('/\/\*[\s\S]*?\*\//', '', $code);
        $code = preg_replace('/\/\/.*/', '', $code);
        $code = preg_replace('/<!--[\s\S]*?-->/', '', $code);
        $code = preg_replace('/\/\*[\s\S]*?\*\//', '', $code);
        $code = preg_replace('/\s*([{}:;,])\s*/', '$1', $code);
        $code = preg_replace('/;}/', '}', $code);
        return preg_replace('/\n\s*\n/', "\n", $code);
    }

    /**
     * 给 export default添加代码
     * @param string $code
     * @param string $new
     * @return string
     */
    public static function addExportCode(string $code, string $new): string {
        $trimmedNewCode = trim($new);
        $objectPattern = '/export\s+default\s*\{/';
        if (preg_match($objectPattern, $code, $matches, PREG_OFFSET_CAPTURE)) {
            $pos = ((float) $matches[0][1]) + ((float) strlen($matches[0][0]));
            return substr_replace($code, "\n    " . $trimmedNewCode, $pos, 0);
        }
        $functionPatterns = [
            '/export\s+default\s+async\s+function\s*\([^)]*\)\s*\{/',
            '/export\s+default\s+function\s*\([^)]*\)\s*\{/',
            '/export\s+default\s*\([^)]*\)?\s*=>\s*\{/'
        ];
        foreach ($functionPatterns as $pattern) {
            if (preg_match($pattern, $code, $matches, PREG_OFFSET_CAPTURE)) {
                $returnPattern = '/(\s*)(return\s*\{)/';
                if (preg_match($returnPattern, $code, $returnMatches, PREG_OFFSET_CAPTURE, $matches[0][1])) {
                    $indent = $returnMatches[1][0];
                    $replacement = $returnMatches[0][0] . "\n" . $indent . "    " . $trimmedNewCode;
                    return substr_replace($code, $replacement, $returnMatches[0][1], strlen($returnMatches[0][0]));
                }
            }
        }
        return $code;
    }

    /**
     * 处理代码
     * @param string $file
     * @return $this
     */
    protected function process(string $file): static {
        $this->file = $file;
        $this->body = @file_get_contents($this->file);
        preg_match_all('/<(template|script|style)\s*([^>]*)>([\s\S]*?)<\/\1>/', $this->body, $matches, PREG_SET_ORDER);
        foreach ($matches as $match) {
            $type = strtolower(trim($match[1]));
            $this->array[$type][] = ['attr' => trim($match[2]), 'data' => trim($match[3])];
            if ($type === 'style') {
                if (str_contains(strtolower(trim($match[2])), 'scoped')) {
                    $this->arr["scoped"] .= trim($match[3]) . "\n";
                } else {
                    $this->arr["style"] .= trim($match[3]) . "\n";
                }
            } else {
                $this->arr[$type] .= trim($match[3]) . "\n";
            }
        }
        return $this;
    }

    /**
     * @return static
     */
    protected function handle(): static {
        if (count($this->array['template']) > 0) {
            $this->code = static::addExportCode($this->arr["script"], "\ntemplate:`" . $this->arr["template"] . "`,\n");
            $style = "";
            $uuid = substr(md5($this->file), 8, 16);
            if ($this->arr["style"]) {
                $style .= "\n__{$uuid}__(`" . $this->arr["style"] . "`,'css-v-" . $uuid . "','style');\n";
            }
            if ($this->arr["scoped"]) {
                $style .= "\n__{$uuid}__(`" . $this->arr["scoped"] . "`,'css-v-" . $uuid . "','scoped');\n";
            }
            if ($style) {
                $jsCode = <<<JS
(!function (){
const __{$uuid}__ = function (e, t, n) {
    if (!document.querySelector("[" + t + "=\"" + n + "\"]")) {
        let d = document.createElement("style");
        d.textContent = e, d.setAttribute(t, n), document.head.appendChild(d)
    }
};
{$style}
}())
JS;
                $this->code = $jsCode . "\n" . $this->code;
            }
            $this->code = static::compressed($this->code);
        }
        return $this;
    }
}