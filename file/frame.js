/**
 * aloneApp.app(appConfig)
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
 *
 * === 路由配置 ===
 * @property {string}                        [view]        请求视图（默认 /）
 * @property {string}                        [format]      请求格式（默认 vue）
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
const frame = {
    /**
     * app对像
     */
    app: aloneApp,
    /**
     * 扩展
     */
    utils: aloneApp.utils,
    /**
     * crypto
     */
    crypto: aloneApp.crypto,
    /**
     * safe
     */
    safe: aloneApp.safe,
    /**
     * createApp
     */
    main: aloneApp.main,
    /**
     * i18n
     */
    i18n: aloneApp.i18n,
    /**
     * router
     */
    router: aloneApp.router,
    /**
     * createApp.use
     * @param param
     * @returns {*}
     */
    use(...param) {
        return aloneApp.use(...param);
    },
    /**
     * 设置或者获取参数
     * @param {appConfig|object|string} [key]  参数名字
     * @param {any}                     [val] 设置的参数
     * @returns {any}
     */
    conf(key, val) {
        return aloneApp.conf(key, val);
    },
    /**
     * 获取语言
     * @param {string|number} key
     * @param {string|number} [def] 没找到语言时返回
     * @returns {*}
     */
    lang(key, def) {
        return aloneApp.lang(key, def);
    },
    /**
     * 设置或者获取语言类型别
     * @param {string} [key]
     * @returns {*}
     */
    language(key) {
        return aloneApp.language(key);
    },
    /**
     * 设置语言
     * @param {object}  val    语言内容
     * @param {string}  [lang] 语言类型
     * @returns {*}
     */
    setLang(val, lang = '') {
        return aloneApp.setLang(val, lang);
    },
    /**
     * vue的this,代替this
     * @param type
     * @returns {*|{}|{}}
     */
    vue(type = null) {
        return aloneApp.vue(type);
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
        return aloneApp.jumpRoute(path, {name, params, query, hash});
    },
    /**
     * 获取当前路由参数
     * @param {string} [key]
     * @returns {any}
     */
    getRoute(key = '') {
        return aloneApp.getRoute(key);
    },
    /**
     * 获取路由列表
     * @returns {any}
     */
    getRoutes() {
        return aloneApp.getRoutes();
    },
    /**
     * 组件包
     * @param {string}                     path   路径
     * @param {number|null}                [delay]
     * @param {number|null}                [timeout]
     * @param {function(path)|null}        [loading]
     * @param {function(path,error)|null}  [error]
     * @returns {{template: string, components: {aloneTemp}}|{errTemp}|{loadTemp}}
     */
    component(path, {delay = null, timeout = null, loading = null, error = null} = {}) {
        return aloneApp.component(path, {delay, timeout, loading, error});
    },
    /**
     * 创建组件
     * @param {string}                     path   路径
     * @param {number|null}                [delay]
     * @param {number|null}                [timeout]
     * @param {function(path)|null}        [loading]
     * @param {function(path,error)|null}  [error]
     * @returns {{template: string, components: {aloneTemp}}|{errTemp}|{loadTemp}}
     */
    components(path, {delay = null, timeout = null, loading = null, error = null} = {}) {
        return aloneApp.components(path, {delay, timeout, loading, error});
    },
    /**
     * 获取路由内容
     * @param {string} path
     * @returns {any}
     */
    async getVue(path) {
        return await aloneApp.getVue(path);
    }
};

export default frame;