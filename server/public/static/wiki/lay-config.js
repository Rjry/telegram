window.ENV = {
    STATIC_PATH: (function(src) {
        src = document.scripts[document.scripts.length - 1].src;
        return src.substring(0, src.lastIndexOf("/") + 1);
    })()
}

layui.config({
    // 用于本地加载框架，开启则需删除页面script节点
    // dir: '/layui/layui.js',
    // 用于开启调试模式，默认false，如果设为true，则JS模块的节点会保留在页面
    debug: true,
    // 用于更新模块缓存，默认不开启，设为true即让浏览器不缓存，也可以设为一个固定的值
    version: true,
    // 用于设定外部扩展模块的所在目录
	base: ENV.STATIC_PATH + 'extend/',
}).extend({
    fn: 'fn',
    wiki: 'wiki',
}).use(['jquery', 'layer', 'fn'], function() {
    
    var $     = layui.$,
        layer = layui.layer,
        fn    = layui.fn;
        
});