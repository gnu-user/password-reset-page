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
 * This template uses an array of error messages and other session variable
 * to display notifications.
 *
 *  - $_SESSION['errors']
 *  - $_SESSION['password_reset'] (if password successfully reset)
 *  - $_SESSION['username'] (if the code is correct)
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
                else if (isset($_SESSION['password_reset']))
                {
                    echo '<div id="active" class="alert alert-success">
                            <button type="button" class="close" data-dismiss="alert">&times;</button>
                            <p>
                                <strong>Your Password has Been Reset!</strong> Your password has successfully
                                been reset, you can now login and use any of the Computer Science Club services.
                            </p>
                            <p>
                                If you have any questions or comments please contact the 
                                <a target="_blank" href="mailto:admin@cs-club.ca">Club Executives</a>
                            </p>
                          </div>';
                }
                else
                {
                    echo '<div id="activeinfo" class="alert alert-info">
                            <button type="button" class="close" data-dismiss="alert">&times;</button>
                            <p>
                                <strong>Enter a New Secure Password!</strong> Ensure that you enter a
                                secure password that is easy for you to remember but hard to break.
                            </p>
                            <p>
                                If you have any issues resetting your password please contact the 
                                <a target="_blank" href="mailto:admin@cs-club.ca">Club Executives</a>
                            </p>
                          </div>';
                }
            ?>
            <form class="well form-horizontal" action="update.php" method="post" accept-charset="UTF-8">
                <fieldset>
                    <!-- Username -->
                    <div class="control-group">
                        <label for="username" class="control-label">Current Username:</label>               
                        <div class="controls">
                            <?php
                                if (isset($_SESSION['username']))
                                {
                                    echo '<span id="username" class="input-large uneditable-input">' . $_SESSION['username'] . '</span>';
                                }
                                else
                                {
                                    echo '<span id="username" class="input-large uneditable-input">Username...</span>';
                                }
                          ?>         
                        </div>
                    </div>
                    <!-- Password -->
                    <div class="control-group">
                        <label for="password" class="control-label">New Password:</label>               
                        <div class="controls">
                            <input id="password" name="password" type="password" maxlength="31" pattern="^[a-zA-Z0-9\`\~\!\@\#\$\%\^\&amp;\*\(\)\-\_\=\+\|\&lt;\&gt;\?]{6,31}$" placeholder="Enter Your New Password..."/>              
                        </div>
                    </div>
                    <!-- Set the new password -->
                    <div class="control-group">
                        <div class="controls">
                            <button type="submit" id="submit" name="submit" class="btn btn-inverse">Submit</button>
                        </div>
                    </div>
                </fieldset>
            </form>
        </div>
    </div>
</section>
