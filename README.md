# 前端js

### 安装

```text
composer require alone-webman/js
```

### 命令

```text
php webman alone:js
```

### 加载css和js

```javascript
/**
 *
 * @param {function|Array} [callback]
 * @param {function|Array} [loading]
 */
function alone_app(callback, loading = []) {
    loading = typeof loading === 'undefined' ? JSON.parse("%loaderIng%") : loading;
    const call = typeof callback === 'function' ? callback : (typeof loading === 'function' ? loading : () => null);
    const load = Array.isArray(callback) ? callback : (Array.isArray(loading) ? loading : []);
    let length = load.length;
    load.forEach(function (file) {
        if (file.toLowerCase().endsWith('.css')) {
            const link = document.createElement('link');
            link.setAttribute('href', file);
            link.setAttribute('type', 'text/css');
            link.setAttribute('rel', 'stylesheet');
            link.onload = () => (length--, (length === 0) && call());
            document.head.appendChild(link);
        } else {
            const script = document.createElement('script');
            script.setAttribute('type', 'text/javascript');
            script.setAttribute('src', file);
            script.onload = () => (length--, (length === 0) && call());
            document.body.appendChild(script);
        }
    });
}
```

### 使用

```html
<!doctype html>
<html lang="zh">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1">
    <title>vue</title>
    <script type="text/javascript" src="/alone/app.js"></script>
</head>
<body>
<div id="app">
    <router-view></router-view>
</div>
<script>
    alone_app(function () {
        const {createApp} = Vue;
        const {createRouter, createWebHashHistory} = VueRouter;
        const {createI18n} = VueI18n;
        const router = createRouter({
            history: createWebHashHistory(), routes: [
                {
                    path: '/',
                    component: {
                        template: '<div>{{ $t("home.welcome") }}</div>'
                    }
                },
                {
                    path: '/about',
                    component: {
                        template: '<div>{{ $t("about.title") }}</div>'
                    }
                }
            ]
        });
        const i18n = createI18n({
            locale: 'zh', fallbackLocale: 'en', messages: {
                zh: {
                    home: {
                        welcome: '欢迎来到首页'
                    },
                    about: {
                        title: '关于我们'
                    }
                },
                en: {
                    home: {
                        welcome: 'Welcome to Home'
                    },
                    about: {
                        title: 'About Us'
                    }
                }
            }
        });
        const app = createApp({});
        app.use(router);
        app.use(i18n);
        app.mount('#app');
        window.changeLanguage = function (locale) {
            i18n.global.locale = locale;
        };
    });
</script>
</body>
</html>
```