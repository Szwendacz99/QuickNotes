const noteTitle = document.querySelector("#note-title");
const noteText = document.querySelector("#note-text");

let noteButtons = document.querySelectorAll(".left-panel-note-item");

function openNote() {
    const note_id = this.getAttribute('data-note-id');

    fetch("/note", {
        method: "POST",
        headers: {
            'Content-Type': 'application/json'
        },
        body: note_id
    }).then(function (response) {
        return response.json();
    }).then(function (result) {
        noteTitle.value = result['title'];
        noteText.value= result['text'];
        noteTitle.setAttribute('data-note-id', result['note_id']);
        viewUpdate();
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
        result['tags'].forEach(tag => addTagItem(noteTagsItem, true, tag))

        result['other_tags'].forEach(tag => addTagItem(noteTagsItem, false, tag))

        switchOverlay('overlay-bg-note-menu', 'note-menu', 'flex')

    })
}

function createTag() {
    const note_id = noteTitle.getAttribute('data-note-id');
    const tagName = document.querySelector('#new-tag').value;
    const noteTagsItem = document.querySelector('#note-info-tags');
    const chooseTagsForm = document.querySelector("#choose-tags-form");

    fetch("/newtag", {
        method: "POST",
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({'tag_name': tagName, 'note_id': note_id})
    }).then(function (response) {
        return response.json()
    }).then(function (result) {

        if (result['result'] === 'ok') {
            const tagDict = {'tag_name': tagName, 'tag_id': result['tag_id']};
            const checkboxInOverlay = addTagItem(noteTagsItem, true, tagDict);
            const checkboxInLeftPanel = addTagItem(chooseTagsForm, true, tagDict);

            checkboxInLeftPanel.setAttribute("onChange", "filterByTag()");

            addTagToNote(checkboxInOverlay);
            filterByTag();
        }

    })
}

function filterByTag() {
    const ids = getNotCheckedTagsId();

    fetch("/notesbytags", {
        method: "POST",
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({'tags': ids})
    }).then(function (response) {
        return response.json()
    }).then(function (notes) {
        document.querySelector("#your-notes-list").querySelectorAll("button").forEach((button => {
            button.style.display = "none";
            notes.forEach(note => {
                if (button.getAttribute('data-note-id') === note['note_id']) {
                    button.style.display = 'flex';
                }
            })
        }))
    })
}

function getNotCheckedTagsId() {
    const tagsContainer = document.querySelector("#choose-tags-form")

    let list = ['00000000-0000-0000-0000-000000000000'];

    tagsContainer.querySelectorAll('input').forEach( checkbox => {
        if (!checkbox.checked) {
            list.push(checkbox.getAttribute('data-tag-uuid'))
        }
    })
    return list;
}



function addTagItem(container, checked, tag) {
    const template = document.querySelector("#template-note-info-tag-item");

    const clone = template.content.cloneNode(true);
    const label = clone.querySelector('label');
    const checkbox = clone.querySelector('input');

    if (!checked) {
        checkbox.removeAttribute('checked');
    } else {
        checkbox.setAttribute('checked', 'true');
    }
    if (container.getAttribute('id') !== "choose-tags-form") {
        checkbox.setAttribute('onChange', 'addTagToNote(this)');
    } else {
        checkbox.setAttribute('onChange', 'filterByTag()');
    }

    checkbox.setAttribute('data-tag-uuid', tag['tag_id']);

    label.innerHTML += tag['tag_name'];

    container.appendChild(clone);
    return checkbox;
}

function addTagToNote(tag_checkbox) {
    const note_id = noteTitle.getAttribute('data-note-id');
    if (tag_checkbox.checked) {
        fetch("/tagnote", {
            method: "POST",
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({'note_id': note_id, 'tag_id': tag_checkbox.getAttribute('data-tag-uuid')})
        }).then((result) => {
            filterByTag();
        })
    } else {
        fetch("/untagnote", {
            method: "POST",
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({'note_id': note_id, 'tag_id': tag_checkbox.getAttribute('data-tag-uuid')})
        }).then((result) => {
            filterByTag();
        })
    }
}

