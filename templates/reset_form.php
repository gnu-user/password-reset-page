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

/*
 * The registration form, the user uses it to register as an official member
 * of the computer science club.
 *
 * DEPENDENCIES
 * ------------
 * 
 * This template uses an array of error messages, or a new_member session variable
 * to display notifications.
 *
 *  - $_SESSION['errors']
 *  - $_SESSION['email_sent'] (if password successfully registered)
 *
 */
?>
<section id="register">
    <div class="page-header">
        <h1>Password Reset</h1>
    </div>
    <div class="row">
        <div class="span8">
            <!--  Display an error if they entered invalid credentials -->
            <?php
                if (isset($_SESSION['errors']))
                {
                    echo '<div class="alert alert-error">
                            <button type="button" class="close" data-dismiss="alert">&times;</button>';

                    foreach ($_SESSION['errors'] as $error)
                    {
                        echo '<p>' . $error . '</p>';
                    }
                    echo '</div>';
                }
                else if (isset($_SESSION['email_sent']))
                {
                    echo '<div id="active" class="alert alert-success">
                            <button type="button" class="close" data-dismiss="alert">&times;</button>
                            <p>
                                <strong>An Email With Reset Instructions Has Been Sent!</strong> An email has been
                                sent to the email address you used to register as a member of the club with
                                instructions on resetting your password.
                            </p>
                            <p>
                                If you did not recieve an email with reset instructions, or for some other reason 
                                you cannot reset your password please contact the 
                                <a target="_blank" href="mailto:admin@cs-club.ca">Club Executives</a>
                            </p>
                          </div>';
                }
                else
                {
                    echo '<div id="activeinfo" class="alert alert-info">
                            <button type="button" class="close" data-dismiss="alert">&times;</button>
                            <p>
                                <strong>Enter the Following to Reset Your Password</strong> an email
                                will be sent to the email address you registered with containing
                                instructions on how to reset your password.
                            </p>
                            <p>
                                If you have any issues resetting your password please contact the 
                                <a target="_blank" href="mailto:admin@cs-club.ca">Club Executives</a>
                            </p>
                          </div>';
                }
            ?>
            <form class="well form-horizontal" action="reset.php" method="post" accept-charset="UTF-8">
                <fieldset>
                    <!--  First & Last Name -->
                    <div class="control-group">
                        <label for="first_name" class="control-label">First Name:</label>                
                        <div class="controls">
                            <input id="first_name" name="first_name" required type="text" maxlength="31" pattern="^(([A-Za-z]+)|\s{1}[A-Za-z]+)+$" placeholder="First name..."/>            
                        </div>
                    </div>
                    <div class="control-group">
                        <label for="last_name" class="control-label">Last Name:</label>             
                        <div class="controls">
                            <input id="last_name" name="last_name" required type="text" maxlength="31" pattern="^(([A-Za-z]+)|\s{1}[A-Za-z]+)+$" placeholder="Last name..."/>                       
                        </div>
                    </div>
                    <!-- Enter your student number... -->
                    <div class="control-group">
                        <label for="student_number" class="control-label">Student Number:</label>               
                        <div class="controls">
                            <input id="student_number" name="student_number" required type="text" maxlength="9" pattern="^\d{9}$" placeholder="100123456..."/>              
                        </div>
                    </div>
                    <!-- Reset Password -->
                    <div class="control-group">
                        <div class="controls">
                            <button type="submit" id="reset" name="reset" class="btn btn-inverse">Reset</button>
                        </div>
                    </div>
                </fieldset>
            </form>
        </div>
    </div>
</section>
