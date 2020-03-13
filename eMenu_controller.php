<?php
if (empty($_POST['page'])) {
    $error_message_signin = "";
    include('signin_page.php');
    exit();
}

require('eMenu_model.php');

session_start();

if ($_POST['page'] == 'SigninPage') {
    $command = $_POST['command'];
    switch ($command) {
        case 'SignIn':
            if (!is_valid($_POST['accountid'], $_POST['password'])) {
                $error_msg_signin = '* Wrong account ID or password *';
                include('signin_page.php');
            } else {
                $_SESSION['SignIn'] = 'Yes';
                $_SESSION['accountid'] = $_POST['accountid'];
                include('main_page.php');
            }
            break;

        case 'ForgotPassword':
            //TODO Make this work. Can omit for this assignment. Low priority.
            break;
    }
}

else if ($_POST['page'] == 'MainPage') {
    if (!isset($_SESSION['SignIn'])) {
        include('signin_page.php');
        exit();
    }

    $command = $_POST['command'];
    switch ($command) {
        //uses get_menu_list from model to populate menu manager list
        case 'GetMenus':
            $result = get_menu_list($_SESSION['accountid']);
            if (count($result) == 0)
                echo "You have no menus. Click the button above create a new one.";
            else {
                $str = "<table class='table' id='menu_table'>";

                $str .= "<tr>";
                $str .= "<th>" . 'Menu Name' . "</th>";
                $str .= "<th>" . 'Last Edited' . "</th>";
                $str .= "</tr>";

                for ($i = 0; $i < count($result); $i++) {

                    $str .= "<tr class='menu-list-row'>";
                    foreach ($result[$i] as $k)
                        $str .= "<td class='d'>" . $k . "</td>";
                    $str .= "</tr>";
                }
                $str .= "</tr>";
                $str .= "</table>";
                echo $str;
            }
            break;

        //Retrieves Restaurant name using get_name from model.
        case 'GetName':
            $result = get_name($_SESSION['accountid']);
            echo $result;
            break;

        case 'GetMenuData':
            $result = get_menu_data($_POST['data'], $_SESSION['accountid']);
            echo $result;
            break;

        case 'CreateNewMenu':
            if (is_new($_POST['data'], $_SESSION['accountid'])) {
                $result = create_new_menu($_POST['data'], $_SESSION['accountid']);

                if ($result)
                    echo 'Menu Created';
                else
                    echo 'Error: Unable to create menu.';
            }else
                echo 'Error: Menu name already exists.';
            break;

        case 'DuplicateMenu':
            //TODO: Check if this works after fixing bug in create_new_menu.
            if (!is_new($_POST['data'], $_SESSION['accountid'])) {
                $result = duplicate_menu($_POST['menu'], $_POST['newName'], $_SESSION['accountid']);

                if ($result == true)
                    echo 'Menu Duplicated';
                else
                    echo 'Error: Unable to duplicate menu.';
            }else
                echo 'Error: Menu name already exists.';
            break;

        case 'DeleteMenu':
            $result = delete_menu($_POST['data'], $_SESSION['accountid']);

            if ($result)
                echo 'Menu Deleted';
            else
                echo 'Error: Unable to delete menu.';
            break;

        case"ActivateMenu":
            $result = activate_menu($_POST['data'], $_SESSION['accountid']);

            if ($result)
                echo 'Menu Activated';
            else
                echo 'Error: Unable to activate menu.';
            break;

        case 'SaveChanges':
            //TODO: Save all changes to DB.
            break;

        case 'SignOut':
            session_unset();
            session_destroy();
            include('signin_page.php');
            exit();
    }
}

else {
    exit();
}
?>
