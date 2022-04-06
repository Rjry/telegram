layui.define(['jquery','layer','element','laytpl','table','fn'], function(exports) {

    var $       = layui.$,
        layer   = layui.layer,
        element = layui.element,
        laytpl  = layui.laytpl,
        table   = layui.table,
        fn      = layui.fn;
    
    /*检索文本*/
    var skey  = '';
    
    /*接口列表*/
    var rList = [];
    
    /*已选接口*/
    var tTabs = [];
    
    /*当前接口*/
    var tPath = '';
    
    var main = {
        
        // 初始化
        init: () => {
            /*清除缓存*/
            fn.bind.event.click({
                doCls: () => {
                    layer.confirm('确定此操作？', {
                        icon: 3,
                        title: '提示'
                    }, function(index) {
                        main.cls(res => {
                            main.tab('home');
                        });
                        layer.close(index);
                    });
                }
            });
            /*侧栏伸展*/
            $(document).on('click', '.warp-side-top', function() {
                var bool = $('#shrink').hasClass('layui-icon-shrink-right');
                renderSideTab(bool ? false : true);
            });
            /*侧栏点击*/
            $(document).on('click', '.sTab', function() {
                var name = $(this).attr('name');
                if (name != '') {
                    $('.warp-side-item').removeClass('active');
                    $('.warp-side-item[name=' + name + ']').addClass('active');
                }
                renderSideTab(true);
            });
            /*侧栏提示*/
            $('.sTab').on('mouseover', function() {
                var that = $(this),
                    msg  = that.context.dataset.tips;
                layer.tips(msg, that, {
                    tips: [2, '#1A2025'],
                    time: 1000
                });
            });
            /*检索过滤*/
            $("input#tListFilter").bind('input propertychange', function() {
                skey = $(this).val();
                main.render.list();
            });
            /*接口点击*/
            $(document).on('click', '.tBox', function() {
                var path = $(this).attr('data-val');
                main.get(path, d => {
                    main.render.tabs(d);
                });
            });
            /*选项卡切换*/
            element.on('tab(tab)', function(data) {
                var eid = $(this)[0]['attributes']['lay-id']['value'];
                tPath = eid;
            });
            /*选项卡删除*/
            element.on('tabDelete(tab)', function(data) {
                var eid  = $(this).parent()[0]['attributes']['lay-id']['value'];
                if (eid != 'home') {
                    fn.arr.del(tTabs,eid);
                }
            });
            /*复制参数*/
            $(document).on('click', '.tCopy', function() {
                var val = $(this).text(),
                    val = val.replace(/^\s+|\s+$/g,'');
                fn.copy(val);
            });
            /*执行按钮*/
            $(document).on('click', '.btn-run', function() {
                main.run();
            });
            /*监听按键*/
            document.onkeydown = function (event) {
                var e = event || window.event;
                const keyCode  = e.keyCode || e.which || e.charCode;
                // console.log(keyCode);return ;
                const keyCombo = e.altKey;
                /*侧栏收缩 | Ctrl + Q*/
                if (keyCombo && keyCode == 81) {
                    $('.warp-side-top').click();
                }
                /*执行按钮 | Ctrl + R*/
                if (keyCombo && keyCode == 82) {
                    main.run();
                }
                /*检索过滤 | Ctrl + S*/
                if (keyCombo && keyCode == 83) {
                    main.tab('home');
                    $('#tListFilter').focus();
                }
            };
            /*滚动条回滚*/
            $(document).on('click', '.layui-icon-top', function() {
                $('.layui-tab-content').animate({scrollTop: 0}, 360);
            });
            /*渲染接口令牌*/
            main.render.token();
            /*渲染全局返码*/
            main.render.retCode();
            /*渲染首页视图*/
            main.render.home();
            /*获取接口文档*/
            main.doc();
        },
        
        // 切换侧栏
        tab: name => {
            $('.sTab[name=' + name + ']').click();
        },
        
        // 获取文档
        doc: callback => {
            wiki('get', '', d => {
                rList = d;
                main.render.list();
                callback && callback();
            });
        },
        
        // 获取详情
        get: (path, callback) => {
            wiki('get', path, d => {
                callback && callback(d);
            });
        },
        
        // 清除缓存
        cls: callback => {
            wiki('cls', '', d => {
                layer.msg('操作成功', {time: 640}, function() {
                    fn.tpl('tListTpl', 'tListView', []);
                    main.doc( d => {
                        callback && callback(d);
                    });
                });
            });
        },
        
        // 执行接口
        run: () => {
            if (tPath == 'home') {
                logs();
                return false;
            }
            var path   = tPath;
            var hash   = tPath.replace(/\./g,'');
            var method = $('#apiMethod_' + hash).attr('data-val');
            var tbData = layui.table.cache['apiToken_' + hash],
                token  = tbData.length == 0 ? '' : tbData[0]['token'];
            var tbData = layui.table.cache['apiParamsTable_' + hash],
                params = {};
            if (tbData.length != 0) {
                layui.each(tbData, function(i, v) {
                    if (v.val != '') params[v.key] = v.val;
                });
            }
            var repBox = $('#apiReponseBox_' + hash);
            repBox.html('请求中...');
            apiRequest(path, method, params, token, res => {
                repBox.JSONView(res);
            });
        },
        
        // 视图渲染
        render: {
            list: () => {
                var tList = [];
                rList.forEach(item => {
                    if (item.indexOf(skey) !== -1) tList.push(item);
                });
                fn.tpl('tListTpl', 'tListView', tList);
            },
            token: () => {
                var ddd = fn.store.local.get('temp_token_data') || false;
                if ( !ddd ) {
                    ddd = {
                        d1: {
                            id: 'd1',
                            title: '',
                            token: '',
                            nbars: '<i class="layui-icon layui-icon-next token-chose" title="传送"></i>',
                        },
                        d2: {
                            id: 'd2',
                            title: '',
                            token: '',
                            nbars: '<i class="layui-icon layui-icon-next token-chose" title="传送"></i>',
                        },
                        d3: {
                            id: 'd3',
                            title: '',
                            token: '',
                            nbars: '<i class="layui-icon layui-icon-next token-chose" title="传送"></i>',
                        },
                        d4: {
                            id: 'd4',
                            title: '',
                            token: '',
                            nbars: '<i class="layui-icon layui-icon-next token-chose" title="传送"></i>',
                        },
                        d5: {
                            id: 'd5',
                            title: '',
                            token: '',
                            nbars: '<i class="layui-icon layui-icon-next token-chose" title="传送"></i>',
                        }
                    };
                    fn.store.local.set('temp_token_data',ddd);
                }
                var arr = [];
                for (var i in ddd) {
                    arr.push(ddd[i]);
                }
                table.render({
                    elem: '#tokenTable',
                    cols: [
                        [
                            {field: 'title', title: '名称', edit: true, width: 100, align: 'left', unresize: true},
                            {field: 'token', title: '令牌', edit: true, align: 'left', unresize: true},
                            {field: 'nbars', title: '操作', width: 60, align: 'center', unresize: true},
                        ]
                    ],
                    data: arr,
                    limit: 9999,
                    skin: 'row line',
                    text: {none: '暂无令牌'}
                });
                /*数据编辑*/
                table.on('edit(tokenTable)', function(obj) { 
                    var ddd = fn.store.local.get('temp_token_data');
                    ddd[obj.data.id] = obj.data;
                    fn.store.local.set('temp_token_data',ddd);
                });
                /*传送令牌*/
                $(document).on('click', '.token-chose', function() {
                    if (tPath == '') return ;
                    var token = $(this).parents('td').prev('td').find('div').text();
                    if (token == '') return ;
                    table.reload('apiToken_' + tPath.replace(/\./g,''), {data: [{token: token}]});
                });
            },
            retCode: () => {
                wiki('code', '', res => {
                    fn.tpl('tCodeTpl', 'tCodeView', res);
                });
            },
            home: () => {
                var tpl = tHomeTpl.innerHTML;
                laytpl(tpl).render([], function(html) {
                    var eid = 'home';
                    element.tabAdd('tab', {
                        id: eid,
                        title: '日志',
                        content: html,
                    });
                    element.tabChange('tab', eid);
                });
                logs();
            },
            tabs: data => {
                var tpl = aTpl.innerHTML;
                laytpl(tpl).render(data, function(html) {
                    var eid = data.apiPath;
                    if ( fn.arr.has(tTabs, eid) ) {
                        element.tabChange('tab', eid);
                    } else {
                        fn.arr.add(tTabs, eid);
                        element.tabAdd('tab', {
                            id: eid,
                            title: data.apiName,
                            content: html,
                        });
                        element.tabChange('tab', eid);
                        /*渲染令牌视图*/
                        renderApiTokenTable(data);
                        /*渲染参数视图*/
                        renderApiParamsTable(data);
                    }
                });
            }
        },
        
    };
    
    // 渲染侧栏视图
    function renderSideTab(bool) {
        var objt = $('#shrink');
        if (bool) {
            objt.removeClass('layui-icon-spread-left');
            objt.addClass('layui-icon-shrink-right');
            $('.warp-left').show();
            $('.warp-right').removeClass('show');
            $('.warp-right').addClass('hide');
        } else {
            objt.removeClass('layui-icon-shrink-right');
            objt.addClass('layui-icon-spread-left');
            $('.warp-left').hide();
            $('.warp-right').removeClass('hide');
            $('.warp-right').addClass('show');
        }
    }
    
    // 渲染令牌视图
    function renderApiTokenTable(data, callback = function(){}) {
        var arr = [];
        if (data.token) {
            arr.push({
                token: '',
            });
        }
        table.render({
            elem: '#apiToken_' + data.apiPath.replace(/\./g,''),
            cols: [
                [
                    {field: 'token', title: '令牌', edit: true, align: 'left', unresize: true},
                ]
            ],
            data: arr,
            limit: 9999,
            skin: 'row line',
            text: {none: '无需令牌'},
            done: function(result, page, count) {
                callback({result, page, count});
            },
        });
    }
    
    // 渲染参数视图
    function renderApiParamsTable(data, callback = function(){}) {
        var arr    = [];
        var params = data.apiParams;
        for (var i in params) {
            arr.push({
                key: i,
                val: '',
                remark: params[i],
            });
        }
        table.render({
            elem: '#apiParamsTable_' + data.apiPath.replace(/\./g,''),
            cols: [
                [
                    {field: 'key', title: '参数', align: 'left', width: 180, unresize: true},
                    {field: 'val', title: '值', edit: true, align: 'left', unresize: true},
                    {field: 'remark', title: '描述', width: 420, align: 'left', unresize: true},
                ]
            ],
            data: arr,
            limit: 9999,
            skin: 'row line',
            text: {none: '无需参数'},
            done: function(result, page, count) {
                callback({result, page, count});
            },
        });
    }
    
    // 接口调用
    function apiRequest(path, method, params, token, callback = function(){}) {
        fn.api.request({
            url: '/api/' + path,
            type: method,
            data: params,
            token: token,
            load: false,
            complete: function(res) {
                callback(res);
            }
        });
    }
    
    // 接口日志
    function logs() {
        wiki('logs', '', res => {
            /*整理数据*/
            var arr = [];
            res.forEach(item => {
                arr.push({
                    id: item.id,
                    path: item.path,
                    method: item.method,
                    params: JSON.stringify(item.params),
                    result: JSON.stringify(item.result),
                    ip: item.req_ip,
                    time: item.req_at + ' ms',
                    create_time: item.create_time,
                });
            });
            /*表格渲染*/
            table.render({
                elem: '#logsTable',
                cols: [
                    [
                        {field: 'method', title: '请求方法', width: 90,  align: 'center', unresize: true},
                        {field: 'path',   title: '请求接口', width: 250, align: 'left', unresize: true},
                        {field: 'params', title: '请求参数', width: 300, align: 'left', unresize: true},
                        {field: 'result', title: '返回结果', align: 'left', unresize: true},
                        {field: 'time',   title: '响应时间', width: 90, align: 'center', unresize: true},
                        {field: 'ip',     title: '来源地址', width: 136, align: 'center', unresize: true},
                        {field: 'create_time', title: '日志时间', width: 180, align: 'left', unresize: true},
                    ]
                ],
                data: arr,
                limit: 9999,
                skin: 'row line',
                text: {none: '暂无日志'}
            });
        });
    }
    
    // 文档调用
    function wiki(cmd, path, callback = function(){}) {
        fn.api.request({
            url: '/api/wiki',
            type: 'POST',
            data: {
                cmd: cmd,
                path: path,
            },
            load: false,
            success: function(res) {
                callback(res);
            },
            fail: function(code, msg) {
                layer.msg(msg, {time: 1200}, function() {
                    // TODO ...
                });
            }
        });
    }
    
    exports('wiki', main);
    
});