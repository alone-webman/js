<?php
return [
    'enable' => true,
    /*
     * 调用路径
     */
    'path'   => "alone/js",
    /*
     * 设置下载列表
     */
    'down'   => [
        "layui/css/layui.css" => "https://unpkg.com/layui@latest/dist/css/layui.css",
        "layui/layui.js"      => "https://unpkg.com/layui@latest/dist/layui.js",
        "vue/vue.js"          => "https://unpkg.com/vue@latest/dist/vue.global.prod.js",
        "vue/vue-router.js"   => "https://unpkg.com/vue-router@latest/dist/vue-router.global.prod.js",
        "vue/vue-i18n.js"     => "https://unpkg.com/vue-i18n@latest/dist/vue-i18n.global.prod.js"
    ],
    /*
     * 集合调用
     */
    'route'  => [
        'alone/js/facade.js' => ["layui/layui.js", "vue/vue.js", "vue/vue-router.js", "vue/vue-i18n.js"]
    ]
];