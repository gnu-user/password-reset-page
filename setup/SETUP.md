SETTING UP THE PASSWORD RESET WEBSITE
============================================


Database Setup
----------------------------------------

Create the table used for storing the password reset codes.

    ```
    USE ucsc_accounts;

    CREATE TABLE passcodes
    (
        passcode       VARCHAR(64), 
        date_created   DATE,
        date_used      DATE DEFAULT NULL,
        access_account SMALLINT UNSIGNED NOT NULL
    );
    ```


Deploying the PHP website
--------------------------

1.	Start by cloning the latest stable tag release or download from github onto
    web server directory on the club server, the project can be found on Github 
    under the [CS-CLUB account](https://github.com/CS-CLUB/password-reset-page.git)
    This is the easiest method since you can pull (update) if there are any changes.

    ```
    git clone https://github.com/CS-CLUB/password-reset-page.git reset 
    ```

2.	Edit the authorization file in inc/auth.php and set the variables, you may 
    need to refer to some of the administration files or variables defined in 
    the election registration page for the db password and AES_KEY. At present
    the following are the configuration options:

    ```php
		 /* Database access */
		 $db_user = 'rms';
		 $db_pass = '...';
		 $db_name = 'ucsc_accounts';

		 /* AES ENCRYPT/DECRYPT KEY */
		 $AES_KEY = '...';
    ```

3.	Next, set the following permissions to various files that should never be made
    accessible (even READ ONLY) to the public as they contain confidential data
	  such as passwords

	  a.	For the inc/auth.php file

    ```bash
    chown root.www-data inc/auth.php
    chmod 640 inc/auth.php
    ```

	  b.	For the password reset email script set the following permissions.

    ```bash
    chown root.www-data scripts/password-reset-email.sh
    chmod 750 scripts/password-reset-email.sh
    ```

4.	The last step is to configure the email account and password in the reset 
    password email script (scripts/password-reset-email.sh). You may need to 
    refer to some of the administration files for the club email account and 
    password information. You may need to configure all of the following parameters
    if you are having difficulty the Gmail SMTP settings may need to be updated.

    ```bash
    # Club email account and message details
    CLUB_EMAIL='uoit.csc@gmail.com'
    EMAIL_PASS='...'
    SMTP_SERVER='smtp.gmail.com'
    SMTP_PORT='465'
    ```

