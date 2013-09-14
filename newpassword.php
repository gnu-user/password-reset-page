<?php
/*
 *  Password Reset Page
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


/* Get the password reset code for the user */
if (isset($_GET['code']))
{
    $_SESSION['code'] = $_GET['code'];

    /* If the code is valid then also display the username of the account */
    if (correct_passcode($mysqli_conn, $_SESSION['code']))
    {
        $_SESSION['username'] = get_username($mysqli_conn, $_SESSION['code']);
    }
    else
    {
        array_push($errors, '<strong>Invalid Reset Code!</strong> You have not provided a valid reset code '.
                   'required to reset your password. If you are having issues resetting your password please ' .
                   'contact the <a target="_blank" href="mailto:admin@cs-club.ca">Club Executives</a>');
    }
}
else
{
    /* Don't display this error on page refresh after setting new password */
    if (!isset($_SESSION['password_reset']))
    {
        array_push($errors, '<strong>No Reset Code Provided!</strong> You have not provided a reset code '.
                   'required to reset your password. If you are having issues resetting your password please ' .
                   'contact the <a target="_blank" href="mailto:admin@cs-club.ca">Club Executives</a>');
    }
}


/* Add a list of errors to be displayed if any occurred */
if (!empty($errors))
{
    $_SESSION['errors'] = $errors;
}


/* Display the template for password reset */
include 'templates/header.php';

include 'templates/newpassword_form.php';

/* Include the footer */
include 'templates/footer.php';

/* close connection */
$mysqli_conn->close();

/* Unset errors and password reset status */
unset($_SESSION['errors']);
unset($_SESSION['password_reset']);
?>
