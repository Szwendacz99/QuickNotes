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

noteButtons.forEach(button => button.addEventListener('click', openNote));

