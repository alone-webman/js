/**
 *
 * @param {function|Array} [callback]
 * @param {function|Array} [loading]
 */
function alone_app(callback, loading) {
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