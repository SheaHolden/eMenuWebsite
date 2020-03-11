"use strict";
//On load, calculates positioning of various elements, displays menu_manager modal, and hides menu editor/ body.
window.addEventListener('load', function () {
    document.getElementById('menu_manager_modal').style.top = 'calc(50% - 15em)';
    document.getElementById('menu_manager_modal').style.left = 'calc(50% - 150px)';
    document.getElementById('menu_options').style.top = 'calc(50% - 14.1em)';
    document.getElementById('menu_options').style.left = 'calc(50% + 180px)';
    document.getElementById('new_menu_form').style.top = 'calc(50% - 15em)';
    document.getElementById('new_menu_form').style.left = 'calc(50% + 180px)';
    $('#menu_manager_modal').show();
    $('#blanket').show();
});

//Retrieves account name and list of menus on page load
window.addEventListener('load' ,function () {
    $.post("eMenu_controller.php",
        { page: 'MainPage', command: 'GetName' },
        function(data) {
            $("#user_name").html(data);
        });
    $.post("eMenu_controller.php",
        { page: 'MainPage', command: 'GetMenus' },
        function(data) {
            $("#menu_list").html(data);
        });
});

//==============================Menu Manager controls==============================
//TODO: figure out how to format and read data from selected row.
/*
$("tbody tr").click(function () {
    $('.selected').removeClass('selected');
    $(this).addClass("selected");
    var data = $('.d',this).html();
    alert(data);
    $('#menu_options').show();
    $('#new_menu_form').hide();
});
*/

$('#menu_list').click(function () {
    $('#menu_options').show();
    $('#new_menu_form').hide();
});

//Edit menu button
//TODO: post to controller to populate page body with selected menu data.
//TODO: Change hardcoded var name to retrieve selected menu name.
$('#menu_edit').click(function () {
    var name = 'Standard Menu';
    $.post("eMenu_controller.php",
        {page: 'MainPage', command: "GetMenuData", data: name},
        function (data) {
            var rows = JSON.parse(data);
            var tree = '';
            tree += "<table id='menu_edit_table'>";
            tree += "<tr><th>" + name + "</th></tr>";
            for (i in rows.categories){
                tree += "<tr><td class='tree-category'>ᴸ⎯⎯ " + rows.categories[i].category_name + "</td></tr>";
                for (j in rows.categories[i].subcategories){
                    tree += "<tr><td class='tree-subcategory' style='padding-left: 2.5em;'>ᴸ⎯⎯ " + rows.categories[i].subcategories[j].subcategory_name + "</td></tr>";
                    for (k in rows.categories[i].subcategories[j].menu_items) {
                        tree += "<tr><td class='tree-menu-item' style='padding-left: 5em;'>ᴸ⎯⎯ " + rows.categories[i].subcategories[j].menu_items[k].item_name + "</td></tr>";
                    }
                }
            }
            tree += "</table>";
            $("#menu_tree").html(tree);

        });
    $('#menu_manager_modal').hide();
    $('#menu_options').hide();
    $('#blanket').hide();
    $('#body').show();
});

//Activate Menu button
//TODO: change hardcoded var name to retrieve selected menu name.
$('#menu_activate').click(function () {
    var name = 'Standard Menu';

    $.post("eMenu_controller.php",
        { page: 'MainPage', command: 'ActivateMenu', data: name},
        function(data) {
            var response = $("#menu_manager_response");
            response.show();
            response.html(data);
            setTimeout(function(){ response.hide(); }, 3000);
        });
});

//Delete menu button
//TODO: change hardcoded var name to retrieve selected menu name.
$('#menu_delete').click(function () {
    var name = 'Test Menu';
    var confirm = $('#menu_manager_response');
    var del = $('#menu_delete');

    //Changes Delete button to cancel button when clicked.
    if (confirm.css("display") != "none"){
        del.html('Delete');
        del.css("background-color", "#125BFF");
    }else{
        del.html('Cancel');
        del.css("background-color", "darkred");
    }
    confirm.toggle();
    confirm.html('Click here to delete selected menu.');
    confirm.click(function () {
        $.post("eMenu_controller.php",
            { page: 'MainPage', command: 'DeleteMenu', data: name},
            function(data) {
                var response = $("#menu_manager_response");
                response.show();
                response.html(data);
                setTimeout(function(){ response.hide(); }, 3000);
            });
        $.post("eMenu_controller.php",
            { page: 'MainPage', command: 'GetMenus' },
            function(data) {
                $("#menu_list").html(data);
            });
    });
});

