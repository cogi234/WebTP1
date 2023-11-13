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


$formatter = new IntlDateFormatter('fr_CA',IntlDateFormatter::FULL,IntlDateFormatter::FULL,'America/New_York',IntlDateFormatter::GREGORIAN);
$date = $formatter->format($creationDate);


$viewContent = <<<HTML
        <div class="photoLayout">
            <div class="photoDetailsOwner">
                <div class="UserAvatarSmall transparentBackground" style="background-image:url('$ownerAvatar')" title="$ownerName"></div>
                $ownerName
            </div>
            <div class="photoDetailsTitle">$title</div>
            <div class="photoDetailsDescription">$description</div>
            <div class="photoDetailsCreationDate">$date</div>
            <img src="$image" class="photoDetailsLargeImage">

            
        </div>
HTML;



$viewScript = <<<HTML
        
        <script defer>
            $("#addPhotoCmd").hide();
        </script>
    HTML;

include "views/master.php";