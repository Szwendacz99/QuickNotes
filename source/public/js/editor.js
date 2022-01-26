function switchDisplay(id, displayType) {
    const elem = document.getElementById(id);
    if (elem.style.display === "none") {
        elem.style.display = displayType;
    } else {
        elem.style.display = "none";
    }
}

function switchOverlay(overlayBgId, overlayId, displayType) {
    const overlay = document.getElementById(overlayId);
    const overlayBg = document.getElementById(overlayBgId);
    if (overlay.style.display === "none") {
        overlay.style.display = displayType;
        overlayBg.style.display = displayType;
    } else {
        overlay.style.display = "none";
        overlayBg.style.display = "none";
    }
}


