<?php
/*
 *  Password Reset Page
 *
 *  Copyright (C) 2013 Joseph Heron, Computer Science Club
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
 * Contains a collection of the functions that directly interact with the database
 * to provide a convenient database abstraction layer, in the future support could
 * be added to support other databases. At the moment the implementations are
 * specific to MySQL (5.1 is the version tested) and prepared statements are
 * used for all queries to provide a layer of protection against SQL injection.
 */


/** 
 * A function which salts the sensitive data for the student id and password
 * as a precaution against the security vulnerability of MySQL. MySQL uses ECB for
 * the AES block cipher, which is cryptographically insecure as it is a linear 
 * block-cipher method, read here for more info (look at the linux mascot in example):
 * http://en.wikipedia.org/wiki/Block_cipher_modes_of_operation#Electronic_codebook_.28ECB.29
 * @package dbinterface
 *
 * @param string &$data The data to salt, passed by reference
 */
function salt_sensitive_data(&$data) 
{         
        $salt = sha1(rand());
        $salt = substr($salt, 0, 8);
        $data = $salt . (string) $data;
}


/** 
 * A function which generates a random password reset code
 *
 */
function generate_passcode() 
{         
    return sha1(mt_rand() . mt_rand());
}

/**
 * Check if the club member is in the database.
 *
 * @param mysqli $mysqli The mysqli connection object
 * @param first_name The first name of the club member
 * @param last_name The last name of the club member
 * @param student_id The member's student id
 * @param string $AES_KEY The AES encrypt/decrypt key for the password
 *
 * @return boolean Whether the member is found in the database.
 */
