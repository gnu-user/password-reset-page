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
if (   isset($_POST['first_name']) && isset($_POST['last_name'])   
    && isset($_POST['student_number']))
    
{
    $data = array();

    /* Validate each form entry, add any errors to list */
    if (valid_first_name($_POST['first_name']) && valid_last_name($_POST['last_name']))
    {
        $data['first_name'] = $_POST['first_name'];
        $data['last_name'] = $_POST['last_name'];
    }
    else
    {
        array_push($errors, "<strong>Invalid name!</strong> Please enter a valid first and/or last name!");
    }

    if (valid_student_num($_POST['student_number']))
    {
        /* Salt the student number */
        $data['student_number'] = $_POST['student_number'];
    }
    else
    {
        array_push($errors, "<strong>Invalid student number!</strong> Please enter a valid student number!");
    }


    /* Validate that the name and student id match what is stored in the database */
    if (isset($data['first_name']) && isset($data['last_name']) && isset($data['student_number']) 
        && club_member_exists($mysqli_conn, $data['first_name'], $data['last_name'], $data['student_number'], $AES_KEY))
    {
        /* Get the email */
        $data['email'] = get_member_email($mysqli_conn, $data['first_name'], $data['last_name'], 
                                          $data['student_number'], $AES_KEY);

        if (!isset($data['email']))
        {
            array_push($errors, 
                "<strong>Something Went Horribly Wrong!</strong> Unfortunately we do not have any email " .
                "listed for your account, please contact the " . 
                '<a target="_blank" href="mailto:admin@cs-club.ca">Club Executives</a> ' .
                "to reset your password!");            
        }
    }
    else
    {
        array_push($errors, 
            "<strong>You do Not Appear to be a Club Member!</strong> <p>Unfortunately we could not find any record " .
            "of your name and student id as a registered club member!</p><p>If you have any issues or would like to " .
            "become a club member please contact the " . 
            '<a target="_blank" href="mailto:admin@cs-club.ca">Club Executives</a></p>');
    }


    /* If there are no errors email generate a reset password code and email it to them */
    if (empty($errors))
    {
        /* Get the access account of the club member, and generate a new password reset code */
        $data['access_account'] = get_member_account($mysqli_conn, $data['first_name'], $data['last_name'], 
                                                     $data['student_number'], $AES_KEY);
        $passcode = generate_passcode();
        add_passcode($mysqli_conn, $passcode, $data['access_account']);

        //update_passphrase($mysqli_conn, $data['passphrase']);
        //add_new_member($mysqli_conn, $data, $AES_KEY);

        /* Finally call a script to send the new club member a friendly
         * "Welcome to CS-CLUB" email with information about the club.
         */
        //system( "scripts/welcome-email.sh " . $data['first_name'] . " " . $data['last_name'] . 
        //        " " . $data['email'] . " >/dev/null &",$retval);

        $_SESSION['email_sent'] = "email_sent";
        header('Location: index.php');
    }
    else
    {
        /* Invalid data, redirect to main page */
        $_SESSION['errors'] = $errors;
        header('Location: index.php');
    }
}
else
{
    /* Invalid data, redirect to main page */
    array_push($errors, "<strong>Invalid Information Provided!</strong> The information you " .
        "provided is not valid please enter valid information");
    $_SESSION['errors'] = $errors;
    header('Location: index.php');
}

/* close connection */
$mysqli_conn->close();
exit();
?>
