#!/bin/bash

# ======================================================================
#
# A simple script to send a password reset email.
# takes the following arguments <first name> <last name> <passcode> <email>
# 
# example: ./welcome-email.sh John Doe ff9dc424611b john.doe@gmail.com
#
# ======================================================================

FIRST_NAME="${1}"
LAST_NAME="${2}"
PASSCODE="${3}"
MEMBER_EMAIL="${4}"

# Club email account and message details
CLUB_EMAIL=''
EMAIL_PASS=''
EMAIL_SUBJECT='Computer Science Club Password Reset'
SMTP_SERVER='smtp.gmail.com'
SMTP_PORT='465'

# Password reset email message
EMAIL_MESSAGE="<html><body>
<p>${FIRST_NAME} ${LAST_NAME},</p>"'<p>The following is a URL to reset the
password for your club account that you created when you registered as a member
of the Computer Science Club.</p>

<p><a href="https://cs-club.ca/reset/newpassword.php?code='${PASSCODE}'">Club Account Password Reset</a><br /></p>

<p>After you have reset your password you should immediately have access to any of the Computer Science Club services
requiring login authentication.</p>

<p>If you have any questions, please feel free to contact the <a target="_blank" href="mailto:admin@cs-club.ca">Club Executives</a></p>

<p>Cheers,<br/>Computer Science Club</p>

</body>
</html>'


# Send the password reset email to the club member from the official 
# CS Club email account (uoit.csc@gmail.com)

smtp-cli --server="${SMTP_SERVER}" --port="${SMTP_PORT}" --ssl --user="${CLUB_EMAIL}" \
--pass="${EMAIL_PASS}" --from="${CLUB_EMAIL}" --to="${MEMBER_EMAIL}" --subject="${EMAIL_SUBJECT}" \
--body-html="${EMAIL_MESSAGE}"

exit $?
