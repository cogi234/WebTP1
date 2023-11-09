<?php
include 'php/sessionManager.php';
include 'models/users.php';
$viewTitle = "Retrait de compte";

userAccess();

$redirect = 'editProfilForm.php';
$redirectElement = "";
if (isset($_POST['redirect'])) {
    $redirect = $_POST['redirect'];
    $redirectElement = <<<HTML
    <input type="hidden" name="redirect" value="$redirect">
    HTML;
}

$idToDelete = $_SESSION['currentUserId'];
if (isset($_POST['Id'])) {
    $idToDelete = (int)$_POST['Id'];
}

$user = UsersFile()->get($idToDelete);
$username = $user->Name();

$viewContent = <<<HTML
    <div class="content loginForm">
        <br>
       <h3> Voulez-vous vraiment effacer le compte $username? </h3>
        <form class="form" method="post" action="deleteProfil.php">
            <input type="hidden" name="Id" value="$idToDelete">
            $redirectElement
            <button type="submit" class="form-control btn-danger">Effacer mon compte</button>
            <br>
            <a href="$redirect" class="form-control btn-secondary">Annuler</a>
        </form>
    </div>
    HTML;
$viewScript = <<<HTML
        <script defer>
            $("#addPhotoCmd").hide();
        </script>
    HTML;
include "views/master.php";