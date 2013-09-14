<?php
/*
 *  Registration Page
 *
 *  Copyright (C) 2013 Jonathan Gillett, Computer Science Club
 *  All rights reserved.
 *
 *  This program is free software: you can redistribute it and/or modify
 *  it under the terms of the GNU Affero General Public License as
 *  published by the Free Software Foundation, either version 3 of the
 *  License, or (at your option) any later version.
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU Affero General Public License for more details.
 *
 *  You should have received a copy of the GNU Affero General Public License
 *  along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

require_once "inc/auth.php";
require_once "inc/db_interface.php";
require_once "inc/validate.php";

session_start();

$errors = array();
$mysqli_conn = new mysqli("localhost", $db_user, $db_pass, $db_name);

/* check connection */
if (!valid_mysqli_connect($mysqli_conn))
{
    printf("Connection failed: %s\n", mysqli_connect_error());
    exit();
}

/* Validate the POST data, add the user as a new member if everything is valid */
if (isset($_SESSION['code']) && isset($_POST['password']))
    
{
    $data = array();


    /* Verify that the password reset code is valid */    
    if (correct_passcode($mysqli_conn, $_SESSION['code']))
    {
        $data['code'] = $_SESSION['code'];

        /* Validate the password and salt it so that it can be stored securely */
        if (valid_password($_POST['password']))
        {
            /* Salt the password */
            $data['password'] = $_POST['password'];
            salt_sensitive_data($data['password']);
        }
        else
        {
            array_push($errors, "<strong>Invalid password!</strong> Only characters " .
                "<strong>a-z/A-Z/0-9/`~!@#$%^&amp;*()-_=+&lt;&gt;?</strong> may be used!");
        }
    }
    else
    {
        array_push($errors, '<strong>Invalid Reset Code!</strong> You have not provided a valid reset code '.
                   'required to reset your password. If you are having issues resetting your password please ' .
                   'contact the <a target="_blank" href="mailto:admin@cs-club.ca">Club Executives</a>');
    }


    /* If there are no errors update the user's password in the database */
    if (empty($errors))
    {
        /* Update the user's password */
        update_password($mysqli_conn, $data['code'], $data['password'], $AES_KEY);

        /* Mark the passcode as used so it cannot be reused, clear code from session */
        update_passcode($mysqli_conn, $data['code']);
        unset($_SESSION['code']);

        $_SESSION['password_reset'] = "password_reset";
        header('Location: newpassword.php');
    }
    else
    {
        /* Invalid data, redirect to main page */
        $_SESSION['errors'] = $errors;
        header('Location: newpassword.php');
    }
}
else
{
    /* Invalid data, redirect to main page */
    array_push($errors, "<strong>Invalid Information Provided!</strong> The information you " .
        "provided is not valid please enter valid information");
    $_SESSION['errors'] = $errors;
    header('Location: newpassword.php');
}

/* close connection */
$mysqli_conn->close();

?>
