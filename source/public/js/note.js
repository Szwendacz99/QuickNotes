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


function saveNote() {
    const note_id = document.querySelector("#note-title").getAttribute('data-note-id');
    const title = document.querySelector("#note-title").value;
    const text = document.querySelector("#note-text").value;


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
    const note_id_item = document.querySelector("#note-title");
    const title_item = document.querySelector("#note-title");
    const text_item = document.querySelector("#note-text");
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
        note_id_item.setAttribute('data-note-id', result['note_id']);
        title_item.value = title;
        text_item.value = text;
        addNoteItem(result['note_id'], title)
    })

}

function deleteNote() {
    const note_id = document.querySelector("#note-title").getAttribute('data-note-id');

    fetch("/delete", {
        method: "POST",
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({'note_id': note_id})
    }).then(function () {
        removeNoteItem(note_id);
        const title_item = document.querySelector("#note-title");
        const text_item = document.querySelector("#note-text");
        title_item.value = "";
        text_item.value = "";
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

noteButtons.forEach(button => button.addEventListener('click', openNote));

