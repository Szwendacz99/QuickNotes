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
        User ID:<?= $user->getUUID() ?><br> <br>
        Nickname: <?= $user->getUsername() ?><br>
        Email: <?= $user->getEmail() ?><br><br>
        <br>
        <br>
        <br>
        <div id="change-nickname-message"></div>
        <input class="default-input" id="new-nickname" placeholder="Start typing nickname">
        <button class="default-button" id="change-nickname-button"> Change nickname </button>
        <br>
        <button onclick="location.href = '/logout';" class="default-button">Logout</button>
    </div>

    <div id="overlay-bg-note-menu" class="overlay-bg" onclick="switchOverlay('overlay-bg-note-menu', 'note-menu', 'flex')"></div>
    <div id="note-menu" class="overlay">
        Note: <div id="note-info-title"></div>
        Date created: <div id="note-info-created"></div>
        Last edited: <div id="note-info-edited"></div>
        <input class="default-input" id="new-tag" placeholder="tag name...">
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
        <div id="your-shared-notes-list"  class="left-panel-field-container">
            <div class="left-panel-field-header">
                Shared by You:
            </div>
            <?php foreach (array_keys($shared_notes) as $other_user): ?>
                <div class="left-panel-field-subheader">For <?=$other_user?></div>
                <?php foreach ($shared_notes[$other_user] as $note): ?>
                    <button class="left-panel-note-item" data-note-id="<?= $note->getNoteUUID() ?>"><?= $note->getTitle() ?></button>
                <?php endforeach; ?>
            <?php endforeach; ?>
        </div>
        <div id="others-shared-notes-list" class="left-panel-field-container">
            <div class="left-panel-field-header">
                Shared by others:
            </div>
            <?php foreach (array_keys($shared_notes_from_others) as $other_user): ?>
                <div class="left-panel-field-subheader">From <?=$other_user?></div>
                <?php foreach ($shared_notes_from_others[$other_user] as $note): ?>
                    <button class="left-panel-note-item" data-note-id="<?= $note->getNoteUUID() ?>"><?= $note->getTitle() ?></button>
                <?php endforeach; ?>
            <?php endforeach; ?>

        </div>
    </div>


    <div class="editor-container">
        <div class="top-panel">
            <button class="dashboard-bt left-panel-button" onclick="switchDisplay('left-panel', 'flex')"></button>
            <div class="dashboard">
                <button class="dashboard-bt new-note" ></button>
                <button class="dashboard-bt save" ></button>
                <button class="dashboard-bt tag" ></button>
                <button class="dashboard-bt share" ></button>
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

</html>
