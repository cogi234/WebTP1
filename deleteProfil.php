<?php
include 'php/sessionManager.php';
include 'models/users.php';
include 'models/photos.php';

userAccess();

$redirect = 'loginForm.php';
if (isset($_POST['redirect'])) {
    $redirect = $_POST['redirect'];
}

$idToDelete = $_SESSION['currentUserId'];
if (isset($_POST['Id'])) {
    $idToDelete = (int)$_POST['Id'];
}

do {
    $photos = PhotosFile()->toArray();
    $oneDeleted = false;
    foreach ($photos as $photo) {
        if ($photo->OwnerId() == $idToDelete) {
            $oneDeleted = true;
            PhotosFile()->remove($photo->Id());
            break;
        }
    }
} while ($oneDeleted);

UsersFile()->remove($idToDelete);
redirect($redirect);