
/*   工具函数集  */


// 把文件转换成可读URL
function getObjectURL(file) {
    var url = null;
    if (window.createObjectURL != undefined) { // basic
        url = window.createObjectURL(file);
    } else if (window.URL) { // mozilla(firefox)
        url = window.URL.createObjectURL(file);
    } else if (window.webkitURL) { // webkit or chrome
        url = window.webkitURL.createObjectURL(file);
    }
    return url;
}


/* 数组删除指定元素 */
Array.prototype.removeByValue = function(val){
    // 比较两个json对象是否相等
    function compare(objA, objB) {
        function isObj(object) {// 判断是不是对象
            return object && typeof(object) == 'object' && Object.prototype.toString.call(object).toLowerCase() == "[object object]";
        }
        function isArray(object) {// 判断是不是数组
            return Object.prototype.toString.apply(object) === '[object Array]';
        }
        function getLength(object) {// 获取对象的长度
            var count = 0;
            for(var i in object) count++;
            return count;
        }
        function CompareObj(objA, objB, flag) {
            for(var key in objA) {
                if(!flag) //跳出整个循环
                    break;
                if(!objB.hasOwnProperty(key)) {
                    flag = false;
                    break;
                }
                if(!isArray(objA[key])) { //子级不是数组时,比较属性值
                    if(objB[key] != objA[key]) {
                        flag = false;
                        break;
                    }
                } else {
                    if(!isArray(objB[key])) {
                        flag = false;
                        break;
                    }
                    var oA = objA[key],
                        oB = objB[key];
                    if(oA.length != oB.length) {
                        flag = false;
                        break;
                    }
                    for(var k in oA) {
                        if(!flag) //这里跳出循环是为了不让递归继续
                            break;
                        flag = CompareObj(oA[k], oB[k], flag);
                    }
                }
            }
            return flag;
        }
        if(!isObj(objA) || !isObj(objB)) return false; //判断类型是否正确
        if(getLength(objA) != getLength(objB)) return false; //判断长度是否一致
        return CompareObj(objA, objB, true); //默认为true
    }
    for (var i = this.length - 1; i >= 0; i--) {
        if (this[i] === val || compare(val, this[i])) {
            this.splice(i, 1);
            break;
        }
    }
};


