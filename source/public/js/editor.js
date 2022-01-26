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
}

document.querySelector(".save").addEventListener('click', saveNote);
