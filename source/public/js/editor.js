function switchDisplay(id, displayType) {
    if (document.getElementById(id).style.display === "none") {
        document.getElementById(id).style.display = displayType;
    } else {
        document.getElementById(id).style.display = "none";
    }
}

function switchOverlay(overlayBgId, overlayId, displayType) {
    if (document.getElementById(overlayId).style.display === "none") {
        document.getElementById(overlayId).style.display = displayType;
        document.getElementById(overlayBgId).style.display = displayType;
    } else {
        document.getElementById(overlayId).style.display = "none";
        document.getElementById(overlayBgId).style.display = "none";
    }
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
