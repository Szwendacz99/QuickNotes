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
