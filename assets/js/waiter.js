document.onreadystatechange = function () {
    //console.log(document.isMy3DReady);
    if (document.isMy3DReady !== undefined) {
        //console.log('il y a une scène 3d')
        //La fonction d'affichage se trouve dans le script babylon.js
    } else {
        if (document.readyState !== "complete") {
        } else {
            document.querySelector("#page_loader").style.visibility = "hidden";
        }
    }
};