function saveNote() {
    const note_id = noteTitle.getAttribute('data-note-id');
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

function openShareMenu() {

    const noteId = noteTitle.getAttribute('data-note-id');

    fetch("/noteshares", {
        method: "POST",
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({'note_id': noteId})
    }).then(function (response) {
        return response.json()
    }).then(function (shares) {
        document.querySelector("#current-shares-of-note").innerHTML = "";
        shares.forEach(username => {
            addNewUnshareButton(username['username']);
        })
    })

    switchOverlay('overlay-bg-sharing-menu', 'sharing-menu', 'flex');
}

function share() {
    const username = document.querySelector("#username-share-input").value
    const note_id = noteTitle.getAttribute('data-note-id');

    fetch("/share", {
        method: "POST",
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({'note_id': note_id, 'username': username})
    }).then(function (response) {
        return response.json()
    }).then(function (result) {
        if (result['result'] === 'ok') {
            addNewUnshareButton(username);
            refreshShares();
        }
    })
}

function unshare(username, button) {
    const note_id = noteTitle.getAttribute('data-note-id');

    fetch("/unshare", {
        method: "POST",
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({'note_id': note_id, 'username': username})
    }).then(function (response) {
        return response.json()
    }).then(function (result) {
        if (result['result'] === 'ok') {
            button.innerHTML = "Unshared !";
        }
        refreshShares()
    })
}

function refreshShares() {
    const container = document.querySelector("#shares-panel")

    fetch("/shares", {
        method: "GET",
        headers: {
            'Content-Type': 'application/json'
        }
    }).then(function (response) {
        response.text().then(text => {
            container.innerHTML = text;
            noteButtons = document.querySelectorAll(".left-panel-note-item");
            noteButtons.forEach(button => button.addEventListener('click', openNote));
        });
    })
}

function addNewUnshareButton(username) {
    const template = document.querySelector("#user-for-unshare-button");
    const clone = template.content.cloneNode(true);
    const button = clone.querySelector('button');
    const label = clone.querySelector('label');

    label.innerHTML = username;

    button.setAttribute('onClick', "unshare('"+username+"', this)");

    document.querySelector("#current-shares-of-note").appendChild(clone);
}

function newNote() {
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
        noteTitle.setAttribute('data-note-id', result['note_id']);
        noteTitle.value = title;
        noteText.value = text;
        const container = document.querySelector("#your-notes-list")
        addNoteItem(container, result['note_id'], title)
    })
}

function deleteNote() {

    if (!confirm("Do you really want to delete this note?")) {
        return;
    }

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
        viewUpdate();
    })
}

function removeNoteItem(note_id) {
    document.querySelectorAll('.left-panel-note-item').forEach(item => {
        if (item.getAttribute('data-note-id') === note_id) {
            item.remove();
        }
    })

}

function addNoteItem(container, noteUUID, title) {
    const template = document.querySelector("#template-note-item");

    const clone = template.content.cloneNode(true);
    const button = clone.querySelector('.left-panel-note-item')
    button.innerHTML = title;

    button.setAttribute('data-note-id', noteUUID);
    button.addEventListener('click', openNote)
    container.appendChild(clone);

}

document.querySelector(".save").addEventListener('click', saveNote);
document.querySelector(".new-note").addEventListener('click', newNote);
document.querySelector(".delete").addEventListener('click', deleteNote);
document.querySelector(".tag").addEventListener('click', noteInfoOverlay);
document.querySelector('.share').addEventListener('click', openShareMenu);

document.querySelector("#new-tag-button").addEventListener('click', createTag);

noteButtons.forEach(button => button.addEventListener('click', openNote));

document.querySelector('#share-button').addEventListener('click', share);
