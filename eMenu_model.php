<?php

$conn = mysqli_connect('localhost', 'sholdenw20', 'Keenen1997', 'C354_sholdenw20');

//Determines if login credentials are valid.
function is_valid($u, $p){
    global $conn;

    $sql = "select * from Restaurant where username = '$u' and password = '$p'";
    $result = mysqli_query($conn, $sql);
    if (mysqli_num_rows($result) > 0)
        return true;
    else
        return false;
}

//Determines if menu exists in DB to prevent duplicates.
function is_new($name, $restaurant)
{
    global $conn;
    $id = get_rest_id($restaurant);

    $sql = "select * from Menu where menu_name = '$name' and rest_id = '$id'";
    $result = mysqli_query($conn, $sql);
    if (mysqli_num_rows($result) > 0)
        return false;
    else
        return true;
}

//Retreives restaurant id
function get_rest_id($u)
{
    global $conn;

    $sql = "select * from Restaurant where (username = '$u')";
    $result = mysqli_query($conn, $sql);
    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        return $row['id'];
    }
    else
        return 'ID not found.';
}

//Retrieves menu name and date associated with restaurant and stores them in array.
function get_menu_list($restaurant){
    global $conn;

    $id = get_rest_id($restaurant);
    if ($id < 0) {
        return array();
    } else {
        $sql = "select menu_name, date from Menu where rest_id = '$id'";
        $result = mysqli_query($conn, $sql);
        $data = array();
        $i = 0;

        if (mysqli_num_rows($result) > 0) {
            while($row = mysqli_fetch_assoc($result)) {
                $data[$i] = $row;
                $i++;
            }
        }

        echo mysqli_error($conn);

        return $data;
    }
}

//Retrieves account name from database to display in header.
function get_name($u){
    global $conn;

    $sql = "select name from Restaurant where username = '$u'";
    $result = mysqli_query($conn, $sql);
    if (mysqli_num_rows($result) > 0){
        $row = mysqli_fetch_assoc($result);
        return $row['name'];
    }
    else
        return 'Account name';
}

//Creates new blank menu and adds it to database. New menu contains nothing in data column.
//TODO: Fix bug where clicking new_submit button signs user out.
function create_new_menu($restaurant, $name){
    global $conn;

    $id = get_rest_id($restaurant);
    $curr_date = date("mm "/" dd "/" y");
    if ($id == "") {
        return false;
    } else {
        $sql = "insert into Menu (menu_name, rest_id, date)
                values ('$name', $id, $curr_date)  ";

        mysqli_query($conn, $sql);
        echo mysqli_error($conn);

        return true;
    }
}

//Activates menu. Updates published_id in Restaurant DB to selected menu.
function activate_menu($name, $restaurant){
    global $conn;

    $sql1 = "select id from Menu where menu_name = '$name'";

    $result1 = mysqli_query($conn, $sql1);

    if ($row = mysqli_fetch_assoc($result1)) {
        $menu_id = $row['id'];

        $sql = "update Restaurant 
            set published_id = $menu_id
            where username='$restaurant'";

        mysqli_query($conn, $sql);

        return true;
    }else
        return false;
}

//Deletes menu from DB
function delete_menu($name, $restaurant){
    global $conn;

    $sql = "select id from Menu where menu_name = '$name'";
    $sql1 = "select id from Restaurant where username = '$restaurant'";

    $result = mysqli_query($conn, $sql);
    $result1 = mysqli_query($conn, $sql1);

    if ($row = mysqli_fetch_assoc($result)) {
        if ($row1 = mysqli_fetch_assoc($result1)){
            $menu_id = $row['id'];
            $rest_id = $row1['id'];

            $sql = "delete from Menu where (id = '$menu_id' and rest_id = '$rest_id')";

            mysqli_query($conn, $sql);
        }
        return true;
    }else
        return false;
}

//Duplicates menu
function duplicate_menu($menu, $name, $restaurant){
    global $conn;

    create_new_menu($restaurant, $name);
    $id = get_rest_id($restaurant);

    $sql = "select * from Menu where menu_name = '$menu' and rest_id = '$id'";
    $result = mysqli_query($conn, $sql);

    if ($row = mysqli_fetch_assoc($result)) {
        $menu_data = $row['data'];

        $sql1 = "update Menu
                set data = $menu_data
                where menu_name = '$name' and rest_id = '$id'";

        mysqli_query($conn, $sql1);

        return true;
    }else
        return false;
}

//Retrieves menu data from DB and sends to controller.
function get_menu_data($name, $restaurant){
    global $conn;

    $id = get_rest_id($restaurant);

    $sql = "select data from Menu where menu_name = '$name' and rest_id = '$id'";
    $result = mysqli_query($conn, $sql);

    if ($row = mysqli_fetch_assoc($result)) {
        $data = $row['data'];

        return $data;
    }else
        return 'Menu Data Not Found.';
}