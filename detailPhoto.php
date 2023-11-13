<?php
include 'php/sessionManager.php';
include 'models/photos.php';
include "models/users.php";
userAccess();

$viewTitle = "Détails photo";

$id = (int) $_GET["id"];
if (!isset($_GET["id"]))
    redirect("illegalAction.php");

$photo = PhotosFile()->get($id);

if ($photo == null)
    redirect("illegalAction.php");

$ownerId = $photo->OwnerId();
if ($ownerId != (int) $_SESSION["currentUserId"] && !$_SESSION["isAdmin"])
    redirect("illegalAction.php");

$title = $photo->Title();
$description = $photo->Description();
$creationDate = $photo->CreationDate();
$shared = $photo->Shared() == "true" ? "checked" : "";
$image = $photo->Image();
$owner = UsersFile()->Get($photo->OwnerId());
$ownerName = $owner->Name();
$ownerAvatar = $owner->Avatar();

$viewContent = <<<HTML
        <div>
            <div class="photoDetailsOwner">$ownerName</div>
            <div class="photoDetailsTitle">$title</div>
        </div>
HTML;



$viewScript = <<<HTML
        
        <script defer>
            $("#addPhotoCmd").hide();
        </script>
    HTML;

include "views/master.php";