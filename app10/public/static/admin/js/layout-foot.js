 /* ------多选和动态菜单-------- */
//打开之菜单
var menuOpened = $('#menu-now').attr('value');
if (menuOpened == 'Rbac') {
    $('.submenu').eq(0).show();
} else if ( menuOpened == 'Subitem'){
    $('.submenu').eq(1).show();
}
$(".menu-box").find('#menu').eq(0).click(function() {
    $('.menu-box').find(".submenu").eq(0).slideToggle('slow');
});
$(".menu-box").find("#menu").eq(1).click(function() {
    $('.menu-box').find(".submenu").eq(1).slideToggle('slow');
});

//选中菜单高亮
if (menuOpened == 'Rbac') {
    var menuOpened = $('#node-now').attr('value');
} else if (menuOpened == 'Subitem') {
    var menuOpened = $('#node-now').attr('value');
}
var selected=$('[name="' + menuOpened + '"]');
selected.css({'background-color' : '#E8F2FE', 'color' : 'grey'});
//多选
var checkAll = false;
var checkbox = $('#checkbox11');
checkbox.click(function() {
    if (!checkAll) {
        $('input').attr('checked', true);
        checkAll = true;
    } else {
        $('input').attr('checked', false);
        checkAll = false;
    }
});