/* 注册表单验证规则 layui——form */
layui.use(['form', 'layer'], function(){
    var form = layui.form;
    var layer = layui.layer;
    form.verify({
        // 验证大于0的数字（包括小数）
        floatNumber: function(value, elem){
            if(!/^[1-9]\d*|0$/.test(value) && !/^[1-9]\d*.\d*|0.\d*[1-9]\d*$/.test(value)){
                return '必须是不小于0的数字';
            }
        },
        // 地区选择的隐藏域不能为空，地区必须选择
        selectedaddr: function(value, item){
            if(!(value !== '')){
                return '请选择隶属关系地区选择';
            }
        },
        // 贫困户脱贫指标，有：则需填写；无：则不填
        ishave: function(value, item){
            if($(item).parent().find("input[name='radio']:checked").val() && (value === '')){
                return '指标项不能为空';
            }
        },
        requiredbyorg: function(value, item){
            if(value === ''){
                return '请先选择上级机构';
            }
        },
        requiredorgtype: function (value, item) {
            if(value === '0' || value === ''){
                return '请先选择机构级别';
            }
        },
        ishavepic: function (value, item) {
            var radio = $(item).parents('dd.right').find('input[type="radio"]:checked');

            // 验证村基本信息录入时的卫生与计划生育信息
            var isWeishenshiInput = $(item).parents('.right').find('#weishenshishu');
            var isChunYiInput = $(item).parents('.right').find('#chunyigeshu');
            var isCeshuoInput = $(item).parents('.right').find('#gonggongcesuoshu');
            // var isLajijiInput = $(item).parents('.right').find('#lajijizhongshu');
            var isLajixiangInput = $(item).parents('.right').find('#lajixiangshu');
            var isBaojieInput = $(item).parents('.right').find('#gudingbaojiegeshu');
            var isShuwuInput = $(item).parents('.right').find('#shuwugeshu');
            var isCangshuInput = $(item).parents('.right').find('#cangshuceshu');
            var isWenGuangInput = $(item).parents('.right').find('#wenhuaguangchangshu');
            var isJianshenInput = $(item).parents('.right').find('#jianshenqicaizhonglei');

            var reg = new RegExp("^[1-9]\\d*$");// 正整数（不包含0）
            var isHavePic = ( /*(radio.length && (radio.val() === '是')) || */(isWeishenshiInput.length && reg.test(parseInt(isWeishenshiInput.val()))) || (isChunYiInput.length && reg.test(parseInt(isChunYiInput.val()))) || (isCeshuoInput.length && reg.test(parseInt(isCeshuoInput.val()))) || (isLajixiangInput.length && reg.test(parseInt(isLajixiangInput.val()))) || (isBaojieInput.length && reg.test(parseInt(isBaojieInput.val()))) || (isShuwuInput.length && reg.test(parseInt(isShuwuInput.val()))) || (isCangshuInput.length && reg.test(parseInt(isCangshuInput.val()))) || (isWenGuangInput.length && reg.test(parseInt(isWenGuangInput.val()))) || (isJianshenInput.length && reg.test(parseInt(isJianshenInput.val()))) );
            if (isHavePic) {
                if (!value || (mytools.img_data_decode(value) === '[]') || (value === 'null')) {
                    var msg = $(item).parent().parent().find('.notice').text().split('：')[1].replace(/(^\s*)|(\s*$)/g, '');
                    return msg ? '请上传图片:'+msg : '请上传图片';
                }
            } else if ( (radio.length && (radio.val() === '是')) && ($(item).attr('name') === 'item[7][shuinilu][piclist]' || $(item).attr('name') === 'item[7][zhuchengqugongjiao][piclist]') ) {
                if (!value || (mytools.img_data_decode(value) === '[]') || (value === 'null')) {
                    var msg = $(item).parent().parent().find('.notice').text().split('：')[1].replace(/(^\s*)|(\s*$)/g, '');
                    return msg ? '请上传图片:'+msg : '请上传图片';
                }
            } else {
                if (!radio.length && !isWeishenshiInput.length && !isChunYiInput.length && !isCeshuoInput.length && !isLajixiangInput.length && !isBaojieInput.length && !isShuwuInput.length && !isCangshuInput.length && !isWenGuangInput.length && !isJianshenInput.length) {
                    if (!value || (mytools.img_data_decode(value) === '[]') || (value === 'null')) {
                        var msg = $(item).parent().parent().find('.notice').text().split('：')[1].replace(/(^\s*)|(\s*$)/g, '');
                        return msg ? '请上传图片:'+msg : '请上传图片';
                    }
                }
            }
        },
        requiredradio: function(value, item){
            if(!$(item).attr('checked') && !$(item).siblings('input[type="radio"]').attr('checked')){
                return $(item).parent().prev().text()+'必选一个值';
            }
        },
        // 身份证号码验证
        identity: function(value, item){
            if (!(/^[1-9]\d{5}(18|19|20)\d{2}((0[1-9])|(1[0-2]))(([0-2][1-9])|10|20|30|31)\d{3}[0-9Xx]$/.test(value))) {
                return '请输入正确的身份证号码';
            }
        },
        // 残疾证 20位数字验证
        disabledcert: function(value, item){
            if (!(/^[1-9]\d{5}(18|19|20)\d{2}((0[1-9])|(1[0-2]))(([0-2][1-9])|10|20|30|31)\d{3}[0-9Xx]\d{2}$/.test(value))) {
                return '请输入正确的残疾证号码';
            }
        },
        // 贫困户脱贫指标的图片上传验证
        alleviationpic: function(value, item){
            var reg = new RegExp('[\\u4E00-\\u9FA5\\uF900-\\uFA2D]', 'g');// 匹配中文
            if (!value || (mytools.img_data_decode(value) === '[]') || (value === 'null') || (reg.test(value))) {
                var msg = $(item).parent().parent().find('.notice').text().split('：')[1].replace(/(^\s*)|(\s*$)/g, '');
                return msg ? '请上传图片:'+msg : '请上传图片';
            }
        }
    });


});



