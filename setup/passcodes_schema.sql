
/*
 * Create the passcodes table used for resetting club account passwords
 */
CREATE TABLE passcodes
(
    passcode       VARCHAR(64), 
    date_created   DATE,
    date_used      DATE DEFAULT NULL,
    access_account SMALLINT UNSIGNED NOT NULL
);
