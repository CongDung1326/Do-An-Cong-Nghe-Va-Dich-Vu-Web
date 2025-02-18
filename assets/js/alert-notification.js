$(document).ready(function () {
    $(".my-button").each((index, value) => {
        $(value).click((e) => {
            $(value).next().addClass("show");
            $(value).next().removeClass("hide");
            $(value).next().addClass("showAlert");
            setTimeout(function () {
                $(value).next().removeClass("show");
                $(value).next().addClass("hide");
            }, 2500);
        })
    })
})