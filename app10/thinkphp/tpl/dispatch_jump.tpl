{__NOLAYOUT__}<!DOCTYPE html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>跳转提示</title>
    <script src="__STATIC__/admin/js/jquery-1.7.2.min.js"></script>
    <style type="text/css">
        *{ padding: 0; margin: 0; }
        #msg-tips{width: 700px; margin: 10% auto;}
        #msg-tips img{width: 700px;}
        #msg-tips .msgbox{margin-top: 60px;padding-left: 150px;}
        #msg-tips .msgbox > div{float: left;}
        #msg-tips .word{min-height: 31px;line-height: 31px;width:200px;border: 2px solid #e2e2e2;box-sizing: border-box;padding: 0 10px;}
        #msg-tips .bar{position: relative; width: 100px; height: 35px; line-height: 35px;text-align: center; background: #e2e2e2; color: #fff; overflow: hidden;margin-left: 10px;}
        #msg-tips .bar p{position: absolute; top: 0; left: -100px; width: 100%; height: 100%; background: #438eb9;}
        #msg-tips .bar span{position: absolute; top:0;left:0;width: 100%;height: 100%;display: block;z-index: 100; cursor: pointer;}
    </style>
</head>
<body>
    <!--<div class="system-message">
        <p><?php echo($code);?> - > <?php echo(strip_tags($msg));?></p>
        <p class="detail"></p>
        <p class="jump">
            页面自动::::: <a id="href" href="<?php echo($url);?>">跳转</a> 等待时间： <b id="wait"><?php echo($wait);?></b>
        </p>
    </div>-->
    <div id="msg-tips">
        <div id="img"><img src="__IMAGES__/fupin_logo.png"></div>
        <div class="msgbox">
            <div class="word">
                <p><?php echo(strip_tags($msg));?></p>
            </div>
            <div class="bar"><p></p><span>GO</span></div>
        </div>
        <div style="display: none;" class="hidden"><span id="time"><?php echo($wait);?></span><span id="href"><?php echo($url);?></span><span id="code"><?php echo($code);?></span></div>
    </div>
    <script type="text/javascript">
        (function(){
            var time = parseInt( $("#time").text() );
            var href = $("#href").text();
            var code = $("#code").text();
            $("#img img").attr('src', '__IMAGES__/hintimg/'+Math.ceil(Math.random()*6)+'.jpg');
            if(code !== '1'){
                $("#msg-tips .word").css('border-color', 'red');
            }
            $("#msg-tips .bar p").animate({
                "left": "0"
            }, time*1000, function(){
                window.location.href = href;
            });
            $("#msg-tips .bar span").click(function(){
                window.location.href = href;
            });
            $(window).keydown(function(event){
                switch (event.keyCode) {
                    case 13:
                        window.location.href = href;
                        break;
                }
            });
        })();
    </script>
</body>
</html>
