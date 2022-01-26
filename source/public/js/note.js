const noteTitle = document.querySelector("#note-title");
const noteText = document.querySelector("#note-text");

const noteButtons = document.querySelectorAll(".left-panel-note-item");

function openNote() {
    const note_id = this.getAttribute('data-note-id');

    fetch("/note", {
        method: "POST",
        headers: {
            'Content-Type': 'application/json'
        },
        body: note_id
    }).then(function (response) {
        return response.json()
    }).then(function (result) {
        noteTitle.value = result['title'];
        noteText.value= result['text'];
        noteTitle.setAttribute('data-note-id', result['note_id'])
    })
}

function noteInfoOverlay() {
    const note_id = document.querySelector("#note-title").getAttribute('data-note-id');
    const noteTitleItem = document.querySelector("#note-info-title")
    const noteCreatedItem = document.querySelector("#note-info-created")
    const noteLastEditItem = document.querySelector("#note-info-edited")
    const noteTagsItem = document.querySelector('#note-info-tags')

    fetch("/noteinfo", {
        method: "POST",
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({'note_id': note_id})
    }).then(function (response) {
        return response.json()
    }).then(function (result) {

        noteTitleItem.innerHTML = result['title'];
        noteCreatedItem.innerHTML = result['creation_datetime'];
        noteLastEditItem.innerHTML = result['last_edit'];
        noteTagsItem.innerHTML = "";
        result['tags'].forEach(tag => {
            const template = document.querySelector("#template-note-info-tag-item");

            const clone = template.content.cloneNode(true);
            const label = clone.querySelector('label');
            const checkbox = label.querySelector('input');

            checkbox.setAttribute('checked', 'true');

            checkbox.setAttribute('data-tag-uuid', tag['tag_id']);

            label.innerHTML += tag['tag_name'];

            // checkbox.addEventListener('click', switchTag)
            noteTagsItem.appendChild(clone);
            noteTagsItem.insertAdjacentHTML('beforeend', "<br>");
        })

        result['other_tags'].forEach(tag => {
            const template = document.querySelector("#template-note-info-tag-item");

            const clone = template.content.cloneNode(true);
            const label = clone.querySelector('label');
            const checkbox = clone.querySelector('input');

            checkbox.removeAttribute('checked');

            checkbox.setAttribute('data-tag-uuid', tag['tag_id']);

            label.innerHTML += tag['tag_name'];

            // checkbox.addEventListener('click', switchTag)
            noteTagsItem.appendChild(clone);
            noteTagsItem.insertAdjacentHTML('beforeend', "<br>");
        })

        switchOverlay('overlay-bg-note-menu', 'note-menu', 'flex')

    })
}


function saveNote() {
    const note_id = document.querySelector("#note-title").getAttribute('data-note-id');
    const title = noteTitle.value;
    const text = noteText.value;


    fetch("/save", {
        method: "POST",
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({'note_id': note_id, 'title': title, 'text': text})
    });

    let noteItems = document.querySelectorAll(".left-panel-note-item");

    noteItems.forEach(item => {
        if (item.getAttribute("data-note-id") === note_id) {
            item.innerHTML = title;
        }
    })
}

function newNote() {
    const title_item = noteTitle;
    const text_item = noteText;
    const title = "New note";
    const text = "";

    fetch("/new", {
        method: "POST",
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({'title': title, 'text': text})
    }).then(function (response) {
        return response.json()
    }).then(function (result) {
        title_item.setAttribute('data-note-id', result['note_id']);
        title_item.value = title;
        text_item.value = text;
        addNoteItem(result['note_id'], title)
    })

}

function deleteNote() {

    const note_id = noteTitle.getAttribute('data-note-id');

    fetch("/delete", {
        method: "POST",
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({'note_id': note_id})
    }).then(function () {
        removeNoteItem(note_id);
        noteTitle.value = "";
        noteText.value = "";
    })
}

function removeNoteItem(note_id) {
    document.querySelectorAll('.left-panel-note-item').forEach(item => {
        if (item.getAttribute('data-note-id') === note_id) {
            item.remove();
        }
    })

}

function addNoteItem(noteUUID, title) {
    const template = document.querySelector("#template-note-item");

    const clone = template.content.cloneNode(true);
    const button = clone.querySelector('.left-panel-note-item')
    button.innerHTML = title;

    button.setAttribute('data-note-id', noteUUID);
    button.addEventListener('click', openNote)
    document.querySelector("#your-notes-list").appendChild(clone);

}

document.querySelector(".save").addEventListener('click', saveNote);
document.querySelector(".new-note").addEventListener('click', newNote);
document.querySelector(".delete").addEventListener('click', deleteNote);
document.querySelector(".tag").addEventListener('click', noteInfoOverlay);

noteButtons.forEach(button => button.addEventListener('click', openNote));
