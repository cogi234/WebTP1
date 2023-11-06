<?php
require 'php/sessionManager.php';
require 'models/users.php';
userAccess();

$redirect = 'photosList.php';
if (isset($_POST['redirect'])) {
    $redirect = $_POST['redirect'];
    unset($_POST['redirect']);
}

$user = UsersFile()->get($_SESSION['currentUserId']);
$newUser = new User($_POST);

if ($newUser->Password() == "") {
    $newUser->setPassword($user->Password());
}

UsersFile()->update($newUser);

$user = UsersFile()->get($_SESSION['currentUserId']);
$_SESSION["name"] = $user->Name();
$_SESSION["avatar"] = $user->Avatar();
$_SESSION['Email'] = $user->Email();
$_SESSION['isAdmin'] = $user->IsAdmin();
redirect($redirect);