//Duplicate menu button
$('#menu_duplicate').click(function () {
    $('#new_menu_form').show();
    $('#menu_options').hide();

    var menu = 'Test Menu';
    var newName = $('#new_menu_name').val();

    if (name != "") {
        $.post("eMenu_controller.php",
            {page: 'MainPage', command: 'DuplicateMenu', data: {menu: menu, newName: newName}},
            function (data) {
                //TODO: assign returned title to menu_tree title
                var response = $("#menu_manager_response");
                response.show();
                response.html(data);
                setTimeout(function(){ response.hide(); }, 3000);
            });
        $.post("eMenu_controller.php",
            { page: 'MainPage', command: 'GetMenus' },
            function(data) {
                $("#menu_list").html(data);
            });
    }else
        $("#new_menu_response").html("");
});

//==============================Page Body Controls=============================
//Menu Item Clicked
$('.tree-menu-item').click(function () {
    var name = 'Standard Menu';
    $.post("eMenu_controller.php",
        {page: 'MainPage', command: "GetMenuData", data: name},
        function (data) {
            var parse = JSON.parse(data);
            var info = '';
            for (i in parse.categories) {
                for (j in parse.categories[i].subcategories) {
                    for (k in parse.categories[i].subcategories[j].menu_items) {
                        info += parse.categories[i].subcategories[j].menu_items[k].item_name;
                    }
                }
            }
            $("#item_name").html(info);
        });
});

//==============================New Menu Controls==============================
//New Menu Button
$('#new_menu').click(function () {
    $('#menu_options').hide();
    $('#new_menu_form').show();
    $('#blanket').show();

});

//Cancel button
$('#new_cancel').click(function () {
    $('#new_menu_form').hide();
    //$('#menu_options').show();
});

//Submit button
//TODO: fix bug described in model.
$('#new_submit').click(function () {

    //var name = $('#new_menu_name').val();
    var name = 'New Menu';

    if (name != '') {
        $.post("eMenu_controller.php",
            {page: 'MainPage', command: 'CreateNewMenu', data: name},
            function (data) {
                //TODO: assign returned title to menu_tree title
                $("#menu_manager_response").html(data);
            });
        $.post("eMenu_controller.php",
            { page: 'MainPage', command: 'GetMenus' },
            function(data) {
                $("#menu_list").html(data);
            });
    }else
        $("#new_menu_response").html("");
});

//==============================Account/ dropdown controls==============================
//Account button
$('#account_btn').click(function () {
    $('.nav-dropdown').toggle();
});

//Manage Menus
$('#nav_manage_menus').click(function () {
    $('#menu_manager_modal').show();
    $('#blanket').show();
    $('.nav-dropdown').hide();
});

//Sign out
$('#nav_sign_out').click(function () {
    $.post("eMenu_controller.php" ,
        {page:'MainPage', command:'SignOut'},
        function(data) {}
    );
});

//==============================Timeout==============================
document.getElementById('nav_sign_out').addEventListener('click', function() {
    timeout();
});

var timer = setTimeout(timeout, 10 * 60 * 1000);
window.addEventListener('mousemove', event_listener_mousemove_or_keydown);
window.addEventListener('keydown', event_listener_mousemove_or_keydown);  // for keyboard action
window.addEventListener('unload', function() {  // when the window is closed
    timeout();
});
function event_listener_mousemove_or_keydown() {
    clearTimeout(timer);
    timer = setTimeout(timeout, 10 * 60 * 1000);
}
function timeout() {
    document.getElementById('form_sign_out').submit();
}