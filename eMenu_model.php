<?php

$conn = mysqli_connect('localhost', 'sholdenw20', 'Keenen1997', 'C354_sholdenw20');

/**==========================================================================================
 * Determines if login credentials are valid.
 * @param $u : Username
 * @param $p : Password
 * @return bool : true if credentials are valid or false if they are not.
 * ==========================================================================================
 */
function is_valid($u, $p){
    global $conn;

    $sql = "select * from Restaurant where username = '$u' and password = '$p'";
    $result = mysqli_query($conn, $sql);
    if (mysqli_num_rows($result) > 0)
        return true;
    else
        return false;
}

/**==========================================================================================
 * Determines if menu exists in DB to prevent duplicates.
 * @param $name : The name of the new menu trying to be created.
 * @param $restaurant : The name of the restaurant menu is being created for.
 * @return bool : True if menu name and restaurant combination does not exist in DB or false if it does.
 * ==========================================================================================
 */
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

/**==========================================================================================
 * Retrieves restaurant id
 * @param $u : Username of the currently logged in restaurant
 * @return string : id of restaurant.
 * ==========================================================================================
 */
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

/**==========================================================================================
 * Retrieves menu name and date associated with restaurant and stores them in array.
 * @param $restaurant : The name of the restaurant menu is being created for.
 * @return array : associative array of all menus belonging to restaurant.
 * ==========================================================================================
 */
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
/**==========================================================================================
 * Retrieves account name from database to display in header.
 * @param $u : Username of the currently logged in restaurant
 * @return string : Name of the restaurant or default string if it can't be found
 * ==========================================================================================
 */
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

/**==========================================================================================
 * Creates new blank menu and adds it to database. New menu contains nothing in data column.
 * @param $name : The name of the new menu that will be created.
 * @param $restaurant : The name of the restaurant menu is being created for.
 * @return bool : True if creation is successful or false if it is not.
 * ==========================================================================================
 */
function create_new_menu($name, $restaurant){
    global $conn;

    $id = get_rest_id($restaurant);
    if ($id == "") {
        return false;
    } else {
        $curr_date = date('m\/d\/Y');
        $sql = "insert into Menu (menu_name, data, rest_id, date)
                values ('$name', '{}', '$id', '$curr_date')";

        mysqli_query($conn, $sql);

        return true;
    }
}

/**==========================================================================================
 * Activates menu. Updates published_id in Restaurant DB to selected menu.
 * @param $name : The name of the menu that will be activated.
 * @param $restaurant : The name of the restaurant menu is being activated for.
 * @return bool : True if activation is successful or false if it is not.
 * ==========================================================================================
 */
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

/**==========================================================================================
 * Deletes menu from DB
 * @param $name : The name of the menu that will be deleted.
 * @param $restaurant : The name of the restaurant menu is being deleted from.
 * @return bool : True if deletion is successful or false if it is not.
 * ==========================================================================================
 */
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

/**==========================================================================================
 * Duplicates Menu
 * @param $menu : The name of the menu being duplicated.
 * @param $name : The name of the new menu that will be created.
 * @param $restaurant : The name of the restaurant menu is being duplicated for.
 * @return bool : True if duplication is successful or false if it is not.
 * ==========================================================================================
 */
//Duplicates menu
//TODO: Fix bug where new menu data is blank
function duplicate_menu($menu, $name, $restaurant){
    global $conn;

    create_new_menu($name, $restaurant);
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

/**==========================================================================================
 * Retrieves menu data from DB and sends to controller.
 * @param $name : The name of the menu data is being retrieved from.
 * @param $restaurant : The name of the restaurant menu data is being retrieved from.
 * @return string : Contents of data column or default value if not found
 * ==========================================================================================
 */
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

/**==========================================================================================
 * Sends updated json string to Menu data column in DB.
 * @param $name : The name of the menu data is being saved to.
 * @param $data : The json data to be sent to DB.
 * @param $restaurant : The name of the restaurant the menu belongs to.
 * @return bool : True if save is successful or false if it is not.
 * ==========================================================================================
 */
function save_changes($name, $data, $restaurant){
    global $conn;

    $id = get_rest_id($restaurant);

    if ($id == "") {
        return false;
    } else {
        $sql = "update Menu
            set data = '$data'
            where menu_name = '$name' and rest_id = '$id'";

        mysqli_query($conn, $sql);

        return true;
    }
}
