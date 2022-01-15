<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" type="text/css" href="public/css/main.css">
    <link rel="stylesheet" type="text/css" href="public/css/editor.css">
    <link rel="stylesheet" type="text/css" href="public/css/leftpanel.css">

    <script type="text/javascript" src="public/js/editor.js"></script>

    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QuickNotes editor</title>
</head>
<body>
    <div id="overlay-bg" class="overlay-bg" onclick="switchOverlay('overlay-bg', 'user-account-menu', 'flex')"></div>
    <div id="user-account-menu" class="user-account-menu">
        <h2>Your account details:</h2>
        User ID: 00000000001: <br>
        Nickname: Maciej<br>
        Email: youremail@domain.dm<br>
        <br>
        <br>
        <br>
        <input class="default-input" placeholder="Start typing nickname">
        <button class="default-button"> Change nickname </button>
    </div>
    <div class="left-panel-container" id="left-panel">
        <button class="button-choose-tags" onclick="switchDisplay('choose-tags-form', 'flex')">Choose tags ↓</button>
        <form id="choose-tags-form">
            <button class="default-button">Save</button>
            <label><input type="checkbox" value="tag 1">tag 1</label>
            <label><input type="checkbox" value="tag 2" checked="checked">checked tag</label>
            <label><input type="checkbox" value="some long named tag (why so long?)">some long named tag (why so long?)</label>
            <label><input type="checkbox" value="tag 4 which ends here">tag 4 which ends here</label>
        </form>
    
        <div class="left-panel-field-container">
            <div class="left-panel-field-header">
                Your notes:
            </div>
            <?php foreach ($notes as $note): ?>
                <button class="left-panel-note-item"><?= $note->getTitle() ?></button>
            <?php endforeach; ?>
        </div>
        <div class="left-panel-field-container">
            <div class="left-panel-field-header">
                Shared by You:
            </div>
            <div class="left-panel-field-subheader"> For User1:</div>
            <button class="left-panel-note-item">note one</button>
            <div class="left-panel-field-subheader"> For User2:</div>
            <button class="left-panel-note-item">note with very long
                name that need to
                expand button
                vertically</button>
        </div>
        <div class="left-panel-field-container">
            <div class="left-panel-field-header">
                Shared by others:
            </div>
            <div class="left-panel-field-subheader"> From User1:</div>
            <button class="left-panel-note-item">note for u</button>
            <div class="left-panel-field-header">
                Shared by others:
            </div>

    
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
                <button class="user-menu-button" onclick="switchOverlay('overlay-bg', 'user-account-menu', 'flex')">Maciej</button>
            </div>
        </div>
        <div class="edit-fields-container">
            <input class="editing-panel editing-panel-title" value="note one" type="text" name="title">

            <div class="edit-textareas-container">
                <textarea class="editing-panel" name="note_input">test</textarea>
                <textarea class="editing-panel" disabled="disabled" name="display">test</textarea>
            </div>
        </div>
    </div>
    
    
</body>
</html>