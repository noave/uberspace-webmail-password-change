<h1>Change Mailbox Password</h1>

<form method="POST">
    <input type="text" name="mailaddr" value="<?php echo isset($_POST['mailaddr']) ? htmlspecialchars($_POST['mailaddr']) : '' ?>"> mailaddress<br>
    <input type="password" name="password_old"> current password<br>
    <input type="password" name="password_new1"> new password<br>
    <input type="password" name="password_new2"> new password (repeat)<br>
    <br>
    <input type="submit" value="change password">
</form>


<?php

if (!isset($_POST['mailaddr'])) {
    exit;
}

if ($_POST['password_new1'] != $_POST['password_new2']) {
    echo 'the new passwords are not identical';
    exit;
}

if(preg_match("/'/", $_POST['password_new1'])) {
    echo 'not allowed chars in new password';
    exit;
}

$check_password = imap_open('{localhost:993/imap/ssl/novalidate-cert/notls/norsh}INBOX', $_POST['mailaddr'], $_POST['password_old'], OP_READONLY, 1);

if ($check_password == true) {
    $new_password = escapeshellarg($_POST['password_new1']);
    $mailuser = implode('@', explode('@', $_POST['mailaddr'], -1));
    $output = shell_exec('uberspace mail user password \'' . $mailuser . '\' -p \'' . $_POST['password_new1'] . '\' 2>&1;');
    echo $output;
}
else {
    echo 'current password incorrect';
}

