async function notification(
    type = "success" | "error" | "warning",
    message = "",
    url = ""
) {
    let html = "";

    switch (type) {
        case "success":
            html = `
            <div class='notification-container'>
                <div class='notification ${type}'>
                    <div class='icon'><i class='fa-solid fa-check'></i></div>
                    <div class='title'>${message}</div>
                </div>
            </div>
            `
            break;
        case "error":
            html = `
            <div class='notification-container'>
                <div class='notification ${type}'>
                    <div class='icon'><i class='fa-solid fa-xmark'></i></div>
                    <div class='title'>${message}</div>
                </div>
            </div>
            `
            break;
        case "warning":
            html = `
            <div class='notification-container'>
                <div class='notification ${type}'>
                    <div class='icon'><i class='fa-solid fa-exclamation'></i></div>
                    <div class='title'>${message}</div>
                </div>
            </div>
            `
            break;
    }

    let body = document.querySelector("body");
    body.insertAdjacentHTML("afterend", html);

    if (url == "") {
        await sleep(2000);
        location.reload();
    }
    else {
        await sleep(2000);
        location.href = url;
    }
}
async function notificationYesNo(
    type = "success" | "error" | "warning",
    message = "",
    url = ""
) {
    let html = "";

    switch (type) {
        case "success":
            html = `
            <div class='notification-container yes-no'>
                <div class='notification'>
                    <div class='icon ${type}'><i class='fa-solid fa-check'></i></div>
                    <div class='title'>${message}</div>

                    <div class='buttons'>
                        <button>Yes</button>
                        <button>No</button>
                    </div>
                </div>
            </div>
            `
            break;
        case "error":
            html = `
            <div class='notification-container yes-no'>
                <div class='notification'>
                    <div class='icon ${type}'><i class='fa-solid fa-xmark'></i></div>
                    <div class='title'>${message}</div>

                    <div class='buttons'>
                        <button>Yes</button>
                        <button>No</button>
                    </div>
                </div>
            </div>
            `
            break;
        case "warning":
            html = `
            <div class='notification-container yes-no'>
                <div class='notification'>
                    <div class='icon ${type}'><i class='fa-solid fa-exclamation'></i></div>
                    <div class='title'>${message}</div>

                    <div class='buttons'>
                        <button>Yes</button>
                        <button>No</button>
                    </div>
                </div>
            </div>
            `
            break;
    }

    let body = document.querySelector("body");
    body.insertAdjacentHTML("afterend", html);
    let yesButton = document.querySelector(".notification-container.yes-no button:first-child");
    let noButton = document.querySelector(".notification-container.yes-no button:last-child");
    let containerNotificationYesNo = document.querySelector(".notification-container.yes-no")

    noButton.addEventListener('click', () => {
        containerNotificationYesNo.classList.add('hidden');
    })
    yesButton.addEventListener('click', () => {
        url == "" ? location.reload() : location.href = url;
    })
}
function sleep(ms) {
    return new Promise(resolve => setTimeout(resolve, ms));
}