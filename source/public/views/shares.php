
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