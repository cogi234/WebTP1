<?php
include 'php/sessionManager.php';
include "models/photos.php";
include "models/users.php";

userAccess();

$viewName="photoList";
$viewTitle = "Photos";
$list = PhotosFile()->toArray();
$viewContent = "<div class='photosLayout'>";


//Filter functions
function FilterMine($a) {
    return $a->OwnerId() == $_SESSION["currentUserId"];
}

if(isset($_GET["filter"]) && $_GET["filter"] == "mine") {
    $list = array_filter($list,"FilterMine");
}

//Sort functions
function CompareDates($a, $b) {
    return $a->CreationDate() - $b->CreationDate();
}
function CompareAuthors($a, $b) {
    return strcasecmp(UsersFile()->Get($a->OwnerId())->Name(), UsersFile()->Get($b->OwnerId())->Name());
}

if(isset($_GET["sort"])) {
    if ($_GET["sort"] == "owners"){
        usort($list, "CompareAuthors");
    } else  if ($_GET["sort"] == "date"){
        usort($list, "CompareDates");
    }
}

foreach ($list as $photo) {
    $id = strval($photo->id());
    $title = $photo->Title();
    $description = $photo->Description();
    $image = $photo->Image();
    $owner = UsersFile()->Get($photo->OwnerId());
    $ownerName = $owner->Name();
    $ownerAvatar = $owner->Avatar();
    $shared = $photo->Shared() == "true";
    $sharedIndicator = "";
    $editCmd = "";
    $visible = $shared;
    if (($photo->OwnerId() == (int)$_SESSION["currentUserId"]) || $_SESSION["isAdmin"]) {
        $visible = true;
        $editCmd = <<<HTML
            <a href="editPhotoForm.php?id=$id" class="cmdIconSmall fa fa-pencil" title="Editer $title"> </a>
            <a href="confirmDeletePhoto.php?id=$id"class="cmdIconSmall fa fa-trash" title="Effacer $title"> </a>
        HTML;
        if ($shared) {
            $sharedIndicator = <<<HTML
                <div class="UserAvatarSmall transparentBackground" style="background-image:url('images/shared.png')" title="partagée"></div>
            HTML;
        } 
    }
    if ($visible) {
    $photoHTML = <<<HTML
        <div class="photoLayout" photo_id="$id">
            <div class="photoTitleContainer" title="$description">
                <div class="photoTitle ellipsis">$title</div>
                $editCmd
            </div>
            <a href="detailPhoto.php?id=$id">
                <div class="photoImage" style="background-image:url('$image')">
                    <div class="UserAvatarSmall transparentBackground" style="background-image:url('$ownerAvatar')" title="$ownerName"></div>
                    $sharedIndicator
                </div>
            </a>
        </div>           
        HTML;
        $viewContent = $viewContent . $photoHTML;
    }
}
$viewContent = $viewContent . "</div>";

$viewScript = <<<HTML
    <script src='js/session.js'></script>
HTML;

include "views/master.php";