function club_member_exists($mysqli, $first_name, $last_name, $student_id, $AES_KEY)
{
    $result = "";

    /* Verify that the first name exists is not already in use */
    if ($stmt = $mysqli->prepare("SELECT 
                                      AES_DECRYPT(student_id, ?) 
                                  FROM 
                                      ucsc_members 
                                  WHERE 
                                      first_name LIKE ? AND 
                                      last_name LIKE ?"))
    {
        /* bind parameters for markers */
        $stmt->bind_param('sss', $AES_KEY, $first_name, $last_name);

        /* execute query */
        $stmt->execute();

        /* bind result variables */
        $stmt->bind_result($result);

        /* fetch value */
        $stmt->fetch();

        /* close statement */
        $stmt->close();
    }


    /* Verify the password, remove the salt from password stored in DB */
    if (strcmp($student_id, substr($result, 8)) === 0)
    {
        return true;
    }
    else
    {
        return false;
    }
}


/**
 * Gets the email that the student used when they registered as a club member
 *
 * @param mysqli $mysqli The mysqli connection object
 * @param first_name The first name of the club member
 * @param last_name The last name of the club member
 * @param student_id The member's student id
 * @param string $AES_KEY The AES encrypt/decrypt key for the password
 *
 * @return string The email of the club member
 */
function get_member_email($mysqli, $first_name, $last_name, $student_id, $AES_KEY)
{
    $email = "";

    /* Verify that the first name exists is not already in use */
    if ($stmt = $mysqli->prepare("SELECT 
                                      email 
                                  FROM 
                                      ucsc_members 
                                  WHERE 
                                      first_name LIKE ? AND 
                                      last_name LIKE ? AND
                                      SUBSTRING(AES_DECRYPT(student_id, ?), 9) LIKE ?"))
    {
        /* bind parameters for markers */
        $stmt->bind_param('ssss', $first_name, $last_name, $AES_KEY, $student_id);

        /* execute query */
        $stmt->execute();

        /* bind result variables */
        $stmt->bind_result($email);

        /* fetch value */
        $stmt->fetch();

        /* close statement */
        $stmt->close();
    }

    return $email;
}


/**
 * Gets the access accout number of the club member
 *
 * @param mysqli $mysqli The mysqli connection object
 * @param first_name The first name of the club member
 * @param last_name The last name of the club member
 * @param student_id The member's student id
 * @param string $AES_KEY The AES encrypt/decrypt key for the password
 *
 * @return string The access account number of the club member
 */
function get_member_account($mysqli, $first_name, $last_name, $student_id, $AES_KEY)
{
    $access_account = "";

    /* Verify that the first name exists is not already in use */
    if ($stmt = $mysqli->prepare("SELECT 
                                      access_account 
                                  FROM 
                                      ucsc_members 
                                  WHERE 
                                      first_name LIKE ? AND 
                                      last_name LIKE ? AND
                                      SUBSTRING(AES_DECRYPT(student_id, ?), 9) LIKE ?"))
    {
        /* bind parameters for markers */
        $stmt->bind_param('ssss', $first_name, $last_name, $AES_KEY, $student_id);

        /* execute query */
        $stmt->execute();

        /* bind result variables */
        $stmt->bind_result($access_account);

        /* fetch value */
        $stmt->fetch();

        /* close statement */
        $stmt->close();
    }

    return $access_account;
}


/**
 * Gets the username of the account that is having the password reset
 *
 * @param mysqli $mysqli The mysqli connection object
 * @param string $passcode The password reset code
 *
 * @return string The username number of the club member
 */
function get_username($mysqli, $passcode)
{
    $username = "";

    /* Verify that the first name exists is not already in use */
    if ($stmt = $mysqli->prepare("SELECT 
                                      m.username 
                                  FROM 
                                      passcodes AS p INNER JOIN 
                                      ucsc_members AS m 
                                  ON 
                                      p.access_account = m.access_account
                                  WHERE 
                                      passcode LIKE ?"))
    {
        /* bind parameters for markers */
        $stmt->bind_param('s', $passcode);

        /* execute query */
        $stmt->execute();

        /* bind result variables */
        $stmt->bind_result($username);

        /* fetch value */
        $stmt->fetch();

        /* close statement */
        $stmt->close();
    }

    return $username;
}



/** 
 * A function which adds the generated password reset code to the database.
 * @package dbinterface
 *
 * @param mysqli $mysqli The mysqli connection object
 * @param string $passcode The password reset code
 *
 * @return boolean True if the passphrase is valid and has not already been used
 */
function add_passcode($mysqli, $passcode, $access_account)
{
    /* Add the passcode to the database */
    if ($stmt = $mysqli->prepare("INSERT INTO 
                                      passcodes
                                  VALUES
                                      (?, CURDATE(), NULL, ?)"))
    {
        /* bind parameters for markers */
        $stmt->bind_param('ss', $passcode, $access_account);

        /* execute query */
        $stmt->execute();

        /* fetch value */
        $stmt->fetch();

        /* close statement */
        $stmt->close();
    }
}


/** 
 * A function which verifies that the password reset code has not already been used and is
 * valid.
 * @package dbinterface
 *
 * @param mysqli $mysqli The mysqli connection object
 * @param string $passcode The password reset code
 *
 * @return boolean True if the passphrase is valid and has not already been used
 */
function correct_passcode($mysqli, $passcode)
{
    $match = "";

    /* Verify that the passphrase is unique and exists */
    if ($stmt = $mysqli->prepare("SELECT 
                                      passcode 
                                  FROM 
                                      passcodes 
                                  WHERE 
                                      passcode LIKE ? AND 
                                      date_used IS NULL"))
    {
        /* bind parameters for markers */
        $stmt->bind_param('s', $passcode);

        /* execute query */
        $stmt->execute();

        /* bind result variables */
        $stmt->bind_result($match);

        /* fetch value */
        $stmt->fetch();

        /* close statement */
        $stmt->close();
    }   

    if (strcmp($passcode, $match) !== 0)
    {
        return false;
    }
    else
    {
        return true;
    }
}


/**
 * A function which updates the passphrase provided by the user to have the date_used field filled so 
 * the passphrase cannot be used to create another account.
 * @package dbinterface
 *
 * @param mysqli $mysqli The mysqli connection object
 * @param string $passphrase The passphrase to update as used
 */
function update_passcode($mysqli, $passcode)
{
    /* Set the passphrase date_used as current date */
    if ($stmt = $mysqli->prepare("UPDATE 
                                      passcodes 
                                  SET 
                                      date_used = CURDATE() 
                                  WHERE 
                                      passcode LIKE ?"))
    {
        /* bind parameters for markers */
        $stmt->bind_param('s', $passcode);

        /* execute query */
        $stmt->execute();

        /* close statement */
        $stmt->close();
    }
}
?>
