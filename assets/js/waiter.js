document.onreadystatechange = function () {
    if (document.readyState !== "complete") {
    } else {
        document.querySelector("#page_loader").style.visibility = "hidden";
    }
}; 