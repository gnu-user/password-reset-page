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

/**
 * Contains a collection of methods that are used to validate POST data, ensure
 * that all POST data has been validated before processing any of the data.
 */


/**
 * Validate the mysql connection is successful.
 * @package validate
 *
 * @param mysqli $mysqli_connection The mysqli connection object
 */
function valid_mysqli_connect($mysqli_connection)
{
    if ($mysqli_connection->connect_errno)
    {
        return FALSE;
    }
    return TRUE;
}


/**
 * Validate the first name
 * @package validate
 *
 * @param string $first_name the first_name post data
 * @return boolean TRUE if the input is valid
 */
function valid_first_name($first_name)
{
    if (preg_match('/^(([A-Za-z]+)|\s{1}[A-Za-z]+)+$/', $first_name) && strlen($first_name) < 32)
    {
        return TRUE;
    }
    return FALSE;
}

/** 
 * Validate the last name
 * @package validate
 *
 * @param string $last_name the last_name post data
 * @return boolean TRUE if the input is valid
 */
function valid_last_name($last_name)
{
    if (preg_match('/^(([A-Za-z]+)|\s{1}[A-Za-z]+)+$/', $last_name) && strlen($last_name) < 32)
    {
        return TRUE;
    }
    return FALSE;
}

/**
 * Validate the student number
 * @package validate
 *
 * @param string $student_number the student_number post data
 * @return boolean TRUE if the input is valid
 */
function valid_student_num($student_number)
{
    if (preg_match('/^\d{9}$/', $student_number))
    {
        return TRUE;
    }
    return FALSE;
}

/**
 * Validate the password
 * @package validate
 *
 * @param string $password the password post data
 * @return boolean TRUE if the input for the password is valid
 */
function valid_password($password)
{
    if (preg_match('/^[a-zA-Z0-9\`\~\!\@\#\$\%\^\&\*\(\)\-\_\=\+\|\<\>\?]{6,31}$/', $password))
    {
        return TRUE;
    }
    return FALSE;
}
?>
