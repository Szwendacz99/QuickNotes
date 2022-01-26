<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" type="text/css" href="public/css/main.css">
    <link rel="stylesheet" type="text/css" href="public/css/editor.css">
    <link rel="stylesheet" type="text/css" href="public/css/leftpanel.css">

    <script type="text/javascript" src="public/js/editor.js" defer></script>
    <script type="text/javascript" src="public/js/note.js" defer></script>
    <script type="text/javascript" src="public/js/account.js" defer></script>

    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QuickNotes editor</title>
</head>
<body>
    <div id="overlay-bg" class="overlay-bg" onclick="switchOverlay('overlay-bg', 'user-account-menu', 'flex')"></div>
    <div id="user-account-menu" class="user-account-menu">
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
    <div class="left-panel-container" id="left-panel">
        <button class="button-choose-tags" onclick="switchDisplay('choose-tags-form', 'flex')">Choose tags ↓</button>
        <div id="choose-tags-form">
            <?php foreach ($user_tags as $tag): ?>
                <label><input type="checkbox" checked="true" data-tag-uuid="<?= $tag->getUuid() ?>" value="<?= $tag->getName() ?>"><?= $tag->getName() ?></label>
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
                <button class="user-menu-button" onclick="switchOverlay('overlay-bg', 'user-account-menu', 'flex')"><?= $user->getUsername() ?></button>
            </div>
        </div>
        <div class="edit-fields-container">
            <input class="editing-panel editing-panel-title" data-note-id="" id="note-title" value="" type="text" name="title">

            <div class="edit-textareas-container">
                <textarea class="editing-panel" id="note-text" name="note_input"></textarea>
                <textarea class="editing-panel" id="note-display" disabled="disabled" name="display"></textarea>
            </div>
        </div>
    </div>
    
    
</body>
<template id="template-note-item">
    <button class="left-panel-note-item" data-note-id=""></button>
</template>

</html>
