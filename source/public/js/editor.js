function switchDisplay(id, displayType) {
    if (document.getElementById(id).style.display === "none") {
        document.getElementById(id).style.display = displayType;
    } else {
        document.getElementById(id).style.display = "none";
    }
}
