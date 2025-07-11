/**
 * aloneApp使用提示
 * === app配置 ===
 * @typedef {Object} appConfig
 * @property {string}        [elem]        容器（默认 app）
 * @property {string}        [home]        路由主页（默认 index）
 * @property {boolean}       [match]       任意路由开关（默认 true）
 * @property {string}        [jump]        路由不存在或者出错时跳转（默认 /）
 * @property {object}        [group]       路由分组（默认 {}）
 * @property {object}        [i18n]        设置i18n  （默认 {} ）
 * @property {object}        [lang]        i18n语文包（默认 {}） messages
 * @property {object}        [router]      路由设置（默认 {}）
 * @property {array}         [routes]      路由列表（默认 []）
 * @property {number}        [routeType]   路由类型（默认 1）1=watch 2=watchEffect 3=isReady
 * @property {string|null}   [app]         app主入口路径
 * @property {string|null}   [main]        main配置文件路径
 */
/**
 * === 路由配置 ===
 * @typedef {Object} routeConfig
 * @property {string}                        [view]        请求视图（默认 /）
 * @property {string}                        [format]      请求格式（默认 "vue"）
 * @property {number}                        [timeout]     超时时间（默认 20000）
 * @property {boolean}                       [blob]        是否使用blob使用（默认 false）
 * @property {boolean}                       [cache]       缓存开关（默认 true）
 * @property {boolean|function(code,type)}   [compress]    是否压缩,可以以自定压缩
 * @property {function(body,path,type,xhr)}  [reqBody]     请求后内容处理
 * @property {string}                        [jsFormat]    js中的import没有格式是否补格式 (默认 js）可设js|vue等,不要.
 * @property {string}                        [cssFormat]   css中的import没有格式是否补格式 (默认 css） 可设css等,不要.
 * @property {string|array|null}             [importType]  直接import的类型（默认 null）如js:application/javascript
 * @property {string}                        [alone]       default中的参数名称（默认 alone) 可设置title/description/keywords/favicon/link/script//head[name:{tag:"",content:"",attr:{}}]
 * @property {number}                        [delay]       状态设置 延迟显示（ms，默认 300）
 * @property {function}                      [loading]     路由加载中处理包,返回显示的内容（默认 null）
 * @property {function}                      [error]       路由加载错误处理包,返回显示的内容（默认 null）
 */
