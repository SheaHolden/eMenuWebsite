<?php
if (!isset($_SESSION['SignIn'])) {
    include('signin_page.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>eMenu - Web Editor</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <link rel="stylesheet" type="text/css" href="main_styles.css">
    <!--<script src="eMenu_script.js"></script>-->
    <script src="populatejs/populatejs.min.js"></script>
</head>

<header>
    <div class="container" id="header">
        <!--==================================Header=======================================-->
        <div class="row" id="header_light">
            <div class="bg-primary">
                <div class="col-lg-2">
                    <img src="eMenu_logo.png" alt="eMenu Logo" id="eMenu_logo">
                </div>
                <div class="col-lg-9">
                    <p id="user_name">Name...</p>
                </div>
                <div class="col-lg-1">
                    <img src="account_icon.png" alt="account" id="account_btn">
                    <div class="nav-dropdown" id="account_dropdown">
                        <div class="row" id="nav_manage_menus">
                            <a>Manage Menus</a>
                        </div>
                        <div class="row" id="nav_account_settings">
                            <a>Account Settings</a>
                        </div>
                        <div class="row" id="nav_help">
                            <a>Help</a>
                        </div>
                        <div class="row" id="nav_sign_out">
                            <a>Sign Out</a>
                        </div>
                    </div>
                </div>
            </div>
            <!-- SignOut form -->
            <form method='post' action='eMenu_controller.php' id='form_sign_out' style='display:none'>
                <input type='hidden' name='page' value='MainPage'>
                <input type='hidden' name='command' value='SignOut'>
            </form>
        </div>
        <div class="row" id="header_dark"></div>
    </div>
</header>

<body>
    <!--==================================Body=======================================-->
    <div id="blanket"></div>
    <div class="container" id="body">
        <div class="row" id="edit_body">
            <div class="col-lg-3" id="menu_tree">
                <p id="tree_menu_name">Loading Menu Details...</p>
            </div>
            <div class="col-lg-6" id="menu_edit_form">
                <h1 style="text-align: center;" id="item_name">Item Name</h1>
                <div class="row">
                    <form id="menu_edit_rows">
                        <label class="form-label">Item Name:</label>
                            <input class="form-input" id="form_item_name" type="text" name="form_item_name" required>
                        <br>
                        <label class="form-label">Item Category: </label>
                        <input class="form-input" id="form_item_category" type="text" name="form_item_category" required>
                        <br>
                        <label class="form-label">Item Subcategory: </label>
                        <input class="form-input" id="form_item_subcategory" type="text" name="form_item_subcategory" required>
                        <br>
                        <label class="form-label">Item Price: </label>
                        <input class="form-input" id="form_item_price" type="text" name="form_item_price" required>
                        <br>
                        <label class="form-label">Item Badges: </label>
                        <input class="form-input" id="form_item_Badges" type="text" name="form_item_Badges" required>
                        <br>
                        <label class="form-label">Item Image: </label>
                        <input class="form-input" id="form_item_image" type="text" name="form_item_image" required>
                        <br>
                        <label class="form-label">Item Description: </label>
                        <input class="form-input" id="form_item_description" type="text" name="form_item_description" required>
                    </form>
                </div>
            </div>
            <div class="col-lg-2" id="node_controls">
                <div class="row" style="height: 62.5vh">
                    <button id="add_category" class="menu-node-btn">New Category</button>
                    <button id="add_subcategory" class="menu-node-btn">New Subcategory</button>
                    <button id="add_badge" class="menu-node-btn">New Badge</button>
                    <button id="add_image" class="menu-node-btn">Import Image</button>
                </div>
                <div class="row">
                    <button id="save_changes" class="menu-node-btn">Save Changes</button>
                    <button id="node_menu_activate" class="menu-node-btn">Activate Menu</button>
                </div>
            </div>
        </div>
    </div>

    <!--==================================Modals=======================================-->
    <!--Menu Manager Modal-->
    <div class="container">
        <div class="column" id="menu_manager_modal">
            <div class="row" style="padding: 0 1em;" id="menu_top">
                <h2>Manage Menus</h2>
                <button id="new_menu">New <b>+</b></button>
            </div>
            <div class="row" id="menu_list" style="padding: 1em">
                Loading Menus...
            </div>
            <div class="row" style="text-align: center; font-weight: bold; margin: -10px;">
                <p id="menu_manager_response" style="display: none; height: 1.5em; background-color: #125BFF"></p>
            </div>
        </div>
        <div class="column" id="menu_options">
            <div class="row" id="menu_edit">
                <a>Edit</a>
            </div>
            <div class="row" id="menu_duplicate">
                <a>Duplicate</a>
            </div>
            <div class="row" id="menu_delete">
                <a>Delete</a><br>
            </div>
            <div class="row" id="menu_activate">
                <a>Activate</a><br>
            </div>
        </div>
    </div>

    <!--New Menu Modal-->
    <div class="container">
        <div class="column">
            <div class="row"  id="new_menu_form">
                <form>
                    <label class="form-label">Menu Name</label><br>
                    <input class="field" id="new_menu_name" type="text" name="new_menu_name" required>
                    <br>
                    <input class="btn" id="new_submit" type="submit" value="Create">
                    <input class="btn" id="new_cancel" type="button" value="Cancel"><br>
                </form>
            </div>
        </div>
    </div>
</body>

<script>
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
        $.post("eMenu_controller.php",
            { page: 'MainPage', command: 'GetName' },
            function(data) {
                $("#user_name").html(data);
            });
    });

    //==============================Menu Manager controls==============================
    //Retrieves account name and list of menus on page load
    window.addEventListener('load' ,function () {
        var name = '';
        $.post("eMenu_controller.php",
            { page: 'MainPage', command: 'GetMenus' },
            function(data) {
                $("#menu_list").html(data);

                //Formats and gets selected menu's name
                $('#menu_list tr.menu-list-row').click(function () {
                    $('.selected').removeClass('selected');
                    $(this).addClass("selected");
                    name = $('.d',this).html();
                    $('#menu_options').show();
                    $('#new_menu_form').hide();
                });
            });

        //functions used throughout.
        function refresh_menu_list(){
            $.post("eMenu_controller.php",
                { page: 'MainPage', command: 'GetMenus' },
                function(data) {
                    $("#menu_list").html(data);
                });
        }
        function node_activate_menu(){
            $.post("eMenu_controller.php",
                { page: 'MainPage', command: 'ActivateMenu', data: name},
                function(data) {
                    alert(data);
                });
        }
        function activate_menu(){
            $.post("eMenu_controller.php",
                { page: 'MainPage', command: 'ActivateMenu', data: name},
                function(data) {
                    var response = $("#menu_manager_response");
                    response.show();
                    response.html(data);
                    setTimeout(function () {response.hide();}, 3000);
                });
        }

        //Edit menu button
        $('#menu_edit').click(function () {
            edit_menu();
            $('#menu_manager_modal').hide();
            $('#menu_options').hide();
            $('#blanket').hide();
            $('#body').show();
        });

        //Activate Menu button
        $('#menu_activate').click(function () {
            activate_menu();
        });

        //Delete menu button
        $('#menu_delete').click(function () {
            var confirm = $('#menu_manager_response');
            var del = $('#menu_delete');

            //Changes Delete button to cancel button when clicked.
            if (confirm.css("display") !== "none"){
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
                        del.html('Delete');
                        del.css("background-color", "#125BFF");

                        refresh_menu_list();
                    });
            });
        });

        //Duplicate menu button
        $('#menu_duplicate').click(function () {
            $('#new_menu_form').show();
            $('#menu_options').hide();

            var newName = $('#new_menu_name').val();

            if (newName !== "") {
                $.post("eMenu_controller.php",
                    {page: 'MainPage', command: 'DuplicateMenu', data: {menu: name, newName: newName}},
                    function (data) {
                        var response = $("#menu_manager_response");
                        response.show();
                        response.html(data);
                        setTimeout(function(){ response.hide(); }, 3000);

                        refresh_menu_list();
                    });
            }else
                $("#new_menu_response").html("");
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
        });

        //Submit button
        //TODO: fix bug described in model.
        $('#new_submit').click(function (event) {

            event.preventDefault();
            var newName = $('#new_menu_name').val();
            if (newName !== "") {
                $.post("eMenu_controller.php",
                    {page: 'MainPage', command: 'CreateNewMenu', data: newName},
                    function (data) {
                        //TODO: assign returned title to menu_tree title and close modal
                        $("#menu_manager_response").html(data);

                        refresh_menu_list();
                    });
            }else
                $("#new_menu_response").html(".....");
        });

        //==============================Page Body Controls=============================
        //Populates menu tree and provides functionality to populate menu edit form with selected item details.
        function edit_menu() {
            $.post("eMenu_controller.php",
                {page: 'MainPage', command: "GetMenuData", data: name},
                function (data) {
                    get_json_data('tree', data, '');

                    //Menu item clicked
                    //TODO: Fix bug where only items in "sandwiches" subcategory updates form fields
                    var item = $('#menu_edit_table tr.i');
                    item.click(function () {
                        $('.selected').removeClass('selected');
                        $(this).addClass("selected");
                        item = $('.tree-menu-item',this).html();
                        item = item.split("ᴸ⎯⎯ ").pop();
                        $("#item_name").html(item);
                        get_json_data('form', data, item);
                    });
                });
            //TODO: node control button functionality
        }

        //reads all menu data and returns specific fields depending on method
        function get_json_data(method, data, iItem){
            var file = JSON.parse(data);
            var tree = '';
            var iCat, iSub, iName, iPrice, iBadge, iDesc, iImg;

            if (method === 'tree'){
                tree += "<table id='menu_edit_table'>";
                tree += "<tr><th>" + name + "</th></tr>";
                for (var i in file.categories) {
                    tree += "<tr class='c'><td class='tree-category'>ᴸ⎯⎯ " + file.categories[i].category_name + "</td></tr>";
                    for (var j in file.categories[i].subcategories) {
                        tree += "<tr class='s'><td class='tree-subcategory' style='padding-left: 2.5em;'>ᴸ⎯⎯ " +
                            file.categories[i].subcategories[j].subcategory_name + "</td></tr>";
                        for (var k in file.categories[i].subcategories[j].menu_items) {
                            tree += "<tr class='i'><td class='tree-menu-item' style='padding-left: 5em;'>ᴸ⎯⎯ " +
                                file.categories[i].subcategories[j].menu_items[k].item_name + "</td></tr>";
                        }
                    }
                }
                tree += "</table>";
                $("#menu_tree").html(tree);
                //clears form fields when tree is refreshed
                $('#menu_edit_rows > input').val("");
                $('#item_name').html("Item Name");
            // When method = form, edit fields are populated with data form json
            }else if (method === 'form'){
                //Parsing through nested json arrays to get to menu items
                for (var a in file.categories) {
                    for (var b in file.categories[a].subcategories) {
                        for (var c in file.categories[a].subcategories[b].menu_items) {
                             for (var l in file.categories[a].subcategories[b].menu_items[c].item_name) {
                                 if((file.categories[a].subcategories[b].menu_items[l].item_name) === iItem) {
                                     iCat = file.categories[a].category_name;
                                     iSub = file.categories[a].subcategories[b].subcategory_name;
                                     iName = file.categories[a].subcategories[b].menu_items[l].item_name;
                                     iPrice = file.categories[a].subcategories[b].menu_items[l].item_price;
                                     iBadge = file.categories[a].subcategories[b].menu_items[l].item_badges;
                                     iDesc = file.categories[a].subcategories[b].menu_items[l].item_desc;
                                     //iImg = file.categories[a].subcategories[j].menu_items[k].item_image;

                                     $('#form_item_category').val(iCat);
                                     $('#form_item_subcategory').val(iSub);
                                     $('#form_item_name').val(iName);
                                     $('#form_item_price').val(iPrice);
                                     $('#form_item_Badges').val(iBadge);
                                     $('#form_item_image').val('img_name.png');
                                     $('#form_item_description').val(iDesc);
                                 }
                            }
                        }
                    }
                }
            }
        }
        //Disables activate menu button until changes have been saved
        var node_menu_activate = $('#node_menu_activate');
        node_menu_activate.prop('disabled', true);
        node_menu_activate.css('background-color', 'grey');

        //Activate Menu button
        node_menu_activate.click(function () {
            node_activate_menu();
        });

        //Save Changes Button
        $('#save_changes').click(function () {
            node_menu_activate.prop('disabled', false);
            node_menu_activate.css('background-color', '#125BFF');
        });
    });
    //==============================Account/ dropdown controls==============================
    //Account
    $('#account_btn').click(function () {
        $('.nav-dropdown').toggle();
    });

    //Manage Menus
    $('#nav_manage_menus').click(function () {
        $('#menu_manager_modal').show();
        $('#menu_options').show();
        $('#blanket').show();
        $('.nav-dropdown').hide();
    });

    //Help
    //TODO: Make help page. May Skip for this assignment.

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
</script>
</html>