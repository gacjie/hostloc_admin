            
require.config({
    paths: {
        'neditor': '../../static/neditor/neditor.all',
        'jquery': '../../static/lib/zTree/js/jquery-1.4.4.min',
        'neditori18n': '../../static/neditor/i18n/zh-cn/zh-cn',
    },
    shim: {
        'neditor': [
        ],
        'neditori18n': [
            'neditor'
        ]
    }
});
function initServiceConfig() {
    /**
  * 自定义上传接口
  * 由于所有Neditor请求都通过editor对象的getActionUrl方法获取上传接口，可以直接通过复写这个方法实现自定义上传接口
  * @param {String} action 匹配neditor.config.js中配置的xxxActionName
  * @returns 返回自定义的上传接口
  */
    UE.Editor.prototype._bkGetActionUrl = UE.Editor.prototype.getActionUrl;
    UE.Editor.prototype.getActionUrl = function (action) {
        /* 按config中的xxxActionName返回对应的接口地址 */
        if (action == 'uploadimage' || action == 'uploadscrawl') {
            return '/addons/neditor/upload/image.html';
        } else if (action == 'uploadvideo') {
            return '/addons/neditor/upload/vedio.html';
        } else {
            return this._bkGetActionUrl.call(this, action);
        }
    }

    /**
     * 图片上传service
     * @param {Object} context UploadImage对象 图片上传上下文
     * @param {Object} editor  编辑器对象
     * @returns imageUploadService 对象
     */
    window.UEDITOR_CONFIG['imageUploadService'] = function (context, editor) {
        return {
            /** 
             * 触发fileQueued事件时执行
             * 当文件被加入队列以后触发，用来设置上传相关的数据 (比如: url和自定义参数)
             * @param {Object} file 当前选择的文件对象
             */
            setUploadData: function (file) {
                return file;
            },
            /**
             * 触发uploadBeforeSend事件时执行
             * 在文件上传之前触发，用来添加附带参数
             * @param {Object} object 当前上传对象
             * @param {Object} data 默认的上传参数，可以扩展此对象来控制上传参数
             * @param {Object} headers 可以扩展此对象来控制上传头部
             * @returns 上传参数对象
             */
            setFormData: function (object, data, headers) {
                return data;
            },
            /**
             * 触发startUpload事件时执行
             * 当开始上传流程时触发，用来设置Uploader配置项
             * @param {Object} uploader
             * @returns uploader
             */
            setUploaderOptions: function (uploader) {
                return uploader;
            },
            /**
             * 触发uploadSuccess事件时执行
             * 当文件上传成功时触发，可以在这里修改上传接口返回的response对象
             * @param {Object} res 上传接口返回的response
             * @returns {Boolean} 上传接口返回的response成功状态条件 (比如: res.code == 200)
             */
            getResponseSuccess: function (res) {
                return res.code == 0;
            },
            /* 指定上传接口返回的response中图片路径的字段，默认为 url
             * 如果图片路径字段不是res的属性，可以写成 对象.属性 的方式，例如：data.url 
             * */
            imageSrcField: 'data.url'
        }
    };

    /**
     * 视频上传service
     * @param {Object} context UploadVideo对象 视频上传上下文
     * @param {Object} editor  编辑器对象
     * @returns videoUploadService 对象
     */
    window.UEDITOR_CONFIG['videoUploadService'] = function (context, editor) {
        return {
            /** 
             * 触发fileQueued事件时执行
             * 当文件被加入队列以后触发，用来设置上传相关的数据 (比如: url和自定义参数)
             * @param {Object} file 当前选择的文件对象
             */
            setUploadData: function (file) {
                return file;
            },
            /**
             * 触发uploadBeforeSend事件时执行
             * 在文件上传之前触发，用来添加附带参数
             * @param {Object} object 当前上传对象
             * @param {Object} data 默认的上传参数，可以扩展此对象来控制上传参数
             * @param {Object} headers 可以扩展此对象来控制上传头部
             * @returns 上传参数对象
             */
            setFormData: function (object, data, headers) {
                return data;
            },
            /**
             * 触发startUpload事件时执行
             * 当开始上传流程时触发，用来设置Uploader配置项
             * @param {Object} uploader
             * @returns uploader
             */
            setUploaderOptions: function (uploader) {
                return uploader;
            },
            /**
             * 触发uploadSuccess事件时执行
             * 当文件上传成功时触发，可以在这里修改上传接口返回的response对象
             * @param {Object} res 上传接口返回的response
             * @returns {Boolean} 上传接口返回的response成功状态条件 (比如: res.code == 200)
             */
            getResponseSuccess: function (res) {
                return res.code == 0;
            },
            /* 指定上传接口返回的response中视频路径的字段，默认为 url
             * 如果视频路径字段不是res的属性，可以写成 对象.属性 的方式，例如：data.url 
             * */
            videoSrcField: 'data.url'
        }
    };


    /**
     * 附件上传service
     * @param {Object} context UploadFile对象 附件上传上下文
     * @param {Object} editor  编辑器对象
     * @returns fileUploadService 对象
     */
    window.UEDITOR_CONFIG['fileUploadService'] = function (context, editor) {
        return {
            /** 
             * 触发fileQueued事件时执行
             * 当文件被加入队列以后触发，用来设置上传相关的数据 (比如: url和自定义参数)
             * @param {Object} file 当前选择的文件对象
             */
            setUploadData: function (file) {
                return file;
            },
            /**
             * 触发uploadBeforeSend事件时执行
             * 在文件上传之前触发，用来添加附带参数
             * @param {Object} object 当前上传对象
             * @param {Object} data 默认的上传参数，可以扩展此对象来控制上传参数
             * @param {Object} headers 可以扩展此对象来控制上传头部
             * @returns 上传参数对象
             */
            setFormData: function (object, data, headers) {
                return data;
            },
            /**
             * 触发startUpload事件时执行
             * 当开始上传流程时触发，用来设置Uploader配置项
             * @param {Object} uploader
             * @returns uploader
             */
            setUploaderOptions: function (uploader) {
                return uploader;
            },
            /**
             * 触发uploadSuccess事件时执行
             * 当文件上传成功时触发，可以在这里修改上传接口返回的response对象
             * @param {Object} res 上传接口返回的response
             * @returns {Boolean} 上传接口返回的response成功状态条件 (比如: res.code == 200)
             */
            getResponseSuccess: function (res) {
                return res.code == 0;
            },
            /* 指定上传接口返回的response中附件路径的字段，默认为 url
             * 如果附件路径字段不是res的属性，可以写成 对象.属性 的方式，例如：data.url 
             * */
            fileSrcField: 'data.url'
        }
    };


}
require(['../../static/neditor/third-party/zeroclipboard/ZeroClipboard', 'neditor', 'neditori18n', 'jquery'], function (ZeroClipboard) {
    initServiceConfig();
    window.ZeroClipboard = ZeroClipboard;

    $("textarea.editor").each(function () {
        let option = {
            zIndex: 898, //编辑器层级的基数,默认
            UEDITOR_HOME_URL: '../../static/neditor/',
            theme: 'notadd',
            serverUrl: "/addons/neditor/upload/attachment.html",
            imageActionName: "uploadimage",
            scrawlActionName: "uploadscrawl",
            videoActionName: "uploadvideo",
            fileActionName: "uploadfile",
            imageFieldName: "file", // 提交的图片表单名称
            imageMaxSize: 2048000, // 上传大小限制，单位B
            imageUrlPrefix: "",
            scrawlUrlPrefix: "",
            videoUrlPrefix: "",
            fileUrlPrefix: "",
            catcherLocalDomain: "",
            toolbars: [JSON.parse('["fullscreen","source","|","undo","redo","|","bold","italic","underline","fontborder","strikethrough","superscript","subscript","removeformat","formatmatch","autotypeset","blockquote","pasteplain","|","forecolor","backcolor","insertorderedlist","insertunorderedlist","selectall","cleardoc","|","rowspacingtop","rowspacingbottom","lineheight","|","customstyle","paragraph","fontfamily","fontsize","|","directionalityltr","directionalityrtl","indent","|","justifyleft","justifycenter","justifyright","justifyjustify","|","touppercase","tolowercase","|","link","unlink","anchor","|","imagenone","imageleft","imageright","imagecenter","|","simpleupload","insertimage","","insertvideo","attachment","insertframe","pagebreak","template","background","|","horizontal","date","time","spechars","","|","inserttable","deletetable","insertparagraphbeforetable","insertrow","deleterow","insertcol","deletecol","mergecells","mergeright","mergedown","splittocells","splittorows","splittocols","charts","|","print","preview","searchreplace","drafts"]')],
            autoHeightEnabled: false,
            xssFilterRules: true
            //input xss过滤
            ,
            videoAllowFiles: ['.mp4', '.mov'],
            imageAllowFiles: [".png", ".jpg", ".jpeg", ".gif", ".bmp"],
            inputXssFilter: true
            //output xss过滤
            ,
            outputXssFilter: true
            // xss过滤白名单 名单来源: https://raw.githubusercontent.com/leizongmin/js-xss/master/lib/default.js
            ,
            whitList: {
                a: ['target', 'href', 'title', 'class', 'style'],
                abbr: ['title', 'class', 'style'],
                address: ['class', 'style'],
                area: ['shape', 'coords', 'href', 'alt'],
                article: [],
                aside: [],
                audio: ['autoplay', 'controls', 'loop', 'preload', 'src', 'class', 'style'],
                b: ['class', 'style'],
                bdi: ['dir'],
                bdo: ['dir'],
                big: [],
                blockquote: ['cite', 'class', 'style'],
                br: [],
                caption: ['class', 'style'],
                center: [],
                cite: [],
                code: ['class', 'style'],
                col: ['align', 'valign', 'span', 'width', 'class', 'style'],
                colgroup: ['align', 'valign', 'span', 'width', 'class', 'style'],
                dd: ['class', 'style'],
                del: ['datetime'],
                details: ['open'],
                div: ['class', 'style'],
                dl: ['class', 'style'],
                dt: ['class', 'style'],
                em: ['class', 'style'],
                font: ['color', 'size', 'face'],
                footer: [],
                h1: ['class', 'style'],
                h2: ['class', 'style'],
                h3: ['class', 'style'],
                h4: ['class', 'style'],
                h5: ['class', 'style'],
                h6: ['class', 'style'],
                header: [],
                hr: [],
                i: ['class', 'style'],
                img: ['style', 'src', 'alt', 'title', 'width', 'height', 'id', '_src', '_url', 'loadingclass', 'class', 'data-latex'],
                ins: ['datetime'],
                li: ['class', 'style'],
                mark: [],
                nav: [],
                ol: ['class', 'style'],
                p: ['class', 'style'],
                pre: ['class', 'style'],
                s: [],
                section: [],
                small: [],
                span: ['class', 'style'],
                sub: ['class', 'style'],
                sup: ['class', 'style'],
                strong: ['class', 'style'],
                table: ['width', 'border', 'align', 'valign', 'class', 'style'],
                tbody: ['align', 'valign', 'class', 'style'],
                td: ['width', 'rowspan', 'colspan', 'align', 'valign', 'class', 'style'],
                tfoot: ['align', 'valign', 'class', 'style'],
                th: ['width', 'rowspan', 'colspan', 'align', 'valign', 'class', 'style'],
                thead: ['align', 'valign', 'class', 'style'],
                tr: ['rowspan', 'align', 'valign', 'class', 'style'],
                tt: [],
                u: [],
                ul: ['class', 'style'],
                video: ['autoplay', 'controls', 'loop', 'preload', 'src', 'height', 'width', 'class', 'style'],
                source: ['src', 'type'],
                embed: ['type', 'class', 'pluginspage', 'src', 'width', 'height', 'align', 'style', 'wmode', 'play', 'autoplay', 'loop', 'menu', 'allowscriptaccess', 'allowfullscreen', 'controls', 'preload'],
                iframe: ['src', 'class', 'height', 'width', 'max-width', 'max-height', 'align', 'frameborder', 'allowfullscreen']
            }
        }
        let id = $(this).attr('id');
        let ue = UE.getEditor(id, option);
        console.log(ue)

    })
});