window.aloneApp = {
    /**
     * app初始化
     * @param {appConfig&routeConfig} option
     *
     */
    app(option = {
        app: null,
        main: null,
        elem: "app",
        home: "index",
        match: true,
        jump: "/",
        group: null,
        i18n: {},
        lang: {},
        router: null,
        routes: [],
        routeType: 1,
        view: "/view/",
        format: "vue",
        timeout: 20000,
        blob: false,
        cache: true,
        compress: true,
        reqBody: (body) => body,
        jsFormat: "js",
        cssFormat: "css",
        delay: 200,
        loading: null,
        error: null,
        importType: null,
        alone: "alone"
    } = {}) {
    },
    vue: Vue,
    vueRouter: VueRouter,
    vueI18n: VueI18n,
    main: Vue.createApp(),
    i18n: VueI18n.createI18n(),
    router: VueRouter.createRouter(),
    i18nConfig: {},
    routerConfig: {},
    groupConfig: {},
    /**
     * 设置或者获取参数
     * @param {string|object} [key]  参数名字
     * @param {any}           [val] 设置的参数
     * @returns {any}
     */
    conf(key, val) {
    },
    /**
     * Vue.createApp().use()
     * @param param
     */
    use(...param) {
    },
    /**
     * vue的this,代替this
     * @param {boolean|null} [type]
     * @returns {*|{}|{}}
     */
    curr(type = null) {
    },
    /**
     * 获取语言
     * @param {string|number} key
     * @param {string|number} [def] 没找到语言时返回
     * @returns {*}
     */
    lang(key, def) {
    },
    /**
     * 设置或者获取语言类型别
     * @param {string} [key]
     * @returns {*}
     */
    language(key) {
    },
    /**
     * 设置语言
     * @param {object}  val
     * @param {string}  [lang]
     * @returns {*}
     */
    setLang(val, lang = '') {
    },
    /**
     * 路由跳转
     * @param {string|number} path
     * @param {string|number} [name]
     * @param {object} [params]
     * @param {object} [query]
     * @param {string} [hash]
     */
    jumpRoute(path, {name = '', params = {}, query = {}, hash = ''} = {}) {
    },
    /**
     * 获取当前路由参数
     * @param {string} [key]
     * @returns {any}
     */
    getRoute(key = '') {
    },
    /**
     * 获取路由列表
     * @returns {any}
     */
    getRoutes() {
    },
    /**
     * 路由文件获取
     * @param {routeConfig} option
     * @returns {object}
     */
    route(option = {}) {
    },
    /**
     * 组件包
     * @param {string}                     path      路径
     * @param {number|null}                [delay]   延迟显示
     * @param {number|null}                [timeout] 超时时间
     * @param {function(path)|null}        [loading] 加载过序
     * @param {function(path,error)|null}  [error]   错误提示
     * @returns {{template: string, components: {aloneTemp}}|{errTemp}|{loadTemp}}
     */
    component(path, {delay = null, timeout = null, loading = null, error = null} = {}) {
    },
    /**
     * 创建模板
     * @param {string}                     path      路径
     * @param {number|null}                [delay]   延迟显示
     * @param {number|null}                [timeout] 超时时间
     * @param {function(path)|null}        [loading] 加载过序
     * @param {function(path,error)|null}  [error]   错误提示
     * @returns {{template: string, components: {aloneTemp}}|{errTemp}|{loadTemp}}
     */
    components(path, {delay = null, timeout = null, loading = null, error = null} = {}) {
    },
    /**
     * 获取路由内容
     * @param {string} path
     * @returns {object}
     */
    async getVue(path) {
    },
    routeWay: {
        /**
         * 分组路由配置
         * @param {object|null}     [group]     分组列表，支持无限级嵌套
         * @param {function|object} [callback]  组件处理回调配置
         * @param {string}          [prefix=""] 路由名称前缀
         * @returns {array}
         */
        groupRoute(group, callback, prefix = "") {
        },
        /**
         * 组件包
         * @param {string}                     path   路径
         * @param {function(path)}             loader 加载包
         * @param {number|null}                [delay]
         * @param {number|null}                [timeout]
         * @param {function(path)|null}        [loading]
         * @param {function(path,error)|null}  [error]
         * @returns {{template: string, components: {aloneTemp}}|{errTemp}|{loadTemp}}
         */
        component(path, loader, {delay = null, timeout = null, loading = null, error = null} = {}) {
        },
        /**
         * 组件包
         * @param {string}                     path   路径
         * @param {function(path)}             loader 加载包
         * @param {number|null}                [delay]
         * @param {number|null}                [timeout]
         * @param {function(path)|null}        [loading]
         * @param {function(path,error)|null}  [error]
         * @returns {{template: string, components: {aloneTemp}}|{errTemp}|{loadTemp}}
         */
        components(path, loader, {delay = null, timeout = null, loading = null, error = null} = {}) {
        }
    },
    /**
     *  这是crypto
     */
    crypto: crypto,
    safe: {
        crypto: aloneApp.crypto,
        utils: {
            rand() {
            },
            to_json(data) {
            },
            to_obj(data) {
            },
            mov(name) {
            },
            mov_rand(name) {
            }
        },
        md5(data) {
        },
        sha1(data) {
        },
        sha3(data) {
        },
        sha256(data) {
        },
        sha512(data) {
        },
        base64: {},
        aes: {
            encrypt(data, key, iv) {
            },
            decrypt(data, key, iv) {
            },
            en(data) {
            },
            de(data, random) {
            }
        },
        des: {
            encrypt(data, key, iv) {
            },
            decrypt(data, key, iv) {
            },
            en: (data) => {
            },
            de(data, random) {
            }
        },
        des3: {
            encrypt(data, key, iv) {
            },
            decrypt(data, key, iv) {
            },
            en(data) {
            },
            de(data, random) {
            }
        },
        mov: {
            en(data, mode) {
            },
            de(data, random) {
            }
        },
        url: {
            en(data, mode) {
            },
            de(data) {
            }
        }
    },
    utils: {
        /**
         * 等待时间/毫秒,要使用 await
         * @param {number}    ms
         * @param {function} [cb]
         * @returns {Promise<unknown>}
         */
        sleep(ms, cb) {
        },
        /**
         * 创造App
         * @param {string} id
         * @param {string} [html]
         * @returns {HTMLElement}
         */
        app(id, html) {
        },
        /**
         * 设置标题
         * @param {string} title
         */
        setTitle(title) {
        },
        /**
         * 设置mate标签
         * @param {string} name - meta名称 (description/keywords)
         * @param {string} content - meta内容
         */
        setMetaTag(name, content) {
        },
        /**
         * 设置favicon.ico
         * @param ico
         * @param [attr]
         * @returns {*}
         */
        setFavicon(ico, attr = {}) {
        },
        /**
         * 设置css文件
         * @param file
         * @param awaits
         * @returns {HTMLLinkElement|Promise<unknown>}
         */
        setLink(file, awaits = false) {
        },
        /**
         * 加载html
         * @returns {string}
         */
        loadHtml() {
        },
        /**
         * 设置js文件
         * @param file
         * @param callback
         * @returns {HTMLScriptElement|Element|Promise<unknown>}
         */
        setScript(file, callback) {
        },
        /**
         * 兼容性封装
         * @param {string} html
         * @param {string} type
         * @param {boolean} mode
         * @returns {Document}
         */
        parseHTML(html, type = 'text/html', mode = true) {
        },
        /**
         * 拼接路径
         * @param {string} base
         * @param {string} [path] 拼接目录 支持./ ../../
         */
        getPath(base, path = "") {
        },
        /**
         * 代码生成blob
         * @param {string} code
         * @param {string} [type]
         * @returns {string}
         */
        blob(code, type = "js") {
        },
        /**
         * 删除两边
         * @param {string} str  字符串
         * @param {any}    char 符号
         * @returns {*}
         */
        trim(str, char = "/") {
        },
        /**
         * 去除字符串右侧的指定字符
         * @param {string} str  字符串
         * @param {any}    char 要去除的字符，默认为"/"
         * @returns {string} 处理后的字符串
         */
        rtrim(str, char = "/") {
        },
        /**
         * 获取格式
         * @param {string} path
         * @returns {string}
         */
        getPathFormat(path) {
        },
        /**
         * 生成唯一标识
         * @returns {string}
         */
        uuid() {
        },
        /**
         * 计算hash
         * @param {string} str
         * @returns {string}
         */
        hash(str) {
        },
        /**
         * 只获取路径
         * @param url
         * @returns {string}
         */
        getPathName(url) {
        },
        /**
         * 获取url参数
         * @param {string}  url
         * @param {object}  [params]
         * @returns {object|string}
         */
        getParams(url = "", params = {}) {
        }
    },
};