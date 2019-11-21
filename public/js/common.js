function pageRem() {
    var winWidth = document.documentElement.clientWidth;
    var winHeight = window.innerHeight;
    if (winWidth <= 320) winWidth = 320;
    if (winWidth >= 750) winWidth = 750;
    var targetRem = parseInt(winWidth / 375 * 100);
    if (targetRem % 2) {
        targetRem++;
    }

    console.log("---winWidth:", winWidth);

    if (winWidth >= 750) {
        document.documentElement.style.fontSize = 12 + "px";
    } else {
        document.documentElement.style.fontSize = targetRem + "px";
    }
}
window.addEventListener("resize", pageRem);
pageRem();