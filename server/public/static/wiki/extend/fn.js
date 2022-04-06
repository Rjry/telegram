layui.define(['laytpl'], function(exports) {

    var $      = layui.$,
        layer  = layui.layer
        laytpl = layui.laytpl;

    var main  = {
        
        /*
        fn.view.init();
        */
        view: {
            init: function() {
                var token   = main.store.local.get('token'),
                    src     = token ? '/view/main/index.html' : '/view/login/index.html';
                var iframe  = document.getElementById('iframeView'),
                    contain = document.documentElement.contains(iframe);
                if (contain) {
                    iframe.src = src + '?_=' + new Date().getTime();
                } else {
                    window.location.href = src + '?_=' + new Date().getTime();
                }
            }
        },
        
        /*
        fn.route.to({page:""});
        fn.route.to({url:""});
        */
        route: {
            to: function(object) {
                var src = '';
                if ( object.hasOwnProperty('url') ) {
                    src = object.url;
                } else if ( object.hasOwnProperty('page') ) {
                    src = '/view/' + object.page + '.html';
                } else {
                    layer.msg('route invalid');
                    return false;
                }
                main.store.local.set('route', object);
                var iframe  = document.getElementById('iframePage'),
                    contain = document.documentElement.contains(iframe);
                if (contain) {
                    iframe.src = src + '?_=' + Date.parse(new Date());
                } else {
                    window.location.href = src + '?_=' + Date.parse(new Date());
                }
            },
            current: function() {
                return main.store.local.get('route') || false;
            }
        },
        
        /*
        fn.bind.data.init({
            text: {},
            value: {},
            src: {}
        });
        fn.bind.event.init({
            click: {
                xxx: function() {}
            }
        });
        */
        bind: {
            data: {
                init: function(object) {
                    main.bind.data.text(object.text);
                    main.bind.data.html(object.html);
                    main.bind.data.value(object.value);
                    main.bind.data.src(object.src);
                },
                text: function(object) {
                    var arr = $('[bind-data-text]');
                    $.each(arr, function(key, obj) {
                        var val = obj.attributes['bind-data-text'].value;
                        if ( object.hasOwnProperty(val) ) obj.textContent = object[val];
                    });
                },
                html: function(object) {
                    var arr = $('[bind-data-html]');
                    $.each(arr, function(key, obj) {
                        var val = obj.attributes['bind-data-html'].value;
                        if ( object.hasOwnProperty(val) ) obj.innerHTML = object[val];
                    });
                },
                value: function(object) {
                    var arr = $('[bind-data-value]');
                    $.each(arr, function(key, obj) {
                        var val   = obj.attributes['bind-data-value'].value;
                        if ( object.hasOwnProperty(val) ) obj.value = object[val];
                    });
                },
                src: function(object) {
                    var arr = $('[bind-data-src]');
                    $.each(arr, function(key, obj) {
                        var val = obj.attributes['bind-data-src'].value;
                        if ( object.hasOwnProperty(val) ) obj.src = object[val];
                    });
                },
            },
            event: {
                init: function(object) {
                    main.bind.event.click(object.click);
                },
                click: function(object) {
                    var arr = $('[bind-event-click]');
                    $.each(arr, function(key, obj) {
                        var val = obj.attributes['bind-event-click'].value;
                        /*方法一*/
                        // obj.onclick = object[val];
                        /*方法二*/
                        obj.addEventListener('click', object[val], false);
                    });
                }
            },
            route: {
                init: function() {
                    var arr = $('[bind-route-page]');
                    $.each(arr, function(key, obj) {
                        var page    = obj.attributes['bind-route-page'].value;
                        obj.onclick = function() {
                            main.route.to({page: page});
                        }
                    });
                    var arr = $('[bind-route-url]');
                    $.each(arr, function(key, obj) {
                        var url     = obj.attributes['bind-route-url'].value;
                        obj.onclick = function() {
                            main.route.to({url: url});
                        }
                    });
                }
            }
        },
        
        /*
        fn.tpl(tplid, viewid, data);
        */
        tpl: function(tplid, viewid, data) {
            var tpl  = document.getElementById(tplid).innerHTML,
                view = document.getElementById(viewid);
            laytpl(tpl).render(data, function(html){
                view.innerHTML = html;
            });
        },
        
        /*
        fn.load.style(src);
        fn.load.script(src, function() {});
        */
        load: {
            style: function(src) {
                var head  = document.getElementsByTagName('head')[0],
                    link  = document.createElement('link');
                link.type = "text/css";
                link.rel  = "stylesheet";
                link.href = src;
                head.appendChild(link);
            },
            script: function(src, callback) {
                var head   = document.getElementsByTagName('head')[0],
                    script = document.createElement('script'),
                    fn     = callback || function(){};
                script.src = src + '?_t=' + new Date().getTime();
                script.onload = fn;
                head.appendChild(script);
            }
        },
        
        /*
        fn.loading.show();
        fn.loading.hide();
        */
        loading: {
            show: function() {
                var head = document.getElementsByTagName('body')[0],
                    div  = document.createElement('div');
                div.id             = 'ed698f3d04061f';
                div.style.position = 'fixed';
                div.style.top    = 0;
                div.style.left   = 0;
                div.style.right  = 0;
                div.style.bottom = 0;
                div.style.backgroundColor = '#fff';
                head.appendChild(div);
                layer.load(1);
            },
            hide: function() {
                var loading = document.getElementById('ed698f3d04061f');
                loading.remove();
                layer.closeAll();
            }
        },
        
        /*
        fn.store.local.get(key);
        fn.store.local.set(key, value, expires);
        fn.store.local.del(key);
        fn.store.local.cls();
        fn.store.session.get(key);
        fn.store.session.set(key, value);
        fn.store.session.del(key);
        fn.store.session.cls();
        */
        store: {
            local: {
                get: function(key) {
                    let item = localStorage.getItem(key);
                    try {
                        item = JSON.parse(item);
                    } catch (error) {
                        item = item;
                    }
                    if (item && item.startTime) {
                        let date = new Date().getTime();
                        if (date - item.startTime > item.expires) {
                            localStorage.removeItem(name);
                            return false;
                        } else {
                            return item.value;
                        }
                    } else {
                        return item;
                    }
                },
                set: function(key, value, expires = false) {
                    if (expires) {
                        var expires = expires * 1000;
                        let params  = { key, value, expires };
                        var data = Object.assign(params, { startTime: new Date().getTime() });
                        localStorage.setItem(key, JSON.stringify(data));
                    } else {
                        if (Object.prototype.toString.call(value) == '[object Object]') {
                            value = JSON.stringify(value);
                        }
                        if (Object.prototype.toString.call(value) == '[object Array]') {
                            value = JSON.stringify(value);
                        }
                        localStorage.setItem(key, value);
                    }
                },
                del: function(key) {
                    localStorage.removeItem(key);
                },
                cls: function() {
                    localStorage.clear();
                }
            },
            session: {
                get: function(key) {
                    var data = sessionStorage[key];
                    if (!data || data === "null") {
                        return null;
                    }
                    return JSON.parse(data).value;
                },
                set: function(key, value) {
                    var data = {value: value}
                    sessionStorage[key] = JSON.stringify(data);
                },
                del: function(key) {
                    sessionStorage.removeItem(key);
                },
                cls: function() {
                    sessionStorage.clear();
                }
            }
        },
        
        /*
        fn.copy(txt);
        */
        copy: function(txt) {
            var createInput = document.createElement('input');
            createInput.value = txt;
            document.body.appendChild(createInput);
            createInput.select();
            document.execCommand("Copy");
            createInput.className = 'createInput';
            createInput.style.display='none';
            layer.msg('复制成功');
        },
        
        /*
        fn.arr.add(arr, val);
        fn.arr.del(arr, val);
        fn.arr.has(arr, val);
        fn.arr.diff(arr1, arr2);
        */
        arr: {
            add: function(arr, val) {
                arr.push(val);
            },
            del: function(arr, val) {
                let index = arr.indexOf(val);
                if (index > -1) arr.splice(index,1);
            },
            has: function(arr, val) {
                return (arr.indexOf(val) != -1) ? true : false;
            },
            diff: function(arr1, arr2) {
                var ret = [];
                var tmp = arr2;
                arr1.forEach(function(val1, i){
                    if (arr2.indexOf(val1) < 0) {
                        ret.push(val1);
                    } else {
                        tmp.splice(tmp.indexOf(val1), 1);
                    }
                });
                return ret.concat(tmp);
            }
        },
        
        /*
        fn.format.text.color('正常', '#5FB878');
        fn.format.text.color('正常', '#ED1505');
        fn.format.text.color('正常', '#009688');
        fn.format.text.color('正常', '#FFB800');
        fn.format.text.color('正常', '#01AAED');
        fn.format.number.fix(number, point);
        fn.format.number.rix(number, point);
        */
        format: {
            text: {
                color: function(text, color) {
                    return '<font style="color:' + color + '">' + text + '</font>';
                }
            },
            number: {
                fix: function(number, decimal = 2) {
                    return number.toFixed(decimal);
                },
                rix: function(number, decimal = 2) {
                    var number = number.toString();
                    let index  = number.indexOf('.');
                    if (index !== -1) {
                        number = number.substring(0, decimal + index + 1)
                    } else {
                        number = number.substring(0)
                    }
                    return parseFloat(number).toFixed(decimal);
                }
            }
        },
        
        /*
        fn.time.today();
        fn.time.yesterday();
        fn.time.date(timestamp);
        fn.time.timestamp(date);
        */
        time: {
            today: function() {
                var date  = new Date(),
                    year  = date.getFullYear(),
                    month = date.getMonth() + 1,
                    month = (month < 10) ? ('0' + month) : month,
                    day   = date.getDate(),
                    day   = (day < 10) ? ('0' + day) : day;
                return year + '-' + month + '-' + day;
            },
            yesterday: function() {
                var date  = new Date(),
                    date  = new Date(date.getTime() - 24*60*60*1000),
                    year  = date.getFullYear(),
                    month = date.getMonth() + 1,
                    month = (month < 10) ? ('0' + month) : month,
                    day   = date.getDate(),
                    day   = (day < 10) ? ('0' + day) : day;
                return year + '-' + month + '-' + day;
            },
            date: function(timestamp, format = 'Y-m-d H:i:s') {
                if (timestamp == 0) return '';
                var date = new Date(timestamp * 1000),
                    Y    = date.getFullYear(),
                    m    = (date.getMonth()+1 < 10 ? '0'+(date.getMonth()+1) : date.getMonth()+1),
                    d    = ( (date.getDate() < 10) ? ('0' +  date.getDate()) : date.getDate() ),
                    H    = ( (date.getHours() < 10) ? ('0' +  date.getHours()) : date.getHours() ),
                    i    = ( (date.getMinutes() < 10) ? ('0' +  date.getMinutes()) : date.getMinutes() ),
                    s    = ( (date.getSeconds() < 10) ? ('0' +  date.getSeconds()) : date.getSeconds() );
                switch (format) {
                    case 'Y-m-d H:i:s':
                        return Y + '-' + m + '-' + d + ' ' + H + ':' + i + ':' + s;
                        break;
                    case 'Y/m/d H:i:s':
                        return Y + '/' + m + '/' + d + ' ' + H + ':' + i + ':' + s;
                        break;
                    case 'm/d H:i:s':
                        return m + '/' + d + ' ' + H + ':' + i + ':' + s;
                        break;
                    case 'Y-m-d':
                        return Y + '-' + m + '-' + d;
                        break;
                    case 'Y-m':
                        return Y + '-' + m;
                        break;
                    case 'm-d':
                        return m + '-' + d;
                        break;
                    case 'Y':
                        return Y;
                        break;
                    case 'm':
                        return m;
                        break;
                    case 'd':
                        return d;
                        break;
                    case 'H:i:s':
                        return H + ':' + i + ':' + s;
                        break;
                    case 'H:i':
                        return H + ':' + i;
                        break;
                    case 'H':
                        return H;
                        break;
                    case 'i':
                        return i;
                        break;
                    case 's':
                        return s;
                        break;
                }
            },
            timestamp: function(date) {
                var date = new Date(date),
                time = date.getTime(date);
                return time / 1000;
            }
        },
        
        /*
        fn.api.request({
            url: '',
            type: '',
            data: {},
            token: '',
            // 是否加载动画,可选,默认:true
            load: true,
            // 请求正常时执行,可选
            complete: functionn(code, msg, data) {
                
            },
            // 请求成功后执行,可选
            success: function(data) {
        
            },
            // 请求失败后执行,可选
            fail: function(code, msg) {
        
            },
            // 请求异常时执行,可选,默认弹框提示
            error: function() {
                    
            }
        });
        */
        api: {
            request: function(object) {
                var load = object.hasOwnProperty('load') ? object.load : true;
                if (load) {
                    var index = layer.load();
                }
                $.ajax({
                    // url : object.url + '?_' + Math.round(new Date()),
                    url : object.url,
                    data: JSON.stringify(object.data),
                    type: object.type || 'POST',
                    timeout: 10000,
                    cache: false,
                    contentType: 'application/json',
                    dataType: 'json',
                    headers: {
                        "X-Api-Token": object.token || '',
                    },
                    complete: function(res) {
                        if (load) layer.close(index);
                        var httpCode = res.status;
                        if (httpCode == 200) {
                            var res  = JSON.parse(res.responseText),
                                code = res.code,
                                msg  = res.msg,
                                data = res.data;
                            if (object.complete instanceof Function) {
                                object.complete({code, msg, data});
                            }
                            if (code == 0) {
                                if (object.success instanceof Function) {
                                    object.success(data);
                                }
                            } else {
                                if (object.fail instanceof Function) {
                                    object.fail(code, msg);
                                }
                            }
                        } else {
                            if (object.error instanceof Function) {
                                object.error();
                            } else {
                                layer.msg('请求失败，请稍后再试~');
                            }
                        }
                    }
                });
            }
        },
        
        /*
        # 调试模式
        fn.ws.trace(true);
        # 创建连接
        fn.ws.connect({
            ping: 30,
            onopen: function() {
                
            },
            onmessage: function(msg) {
                
            },
            onerror: function(err) {
                
            },
            onclose: function() {
                
            }
        });
        # 发送消息
        fn.ws.send({type: "xxx", data: {}});
        # 关闭连接
        fn.ws.close();
        */
        ws: {
            debug: false,
            socket: null,
            trace: function(bool) {
                main.ws.debug = bool;
            },
            connect: function(object) {
                /*建立连接*/
                var url = object.url || main.store.local.get('env')['websocket_url'];
                main.ws.socket = new WebSocket(url);
                /*发送心跳包*/
                var timer = setInterval(function() {
                    if (main.ws.socket && main.ws.socket.readyState == 1) {
                        main.ws.send({"type": "ping"});
                    } else {
                        clearInterval(timer);
                    }
                },1000 * object.ping);
                main.ws.socket.onopen = function() {
                    main.ws.debug && console.log('%c::WebSocket::连接成功::','color:#FFFFFF;background-color:#FC3E5C;');
                    object.onopen();
                }
                main.ws.socket.onmessage = function(msg) {
                    main.ws.debug && console.log('%c::WebSocket::收到消息::','color:#FFFFFF;background-color:#FC3E5C;');
                    var msg = JSON.parse(msg.data);
                    main.ws.debug && console.log(msg);
                    object.onmessage(msg);
                }
                main.ws.socket.onerror = function(err) {
                    main.ws.debug && console.log('%c::WebSocket::发生错误::','color:#FFFFFF;background-color:#FC3E5C;');
                    main.ws.debug && console.log(err);
                    object.onerror(err);
                }
                main.ws.socket.onclose = function() {
                    main.ws.debug && console.log('%c::WebSocket::连接断开::','color:#FFFFFF;background-color:#FC3E5C;');
                    object.onclose();
                }
            },
            send: function(msg) {
                main.ws.debug && console.log('%c::WebSocket::发送消息::','color:#FFFFFF;background-color:#FC3E5C;');
                main.ws.debug && console.log(msg);
                var msg = JSON.stringify(msg);
                if (main.ws.socket && main.ws.socket.readyState == 1) {
                    main.ws.socket.send(msg);
                } else {
                    console.log('%cwebsocket already closed.','color:#FFFFFF;background-color:#FC3E5C;');
                }
            },
            close: function() {
                main.ws.debug && console.log('%c::WebSocket::关闭连接::','color:#FFFFFF;background-color:#FC3E5C;');
                main.ws.socket && main.ws.socket.close();
            }
        }

    };
    
    main.bind.route.init();

    exports('fn', main);
    
});