var mytools = {
    // 计算图片宽高，按单边100px大小居中显示
    dealImgwh: function(imglist){
        for(var i=0; i < imglist.length; i++){
            var ratio = (imglist[i].width/imglist[i].height).toFixed(2);
            if(ratio > 1){
                var h = (100*imglist[i].width)/imglist[i].height;
                imglist.eq(i).css({
                    width: '100px',
                    height: h+'px',
                    top: (100-h)/2+'px',
                    left: 0
                });
            } else {
                var w = (100*imglist[i].width)/imglist[i].height;
                imglist.eq(i).css({
                    width: w+'px',
                    height: '100px',
                    top: 0,
                    left: (100-w)/2+'px'
                });
            }
        }
    },
    // 上传文件的过滤，生成url
    dealFileFilter: function(json){
        var options = {
            filedata: json.filtes, // 初始文件列表
            maxFileSizeImg: json.maxFileSizeImg || 1024 * 1024 * 5,// 图片最大5M
            minFileSizeImg: json.minFileSizeImg || 0,// 图片最小0
            errorMsg: {                     // 文件出错提示信息
                fileSizeErr: '该图片大小已超出规定大小范围（5M以内）',
                fileTypeErr: '改图片格式超出被允许上传的格式范围'
            }
        };
        function filter(files, options){
            var self = options;
            var arr = [];
            var byfilter = [];// 被过滤的文件集合
            for(var i = 0; i < files.length; i++){
                var file = files[i];
                if(/(.*)\.(jpg|bmp|gif|ico|pcx|jpeg|tif|png|raw|tga)$/g.test(file.name)){
                    if(file.size > self.maxFileSizeImg || file.size < self.minFileSizeImg){
                        // 文件大小不被允许
                        byfilter.push({name: file.name, reason: self.errorMsg.fileSizeErr});
                    } else {
                        arr.push(file);
                    }
                } else {
                    // 文件类型错误
                    byfilter.push({name: file.name, reason: self.errorMsg.fileTypeErr});
                }
            }
            if(byfilter.length){
                json.onfailMsgDeal(byfilter);
            }
            return arr;
        }
        // 把文件转换成可读URL
        function getObjectURL(file) {
            var url = null;
            if (window.createObjectURL != undefined) { // basic
                url = window.createObjectURL(file);
            } else if (window.URL) { // mozilla(firefox)
                url = window.URL.createObjectURL(file);
            } else if (window.webkitURL) { // webkit or chrome
                url = window.webkitURL.createObjectURL(file);
            }
            return url;
        }

        // 上传文件
        function fnUploadFiles(filedata) {
            var formData = new FormData();
            for(var i=0, file; file = filedata[i]; i++){
                formData.append('file[]', file);
            }
            var xhr = new XMLHttpRequest();
            if(xhr.upload){
                xhr.upload.addEventListener('progress', function (e) {
                    // console.log('上传进度:', e.loaded/e.total);
                });
            }

            xhr.open('POST', json.url, true);
            xhr.send(formData);

            xhr.onreadystatechange = function(e){
                if(xhr.readyState === 4){
                    if(xhr.status === 200){
                        json.onSuccess(xhr.responseText);// 文件上传成功
                    }else {
                        json.onDealErr('图片上传失败！');
                    }
                }
            }
        }

        options.filedata = filter(options.filedata, options);
        var filterurl = [];
        for(var i = 0, item; item = options.filedata[i]; i++){
            filterurl.push(getObjectURL(item));
        }
        if(options.filedata.length && filterurl.length){
            // 显示图片
            json.onshowimg(filterurl);
            // 上传图片
            // json.onuploadimg(options.filedata);
            fnUploadFiles(options.filedata);
        }

    },
    img_data_encode: function(obj){
        // 编码
        return encodeURI( JSON.stringify(obj) );
    },
    img_data_decode: function(str){
        // 解码
        return JSON.parse( unescape(str) );
    },
    // 上传图片的弹窗函数，点击出现弹窗的dom对象（jq），保存上传图片信息hidden的input控件(jq),允许上传图片的最大个数
    fnFilesUploadCPM: function(btnobj, inputhidden, upload, uploadurl){
        var picnum = (function(){
            var data = $(btnobj).data('imgnum');
            return (data && parseInt(data) >= 0) ? parseInt(data) : -1;
        })();
        var This = btnobj;
        var html = '';
        // 保存确定添加的图片(老照片和新添加的)
        var addlist = (function(){
            var oldpiclist = inputhidden;//$(This).next('input');
            var reg = new RegExp('[\\u4E00-\\u9FA5\\uF900-\\uFA2D]', 'g');// 匹配中文
            if (oldpiclist.length && oldpiclist.val() && (!reg.test(oldpiclist.val())) && (oldpiclist.val() !== 'null' && (mytools.img_data_decode(oldpiclist.val() !== '[]')))) {
                return val = mytools.img_data_decode(oldpiclist.val());
            } else {
                return [];
            }
        })();

        if(addlist.length){
            for(var i=0;i<addlist.length;i++){
                html += '<div class="img_box"><span class="file-item"><a href='+upload+'/'+addlist[i].url+' target="_blank" class="read a">'+addlist[i].name+'</a><i class="layui-icon layui-icon-close"></i></span></div>';
            }
        }
        // console.log('维护的图片列表', addlist);
        layui.use('layer', function(){
            var layer = layui.layer;
            var flag = false; // 判断图片列表是否全部上传完毕
            layer.open({
                title: '文件管理',
                type: 1,
                area: ['70%', '60%'],
                fixed: false,
                maxmin: true,
                btn: ['确定'],
                content: '<div id="mangpic"><div class="picBox"></div><p class="notice">当前已有文件&nbsp;<span class="have">0</span>&nbsp;/&nbsp;总共可传文件&nbsp;<span class="totalpic">0</span></p></div>',
                success: function(elem, index){
                    // 上传图片
                    $(elem).find('.picBox').uploadImg({
                        "url": uploadurl,
                        "picnum": picnum,
                        onReady: function () {
                            $(elem).find('.previewBox').append(html);
                            $("#mangpic .notice .totalpic").text((picnum >= 0) ? picnum : '不限');
                            $("#mangpic .notice .have").text($("#mangpic .img_box").length);
                        },
                        onDealTypeErr: function (errlist) {
                            var html = '';
                            for(var i=0, item; item = errlist[i];i++){
                                html += "<li><span>"+item.name+"</span>&nbsp;&nbsp;:&nbsp;&nbsp;<span>"+item.reason+"</span></li>";
                            }
                            layer.open({
                                title: '被过滤的文件列表及原因',
                                type: 1,
                                content: "<ul id='reasonbyfiles'>"+html+"</ul>",
                                area: ['50%', "40%"],
                                btn: ['确定'],
                                yes: function (index, layero) {
                                    layer.close(index);
                                }
                            });
                        },
                        onSuccess: function (param) {
                            flag = true;
                            for(var i = 0; i < param.length; i++){
                                addlist.push(param[i]);
                            }
                            layer.msg('文件上传成功!', {icon: 1, time: 1000});
                            $("#mangpic .notice .have").text($("#mangpic .img_box").length);
                        },
                        onError: function (err) {
                            var msg = "code: "+ err.code +", msg: "+ err.msg;
                            layer.confirm(msg, {icon: 2, title: '错误提示'}, function(index){
                                layer.close(index);
                            });
                        },
                        onHint: function (param) {
                            // 提示
                            // console.log('温馨提示：', param);
                            layer.msg(param, {icon: 0, time: 2000});
                        }
                    });

                    // 删除图片
                    $(elem).find("#mangpic").on('click', '.layui-icon-close', function (event) {
                        event.stopPropagation();
                        var imgIndex = $(this).parents('.img_box').index();
                        addlist.removeByValue(addlist[imgIndex]);
                        $(this).parents('.img_box').remove();
                        $("#mangpic .notice .have").text($("#mangpic .img_box").length);
                    });
                },
                yes: function(index, layero){
                    console.log('addlist', addlist);
                    var encodeval = mytools.img_data_encode(addlist);
                    inputhidden.val(encodeval);
                    layer.close(index);
                }
            });
        });
    },
    // 一弹窗轮播的形式查看表格中的图片 layui中的layer对象，进行编码的图片地址数组
    fnShowPicInTableByWindow: function(layer, rel, upload){
        if (rel && (rel !== 'null') && (mytools.img_data_decode(rel) !== '[]')) {
            var data = mytools.img_data_decode(rel);
            var html = '';
            for(var i=0;i<data.length;i++){
                html += '<li><img src='+upload+'/'+data[i].url+' title='+data[i].name+' alt='+data[i].name+'></li>';
            }
            layer.open({
                type: 1,
                title: false,
                closeBtn: 0,
                content: '<div id="tableshowpic" class="showpic-box"><ul class="pic-list clearfix">'+html+'</ul><div class="btnbox"><i class="btn prev">&lt;</i><i class="btn next">&gt;</i></div></div>',
                area: ['70%', '80%'],
                shadeClose: true,
                success: function(index, layero){
                    var wid = $('#tableshowpic').width();
                    var hei = $('#tableshowpic').height();
                    // 计算ul和li的宽度
                    function fnCountImg(){
                        var imgs = $('.pic-list li');
                        var tableshowpic = $('#tableshowpic');
                        $(".pic-list").width(Math.ceil( tableshowpic.width()*imgs.length ));
                        imgs.width(tableshowpic.width());
                    }
                    // 计算文件的大小及居中显示 最大宽度(数字)， 最大高度(数字)
                    function fnPosiImg(img, wid, hei){
                        var boxratio = (wid/hei).toFixed(2);
                        for(var i = 0; i < img.length; i++){
                            var ratio = (img[i].width/img[i].height).toFixed(2);
                            if(ratio < boxratio){
                                var w = (hei/img[i].height)*img[i].width;
                                img.eq(i).css({
                                    width: w+'px',
                                    height: hei+'px',
                                    top: 0,
                                    left: (wid-w)/2+'px'
                                });
                            } else {
                                var h = (wid/img[i].width)*img[i].height;
                                img.eq(i).css({
                                    width: wid+'px',
                                    height: h+'px',
                                    top: (hei-h)/2+'px',
                                    left: 0
                                });
                            }
                        }
                    }
                    // 注册左右点击事件
                    function fnBanner(time){
                        var btni = $("#tableshowpic .btnbox i");
                        var piclist = $("#tableshowpic .pic-list");
                        var piclistli = $("#tableshowpic .pic-list li");
                        var left = $("#tableshowpic .pic-list").position().left;
                        var num = 1;// 当前文件的序号
                        btni.click(function(){
                            var index = $(this).index();
                            if(index){
                                num++;
                                if(num > (piclistli.length)){
                                    num = 1;
                                }
                            } else {
                                num--;
                                if(num < 1){
                                    num = piclistli.length;
                                }
                            }
                            piclist.animate({"left": -wid*(num-1)+'px'}, time);
                        });
                    }

                    fnCountImg();
                    var imgs = $('.pic-list li img');
                    var defereds = [];
                    imgs.each(function(){
                        var dfd = $.Deferred();
                        $(this).load(dfd.resolve);
                        defereds.push(dfd);
                    });
                    $.when.apply(null, defereds).done(function(){
                        fnPosiImg(imgs, wid, hei);
                    });
                    if($(".pic-list li").length > 1){
                        fnBanner(500);
                    } else {
                        $("#tableshowpic .btnbox i").hide();
                    }
                }
            });
        } else {
            layer.msg('这里暂时没有文件', {icon: 0,time: 2000});
        }
    }

};
