function changeNickname() {
    const nickname = document.querySelector("#new-nickname").value;
    const changeNickanmeMessageNode = document.querySelector("#change-nickname-message")

    fetch("/nickname", {
        method: "POST",
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({'nickname': nickname})
    }).then(function (response) {
        return response.json();
    }).then(function (result) {
        changeNickanmeMessageNode.innerHTML = result['result'];
    })
}

document.querySelector('#change-nickname-button').addEventListener('click', changeNickname)
