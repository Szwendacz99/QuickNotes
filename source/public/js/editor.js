function switchDisplay(id, displayType) {
    const elem = document.getElementById(id);
    if (elem.style.display !== displayType) {
        elem.style.display = displayType;
    } else {
        elem.style.display = "none";
    }
}

function switchOverlay(overlayBgId, overlayId, displayType) {
    const overlay = document.getElementById(overlayId);
    const overlayBg = document.getElementById(overlayBgId);
    if (overlay.style.display !== displayType) {
        overlay.style.display = displayType;
        overlayBg.style.display = displayType;
    } else {
        overlay.style.display = "none";
        overlayBg.style.display = "none";
    }
}

function switchEditor() {
    const edit = document.querySelector("#note-text");
    const view = document.querySelector("#note-display");


    if (edit.style.display === 'none') {
        edit.style.maxWidth = '100%';
        view.style.display = 'none';
        edit.style.display = 'block';
    } else if (view.style.display === 'none'){
        view.style.display = 'block';
        view.style.maxWidth = '50%';
        edit.style.maxWidth = '50%';
    } else {
        view.style.maxWidth = '100%';
        view.style.display = 'block';
        edit.style.display = 'none';
    }
}

function viewUpdate() {
    const textInput = document.querySelector("#note-text")
    const text = textInput.value;
    const view = document.querySelector("#note-display");

    view.innerHTML = marked.parse(text);

    syncScrollFromInput()
}

function syncScrollFromInput() {
    const textInput = document.querySelector("#note-text")
    const view = document.querySelector("#note-display");
    view.scrollTop = textInput.scrollTop;
}

function syncScrollFromView() {
    const textInput = document.querySelector("#note-text")
    const view = document.querySelector("#note-display");
    textInput.scrollTop = view.scrollTop;
}

document.querySelector("#note-text").addEventListener('input', viewUpdate);
document.querySelector(".change-view").addEventListener('click', switchEditor);
document.querySelector("#note-text").addEventListener('scroll', syncScrollFromInput);
document.querySelector("#note-display").addEventListener('scroll', syncScrollFromView);
