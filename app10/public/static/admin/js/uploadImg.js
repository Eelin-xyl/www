/*
*上传图片并本地预览插件
*兼容IE8
*url    ----上传服务器地址
*obj    ----上传图片容器
*width  ----图片宽度
*height ----图片宽度
 */
(function($) {
// 构造函数
function UploadImg(obj,opt) {
    // debugger;
    this.obj = obj;
    this.$obj = $(obj);
    this.iframeObj = null;
    this.frameCount = 0;
    this.formName = '';
    this.colfile = null;
    this.defaultOpt = {
        "url": '',
        // 默认-1时,代表上传图片个数不限
        "picnum": -1,
        "fileType": ['image', 'text', 'word', 'excel', 'wps', 'pdf', 'ppt', 'zip'],
        "width": 100,
        "height": 100,
        "maxImgSize": 5*1024*1024
    };
    this.filesList = [];// 保存允许上传的文件
    this.filterList = [];// 别过滤的文件的名称和过滤原因
    this.options = $.extend({}, this.defaultOpt, opt);
    this.init();
}

// 初始化html
UploadImg.prototype.init = function() {
    // IE 9- 不支持multiple属性，每次只能传一张图片
    var html = '<div class="file-btn" style="position:relative;"><input multiple = "multiple" type="file" class="fileInput" name="file[]"><button type="button" class="uploadBtn">上传文件</button></div><div class="previewBox clearfix"></div>';
    $(html).find(".fileInput").css({
        position: 'absolute',
        zIndex: '999',
        display: 'block',
        opacity: '0',
        filter: 'alpha(opacity=0)',
        height: '34px',
        width: '106px',
        border: 'none',
        outline: 'hidden'
    });
    $(html).find(".previewBox").css({
        margin:'15px 0'
    }).find('img').css({marginRight: '10px'});
    $(html).find(".uploadBtn").css({
        border:'1px solid #eee',
        background: '#fff',
        color:'#666',
        fontSize: '16px',
        lineHeight: '2',
        padding: '0 20px',
    });
    this.$obj.append($(html));
    this.bindEvent();
    this.options.onReady();
};
//绑定事件
UploadImg.prototype.bindEvent = function() {
    var self = this;
    $(".fileInput").change(function(){
        self.filesList = [];// 清空文件列表
        self.operationImg(this);
        if(self.filterList.length){
            self.options.onDealTypeErr(self.filterList);
            self.filterList = [];
        }
    });
};
//检查图片格式
UploadImg.prototype.isImg = function(url, file) {
    console.log('ur1231231231231231l', url);
    if (file) {
        var type = this.getFileType(file);
        return ($.inArray(type, this.options.fileType) > -1) ? true : false;
    } else {
        return (/.+\.(jpg|png|jpeg|gif|xls|pdf|text|doc|zip)$/i.test(url)) ? true : false;
    }
};
// 获取文件格式
UploadImg.prototype.getFileType = function (file) {
    var type = "";
    if(/image/.test(file.type)){
        type = 'image';
    } else if(/video/.test(file.type)){
        type = 'video';
    } else if(/audio/.test(file.type)){
        type = 'audio';
    } else if(/zip/.test(file.name)){// 压缩文件的type属性为空
        type = 'zip';
    } else if(/msword/.test(file.type) || /wordprocessingml.document/.test(file.type)){
        type = 'word';
    } else if(/ms-powerpoint/.test(file.type) || /presentationml.presentation/.test(file.type)){
        type = 'ppt';
    } else if(/ms-excel/.test(file.type) || /spreadsheetml.sheet/.test(file.type)){
        type = 'excel';
    } else if(/plain/.test(file.type)){
        type = 'text';
    } else if(/pdf/.test(file.type)){
        type = 'pdf';
    } else if(/ms-works/.test(file.type)){
        type = 'wps';
    } else if(/psd/.test(file.name)){
        type = "psd";
    }else {
        type = 'other';
    }
    return type;
};
// 格式化文件大小
UploadImg.prototype.formatFileSize = function (bytes) {
    if (typeof bytes !== 'number') {
        return '';
    }
    if (bytes >= 1000000000) {
        return (bytes / 1000000000).toFixed(2) + ' GB';
    }
    if (bytes >= 1000000) {
        return (bytes / 1000000).toFixed(2) + ' MB';
    }
    return (bytes / 1000).toFixed(2) + ' KB';
};
// 获取 图片大小 兼容IE8+
UploadImg.prototype.getImgSize = function(obj){
    // IE10+，Firefox， chrome
    if (obj.files) {
        return obj.files[0].size;
    } else {
        try {
            obj.select();
            $(obj).blur();
            if(document.selection) {
                // 获取IE低版本上传文件的路径值一般都是这种方式
                // IE6直接通过input的value值获取
                var url = document.selection.createRange().text;
                var fso = new ActiveXObject("Scripting.FileSystemObject");
                var filesize = fso.GetFile(url).size;
                // console.log('IE******size', filesize);
                return filesize;
            } else {
                this.options.onHint('您的浏览器版本太低，请更换浏览器');
            }
        } catch (e) {
            alert(e+"\n"+"如果错误为：Error:Automation 服务器不能创建对象；"+"\n"+"请按以下方法配置浏览器："+"\n"+"请打开【Internet选项-安全-Internet-自定义级别-ActiveX控件和插件-对未标记为可安全执行脚本的ActiveX控件初始化并执行脚本（不安全）-点击启用-确定】");
            return window.location.reload();
        }
    }
};
//添加预览图片到页面上
UploadImg.prototype.addImgHtml = function(file) {
    var type = this.getFileType(file);
    if($(".previewBox")) {
        var html = "<div class='img_box'><div class='mark ing'></div><span class='file-item'>"+ file.name +"("+ this.formatFileSize(file.size) +")<i class='layui-icon layui-icon-close'></i></span></div>";
        $(".previewBox").append(html);
    }
};


//正常处理 IE10+
UploadImg.prototype.previewImg = function(obj) {
    var file = obj.files[0];
    var self = this;
    if (self.options.picnum !== -1) {
        var oldpicLen = $("#mangpic .img_box").length;
        var num = (self.options.picnum > oldpicLen) ? (self.options.picnum - oldpicLen) : 0;
        if (obj.files.length > num) {
            self.options.onHint('这里现在最多再允许上传'+num+'个文件');
            return;
        }
    }
    for(var i=0, item; item = obj.files[i]; i++){
        if(this.isImg(item.name, item)) {
            if (item.size <= this.options.maxImgSize) {
                /* var reader = new FileReader();
                reader.readAsDataURL(item);
                reader.onload = function(evt) {
                    self.addImgHtml(evt.target.result, item);
                }; */
                self.addImgHtml(item);
                self.filesList.push(item);
            } else {
                // 处理图片大小错误
                this.filterList.push({"name": item.name, "reason":'文件大小超出允许范围(<=5M)'});
            }
        } else {
            // 处理图片格式错误
            this.filterList.push({"name": item.name, "reason":'文件格式不被允许(jpg|png|jpeg)'});
        }
    }
};


//上传图片操作；
UploadImg.prototype.operationImg = function(fileObj) {
    var self = this;
    // console.log(fileObj, fileObj.files[0]);
    if(fileObj.files && fileObj.files[0]) {
        //html5 files API
        this.previewImg(fileObj);
        // console.log('上传的文件列表：', this.filesList);
        if (this.filesList.length) {
            var interval = setInterval(function(){
                if ($("#mangpic .img_box .mark.ing").length === self.filesList.length) {
                    self.uploadImgByAjax(self.filesList);
                    clearInterval(interval);
                }
            }, 1000/60);
        }
    } else {
        //兼容IE
        this.previewImgIE(fileObj);
    }
};
//兼容IE处理 IE9-
UploadImg.prototype.previewImgIE = function(obj) {
    var self = this;
    obj.select();
    $(obj).blur();
    if(document.selection) {
        // 获取IE低版本上传文件的路径值一般都是这种方式
        // IE6直接通过input的value值获取
        var url = document.selection.createRange().text;
        // console.log('ie8-url', url.split('\\')[url.split('\\').length-1]);
        var name = url.split('\\')[url.split('\\').length-1];
        if (this.isImg(url)) {
            if (this.getImgSize(obj) <= this.options.maxImgSize) {
                console.log('urlurlurlurlurlurl', url);
                var imgWrap = "<div class='imgWrap'><span class='file-item'>"+ name +"<i class='layui-icon layui-icon-close'></i></span><div class='mark'>上传中...</div></div>";
                $(".previewBox").append($(imgWrap));
                /* $(".imgWrap").css({
                    "width":this.options.width,
                    "height":this.options.height,
                    "display":"inline-block",
                    "margin-right":"10px",
                    "*display":"inline",
                    "*zoom":1
                });
                $(".imgWrap:last").css("filter","progid:DXImageTransform.Microsoft.AlphaImageLoader(sizingMethod = scale,src=\""+url+"\")");*/
                self.uploadImgByForm(obj);
            } else {
                this.filterList.push({"name": name, "reason":'文件大小超出允许范围(<=5M)'});
            }
        } else {
            this.filterList.push({"name": name, "reason":'文件格式不被允许(jpg|png|jpeg)'});
        }
    } else {
        this.options.onHint('您的浏览器版本太低，请更换浏览器');
    }
};
// html5 FormData Ajax上传
UploadImg.prototype.uploadImgByAjax = function(fileList){
    var self = this;
    console.log('fileList+ajax', fileList);
    // 创建XMLHttpRequest对象
    function createXHR() {
        if (typeof XMLHttpRequest != "undefined") {
            return new XMLHttpRequest();
        } else if (typeof ActiveXObject != "undefined") {
            if (typeof arguments.callee.activeXString != "string") {
                var versions = ["MSXML2.XMLHttp.6.0", "MSXML2.XMLHttp.3.0", "MSXML2.XMLHttp"];
                for (var i = 0, len = versions.length; i < len; i++) {
                    try {
                        var xhr = new ActiveXObject(versions[i]);
                        arguments.callee.activeXString = versions[i];
                        return xhr;
                    } catch (ex) {
                        //跳过
                    }
                }
            }
            return new ActiveXObject(arguments.callee.activeXString);
        } else {
            throw new Error("NO XHR object available.")
        }
    }
    var formData = new FormData();
    for(var i=0; i<fileList.length; i++){
        formData.append('file[]', fileList[i]);
    }
    var xhr = createXHR();
    if(xhr.upload){
        xhr.upload.addEventListener('progress', function(e){
            // lengthComputable是一个表示进度信息是否可用的布尔值
            if(e.lengthComputable){
                var percentComplete = Math.round(e.loaded/e.total*100);
                var mark = $("#mangpic .img_box .mark");
                if (mark.hasClass('ing')) {
                    if (percentComplete >= 0 && percentComplete < 100) {
                        mark.text( percentComplete+'%' );
                    } else {
                        mark.removeClass('ing').addClass('old').fadeOut();
                    }
                }
                // self.options.onUploading(e.loaded/e.total);
            }
        }, false);
        xhr.onload = function(){
            if((xhr.status >= 200 && xhr.status < 300) || xhr.status === 304){
                var data = JSON.parse( xhr.responseText );
                if(data.state){
                    self.options.onSuccess(data.data);
                } else {
                    if (data.data) {
                        self.options.onError({"code": data.state, "msg": data.data});
                    } else {
                        self.options.onError({"code": data.state, "msg": '服务器在打瞌睡'});
                    }
                }
            } else {
                self.options.onError({"code": xhr.status, "msg": '本地错误'});
            }
        };
        xhr.open("POST", this.options.url, true);
        xhr.send(formData);
    }
};
// 模拟表单上传
UploadImg.prototype.uploadImgByForm = function(target){
    var self = this;
    if(this.options.url === '' || this.options.url == null){
        this.options.onHint('填写上传地址');
        return;
    }
    if (this.iframeObj == null) {
        var frameName = 'upload_frame_' + (this.frameCount++);
        var iframe = $('<iframe style="position:absolute;top:-9999px"><script type="text/javascript"></script></iframe>').attr('name', frameName);
        formName = 'form_' + frameName;
        var form = $('<form method="post" style="display:none;" enctype="multipart/form-data" />').attr('name', formName);
        form.attr("target", frameName).attr('action', self.options.url);
        var fileHtml = $(target).prop("outerHTML");
        colfile = $(target).clone(true);
        $(target).replaceWith(colfile);
        form.append(target);
        $(document.body).append(iframe).append(form);
        iframeObj = iframe;
        // console.log('模拟form结构', iframe, form);
    }
    var form = $("form[name=" + formName + "]");
    //加载事件
    iframeObj.on("load", function (e) {
        var contents = $(this).contents().get(0);
        var data = $(contents).find('body').text();
        var response = JSON.parse( data );
        //    var response = JSON.parse( data.replace(/(\}\]\})/g, '}]}+').split("+")[0] );
        if(response.state){
            $(".imgWrap .mark").hide();
            self.options.onSuccess(response.data);
        } else {
            if (response.data) {
                self.options.onError({"code": response.state, "msg": response.data});
            } else {
                self.options.onError({"code": response.state, "msg": '服务器在打瞌睡'});
            }
        }
        iframeObj.remove();
        form.remove();
        iframeObj = null;
        //启用
        $(colfile).removeAttr("disabled");
    });
    try {
        form.submit();
    } catch (Eobject) {
        // console.log('上传报错——form', Eobject);
        self.options.onError({"code": '100000', "msg": Eobject});
    }
};

//绑定插件
$.fn.uploadImg= function(options) {
    return this.each(function() {
        new UploadImg(this, options);
    });
};
})(jQuery);
