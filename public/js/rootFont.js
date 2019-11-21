!(function(win, doc) {
  function setFontSize() {
    // 获取window 宽度
    // zepto实现 $(window).width()就是这么干的
    var winWidth1 = window.innerWidth;
    var winWidth2 = document.documentElement.clientWidth;
    var winWidth = winWidth1 < winWidth2 ? winWidth1 : winWidth2;
    // winWidth = 750;
    if (winWidth >= 750) {
      winWidth = 750;
    }
    doc.documentElement.style.fontSize = (winWidth / 750) * 100 + "px";
  }

  var evt = "onorientationchange" in win ? "orientationchange" : "resize";

  var timer = null;

  win.addEventListener(
    evt,
    function() {
      clearTimeout(timer);

      timer = setTimeout(setFontSize, 0);
    },
    false
  );

  win.addEventListener(
    "pageshow",
    function(e) {
      if (e.persisted) {
        clearTimeout(timer);

        timer = setTimeout(setFontSize, 0);
      }
    },
    false
  );

   window.setFontSize = setFontSize;

  //初始化
  setFontSize();

  document.documentElement.setAttribute("data-dpr", "1");

  var userAgentInfo = navigator.userAgent;
  var Agents = [
    "Android",
    "iPhone",
    "SymbianOS",
    "Windows Phone",
    "iPad",
    "iPod"
  ];
  var flag = true;
  for (var v = 0; v < Agents.length; v++) {
    if (userAgentInfo.indexOf(Agents[v]) > 0) {
      flag = false;
      break;
    }
  }

  document.documentElement.style.margin = "0 auto";
  if (flag) {
    // true为pc
    document.documentElement.style.maxWidth = "750px";
  } else {
    // 手机
    document.documentElement.style.maxWidth = "100%";
  }
})(window, document);
