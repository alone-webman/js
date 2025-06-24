<?php
return [
    'enable' => true,
    /*
     * 访问路径
     */
    'path'   => "style/alone",
    /*
     * 保存位置空时默认位置(绝对路径)
     */
    'save'   => __DIR__ . '/file',
    /*
     * 下载那个列表
     */
    'down'   => "dev",
    /*
     * 设置下载列表 (相对路径=>下载地址)通过/alone/js/layui/css/layui.css访问
     */
    "config" => [
        'dev'   => [
            "layui/css/layui.css" => "https://unpkg.com/layui@latest/dist/css/layui.css",
            "layui/layui.js"      => "https://unpkg.com/layui@latest/dist/layui.js",
            "vue/vue.js"          => "https://unpkg.com/vue@latest/dist/vue.global.js",
            "vue/vue-router.js"   => "https://unpkg.com/vue-router@latest/dist/vue-router.global.js",
            "vue/vue-i18n.js"     => "https://unpkg.com/vue-i18n@latest/dist/vue-i18n.global.js"
        ],
        'style' => [
            "layui/css/layui.css" => "https://unpkg.com/layui@latest/dist/css/layui.css",
            "layui/layui.js"      => "https://unpkg.com/layui@latest/dist/layui.js",
            "vue/vue.js"          => "https://unpkg.com/vue@latest/dist/vue.global.prod.js",
            "vue/vue-router.js"   => "https://unpkg.com/vue-router@latest/dist/vue-router.global.prod.js",
            "vue/vue-i18n.js"     => "https://unpkg.com/vue-i18n@latest/dist/vue-i18n.global.prod.js"
        ]
    ],
    /*
     * 路由文件 (保存的相对路径),"/开头自定绝对路径"
     */
    'route'  => [
        'style/alone/vue.js' => [
            "vue/vue.js",
            "vue/vue-router.js",
            "vue/vue-i18n.js"
        ]
    ]
];