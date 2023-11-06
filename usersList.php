<?php
include 'php/sessionManager.php';
include_once "models/users.php";

userAccess();
adminAccess();

$viewTitle = "Liste des usagers";
$list = UsersFile()->toArray();
$viewContent = "";

foreach ($list as $User) {
    $id = strval($User->id());
    if ($id == $_SESSION['currentUserId']) {
        continue;
    }
    $name = $User->Name();
    $email = $User->Email();
    $password = $User->Password();
    $avatar = $User->Avatar();
    $type = $User->Type();
    $blocked = $User->Blocked();
    $isAdmin = $User->isAdmin();
    $isBlocked = $User->isBlocked();

    if ($isAdmin) {
        $adminHTML = <<<HTML
        <form method="post" action="updateProfil.php">
            <input type="hidden" name="Name" value="$name">
            <input type="hidden" name="Email" value="$email">
            <input type="hidden" name="Password" value="$password">
            <input type="hidden" name="Avatar" value="$avatar">
            <input type="hidden" name="Type" value="0">
            <input type="hidden" name="Blocked" value="$blocked">
            <input type="hidden" name="redirect" value="usersList.php">
            <button type="submit">
                <i class="cmdIcon fa fa-user-gear"></i>
            </button>
        </form>
        HTML;
    } else {
        $adminHTML = <<<HTML
        <form method="post" action="updateProfil.php">
            <input type="hidden" name="Name" value="$name">
            <input type="hidden" name="Email" value="$email">
            <input type="hidden" name="Password" value="$password">
            <input type="hidden" name="Avatar" value="$avatar">
            <input type="hidden" name="Type" value="1">
            <input type="hidden" name="Blocked" value="$blocked">
            <input type="hidden" name="redirect" value="usersList.php">
            <button type="submit">
                <i class="cmdIcon fa fa-user"></i>
            </button>
        </form>
        HTML;
    }

    if ($isBlocked) {
        $blockedHTML = <<<HTML
        <form method="post" action="updateProfil.php">
            <input type="hidden" name="Name" value="$name">
            <input type="hidden" name="Email" value="$email">
            <input type="hidden" name="Password" value="$password">
            <input type="hidden" name="Avatar" value="$avatar">
            <input type="hidden" name="Type" value="$type">
            <input type="hidden" name="Blocked" value="0">
            <input type="hidden" name="redirect" value="usersList.php">
            <button type="submit">
                <i class="cmdIcon fa fa-ban redCmd"></i>
            </button>
        </form>
        HTML;
    } else {
        $blockedHTML = <<<HTML
        <form method="post" action="updateProfil.php">
            <input type="hidden" name="Name" value="$name">
            <input type="hidden" name="Email" value="$email">
            <input type="hidden" name="Password" value="$password">
            <input type="hidden" name="Avatar" value="$avatar">
            <input type="hidden" name="Type" value="$type">
            <input type="hidden" name="Blocked" value="1">
            <input type="hidden" name="redirect" value="usersList.php">
            <button type="submit">
                <i class="cmdIcon fa-regular fa-circle greenCmd"></i>
            </button>
        </form>
        HTML;
    }

    $deleteHTML = <<<HTML
    <a href="editProfilForm.php" class="">
        <i class="cmdIcon fa fa-user-slash goldenrodCmd"></i>
    </a>
    HTML;


    $UserHTML = <<<HTML
    <div class="UserRow" User_id="$id">
        <div class="UserContainer noselect">
            <div class="UserLayout">
                <div class="UserAvatar" style="background-image:url('$avatar')"></div>
                <div class="UserInfo">
                    <span class="UserName">$name</span>
                    <a href="mailto:$email" class="UserEmail" target="_blank" >$email</a>
                </div>
            </div>
            <div class="UserCommandPanel">
                $adminHTML
                $blockedHTML
                $deleteHTML
            </div>
        </div>
    </div>           
    HTML;
    $viewContent = $viewContent . $UserHTML;
}

$viewScript = <<<HTML
    <script src='js/session.js'></script>
    <script defer>
        $("#addPhotoCmd").hide();
    </script>
HTML;

include "views/master.php";
