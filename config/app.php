<?php
return [
    'enable'  => true,
    /*
     * 访问路径
     */
    'path'    => "alone/js",
    /*
     * 保存位置空时默认位置(绝对路径)
     */
    'save'    => "",
    /*
     * 设置下载列表 (相对路径=>下载地址)
     */
    'down'    => [
        "layui/css/layui.css" => "https://unpkg.com/layui@latest/dist/css/layui.css",
        "layui/layui.js"      => "https://unpkg.com/layui@latest/dist/layui.js",
        "vue/vue.js"          => "https://unpkg.com/vue@latest/dist/vue.global.prod.js",
        "vue/vue-router.js"   => "https://unpkg.com/vue-router@latest/dist/vue-router.global.prod.js",
        "vue/vue-i18n.js"     => "https://unpkg.com/vue-i18n@latest/dist/vue-i18n.global.prod.js"
    ],
    /*
     * 路由文件 (保存的相对路径),"/开头自定绝对路径"
     */
    'route'   => [
        'alone/vue.js' => ["vue/vue.js", "vue/vue-router.js", "vue/vue-i18n.js"]
    ],
    /*
     * alone_app加载 (完全的访问路径)
     */
    "loading" => [
        "/alone/js/layui/css/layui.css",
        "/alone/js/layui/layui.js",
        "/alone/vue.js"
    ]
];