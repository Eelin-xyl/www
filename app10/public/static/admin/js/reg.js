/**
 * Created by 贾芸吉 on 2018/6/23.
 */

//当页面内容都加载完才执行
$(document).ready(function(e) {
    //加载三个下拉列表
    $("#ssj").html("<select id='Area'></select>");
    //加载显示数据
    FillArea();

    //当省份选中变化，重新加载市和区
    $("#Area").change(function(){
        //当元素的值发生改变时，会发生 change 事件,该事件仅适用于文本域（text field），以及 textarea 和 select 元素。
        //加载市
        $("#sss").html("<select name='areaS' id='AreaS'></select>");
        FillShi();

        //当市选中变化，重新加载区
        $("#AreaS").change(function(){
            console.log(123123);
            //加载区

            FillQu();

        })
    })



});


//加载省份信息
function FillArea()
{
    //取父级代号
    var pid =$("#Area").val();
   // alert(pid);
    //根据父级代号查数据
    $.ajax({
        //取消异步，也就是必须完成上面才能走下面
        async:false,
        url: "getAreaJ",
        data:{pid:pid},
        type:"POST",
        dataType:"JSON",
        success: function(temp){

            $("#Area").html(temp);
        }
    });
}
//加载市信息
function FillShi()
{
    //取父级代号
    var pid =$("#Area").val();

    //根据父级代号查数据
    $.ajax({

    //取消异步，也就是必须完成上面才能走下面
        async:false,
        url:"getAreaS",
        data:{pid:pid},
        type:"POST",
        dataType:"JSON",
        success: function(temp){
            $("#AreaS").html(temp);

        }
    });
}

//加载区信息
function FillQu()
{
    //取父级代号
    var pcode =$("#AreaS").val();
    //根据父级代号查数据
    $.ajax({
        //不需要取消异步
        url:"getAreaQ",
        data:{pid:pcode},
        type:"POST",
        dataType:"JSON",
        success: function(data){
            $("#ssq").html(data);

        }
    });
}
