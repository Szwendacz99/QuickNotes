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
    // console.log(list.pop());
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

    checkbox.setAttribute('onChange', 'addTagToNote(this)')
    checkbox.setAttribute('data-tag-uuid', tag['tag_id']);

    label.innerHTML += tag['tag_name'];

    // checkbox.addEventListener('click', switchTag)
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

document.querySelector("#new-tag-button").addEventListener('click', createTag);

noteButtons.forEach(button => button.addEventListener('click', openNote));
