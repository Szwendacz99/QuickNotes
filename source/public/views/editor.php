<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" type="text/css" href="public/css/main.css">
    <link rel="stylesheet" type="text/css" href="public/css/editor.css">
    <link rel="stylesheet" type="text/css" href="public/css/leftpanel.css">

    <script type="text/javascript" src="public/js/editor.js" defer></script>
    <script type="text/javascript" src="public/js/note.js" defer></script>
    <script type="text/javascript" src="public/js/account.js" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/marked/marked.min.js"></script>

    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QuickNotes editor</title>
</head>
<body>
    <div id="overlay-bg-account-menu" class="overlay-bg" onclick="switchOverlay('overlay-bg-account-menu', 'user-account-menu', 'flex')"></div>
    <div id="user-account-menu" class="overlay">
        <h2>Your account details:</h2>
        <div class="info">
            <h5>User ID:</h5>
            <div><?= $user->getUUID() ?></div>
            <h5>Username:</h5>
            <div id="acc-details-username"><?= $user->getUsername() ?></div>
            <h5>Email:</h5>
            <div><?= $user->getEmail() ?></div>
        </div>
        <div id="change-nickname-message"></div>
        <input class="default-input" id="new-nickname" placeholder="Start typing nickname">
        <button class="default-button" id="change-nickname-button"> Change nickname </button>
        <br>
        <button onclick="location.href = '/logout';" class="default-button">Logout</button>
    </div>

    <div id="overlay-bg-sharing-menu" class="overlay-bg" onclick="switchOverlay('overlay-bg-sharing-menu', 'sharing-menu', 'flex')"></div>
    <div id="sharing-menu" class="overlay">
        <h2>Sharing menu</h2>
        <div class="info" id="current-shares-of-note">

        </div>
        <input class="default-input" autocomplete="off" oninput="searchForUsers()" id="username-share-input" placeholder="Search for user...">
        <button class="default-button" id="share-button" > Share </button>
        Possible users: <br>
        <div class="info">
            <div id="share-users-list" class="manage-note-tags">
            </div>
        </div>
    </div>

    <div id="overlay-bg-note-menu" class="overlay-bg" onclick="switchOverlay('overlay-bg-note-menu', 'note-menu', 'flex')"></div>
    <div id="note-menu" class="overlay">
        <h2>Note tags</h2>
        <div class="info">
            <h5>Note:</h5> <div id="note-info-title"></div>
            <h5>Date created:</h5> <div id="note-info-created"></div>
            <h5>Last edited: </h5><div id="note-info-edited"></div>
        </div>
        <input class="default-input" autocomplete="off" id="new-tag" placeholder="tag name...">
        <button class="default-button" id="new-tag-button"> Add new tag </button>
        Tags: <br>
        <div id="note-info-tags" class="manage-note-tags">
        </div>

    </div>

    <div class="left-panel-container" id="left-panel">
        <button class="button-choose-tags" onclick="switchDisplay('choose-tags-form', 'flex')">Choose tags ↓</button>
        <div id="choose-tags-form">
            <?php foreach ($user_tags as $tag): ?>
                <label><input type="checkbox" checked onChange="filterByTag()" data-tag-uuid="<?= $tag->getUuid() ?>" ><?= $tag->getName() ?></label>
            <?php endforeach; ?>
        </div>
    
        <div id="your-notes-list" class="left-panel-field-container">
            <div class="left-panel-field-header">
                Your notes:
            </div>
            <?php foreach ($notes as $note): ?>
                <button class="left-panel-note-item" data-note-id="<?= $note->getUuid() ?>"><?= $note->getTitle() ?></button>
            <?php endforeach; ?>
        </div>
        <div id="shares-panel">
        <?php include 'shares.php'?>
        </div>
    </div>


    <div class="editor-container">
        <div class="top-panel">
            <button class="dashboard-bt left-panel-button" onclick="switchDisplay('left-panel', 'flex')"></button>
            <div class="dashboard">
                <button class="dashboard-bt new-note" ></button>
                <button class="dashboard-bt save" ></button>
                <button class="dashboard-bt tag" ></button>
                <button class="dashboard-bt share"></button>
                <button class="dashboard-bt change-view" ></button>
                <button class="dashboard-bt delete" ></button>
                <button class="user-menu-button" onclick="switchOverlay('overlay-bg-account-menu', 'user-account-menu', 'flex')"><?= $user->getUsername() ?></button>
            </div>
        </div>
        <div class="edit-fields-container">
            <input class="editing-panel editing-panel-title" data-note-id="" id="note-title" value="" type="text" name="title">

            <div class="edit-textarea-container">
                <textarea class="editing-panel" id="note-text" name="note_input"></textarea>
                <div class="editing-panel" id="note-display"></div>
            </div>
        </div>
    </div>
    
    
</body>
<template id="template-note-item">
    <button class="left-panel-note-item" data-note-id=""></button>
</template>

<template id="template-note-info-tag-item">
    <label><input type="checkbox" data-tag-uuid=""></label>
</template>

<template id="user-for-unshare-button">
    <div><label>User 1</label> - <button class="default-button">Unshare</button></div>
</template>

</html>
