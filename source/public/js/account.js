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
        if (result['result'] === 'Nickname changed successfully!') {
            const usernameItem1 = document.querySelector("#acc-details-username");
            usernameItem1.innerHTML = nickname;
            const usernameItem2 = document.querySelector(".user-menu-button");
            usernameItem2.innerHTML = nickname;
            changeNickanmeMessageNode.style.color = "lightgreen";
        } else {
            changeNickanmeMessageNode.style.color = "red";
        }
    })
}

document.querySelector('#change-nickname-button').addEventListener('click', changeNickname)
