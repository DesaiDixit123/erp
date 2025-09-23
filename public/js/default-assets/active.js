(function ($) {
  "use strict";

  // :: Variables
  var flapt_window = $(window);
  var pageWrapper = $(".flapt-page-wrapper");
  var sideMenuArea = $(".flapt-sidemenu-wrapper");

  // Toast active code
  window.addEventListener("load", () => {
    let myAlert = document.querySelectorAll(".toast")[0];
    if (myAlert) {
      let frAlert = new bootstrap.Toast(myAlert);
      frAlert.show();
    }
  });
  

  // :: Slimscroll Active Code
  if ($.fn.slimscroll) {
    $("#flaptSideNav").slimscroll({
      height: "100%",
      size: "2px",
      position: "right",
      color: "#8c8c8c",
      alwaysVisible: false,
      distance: "0px",
      railVisible: false,
      touchScrollStep: 75,
      wheelStep: 10,
    });
  }
  // :: Active Code
  if ($.fn.slimscroll) {
    $("#form-todo-list").slimscroll({
      height: "350",
      size: "2px",
      position: "right",
      color: "#8c8c8c",
      alwaysVisible: false,
      distance: "0px",
      railVisible: false,
      touchScrollStep: 75,
      wheelStep: 10,
    });
  }
  // :: Active Code
  if ($.fn.slimscroll) {
    $("#listCard, #listCard1").slimscroll({
      height: "420",
      size: "2px",
      position: "right",
      color: "#8c8c8c",
      alwaysVisible: false,
      distance: "0px",
      railVisible: false,
      touchScrollStep: 75,
      wheelStep: 10,
    });
  }

  // :: Slimscroll Active Code
  if ($.fn.slimscroll) {
    $("#messageBox, #notificationsBox").slimscroll({
      height: "345px",
      size: "4px",
      position: "right",
      color: "#ebebeb",
      alwaysVisible: false,
      distance: "0px",
      railVisible: false,
      wheelStep: 15,
    });
  }
  // :: Chatbox Active Code
  if ($.fn.slimscroll) {
    $("#HotChat-list, #HotChat-list2, #HotChat-list3, #chatBody").slimscroll({
      height: "500px",
      size: "4px",
      position: "right",
      color: "#ebebeb",
      alwaysVisible: false,
      distance: "0px",
      railVisible: false,
      wheelStep: 15,
    });
  }

  // :: Chatbox Active Code
  if ($.fn.slimscroll) {
    $("#chatBody").slimscroll({
      height: "580px",
      size: "4px",
      position: "right",
      color: "#ebebeb",
      alwaysVisible: false,
      distance: "0px",
      railVisible: false,
      wheelStep: 15,
    });
  }

  // :: Slimscroll Active Code
  if ($.fn.slimscroll) {
    $("#product, #shiping").slimscroll({
      height: "280px",
      size: "4px",
      position: "right",
      color: "#ebebeb",
      alwaysVisible: false,
      distance: "0px",
      railVisible: false,
      wheelStep: 15,
    });
  }

  // :: Slimscroll Active Code
  if ($.fn.slimscroll) {
    $(".widgetbox").slimscroll({
      height: "400px",
      size: "2px",
      position: "right",
      color: "#ebebeb",
      alwaysVisible: false,
      distance: "0px",
      railVisible: false,
      wheelStep: 15,
    });
  }

  // :: Slimscroll Active Code
  if ($.fn.slimscroll) {
    $("#dashboardTimeline").slimscroll({
      height: "380px",
      size: "2px",
      position: "right",
      color: "#ebebeb",
      alwaysVisible: false,
      distance: "0px",
      railVisible: false,
      wheelStep: 2,
    });
  }

  // :: Slimscroll Active Code
  if ($.fn.slimscroll) {
    $("#dashboardTable").slimscroll({
      height: "400px",
      size: "2px",
      position: "right",
      color: "#ebebeb",
      alwaysVisible: false,
      distance: "0px",
      railVisible: false,
      wheelStep: 15,
    });
  }

  // :: Slimscroll Active Code
  if ($.fn.slimscroll) {
    $("#chatBox").slimscroll({
      height: "330px",
      size: "2px",
      position: "right",
      color: "#ebebeb",
      alwaysVisible: false,
      distance: "0px",
      railVisible: false,
      wheelStep: 15,
    });
  }
  // :: Slimscroll Active Code
  if ($.fn.slimscroll) {
    $("#shareId, #activeUser").slimscroll({
      height: "400px",
      size: "2px",
      position: "right",
      color: "#ebebeb",
      alwaysVisible: false,
      distance: "0px",
      railVisible: false,
      wheelStep: 15,
    });
  }

  // :: Slimscroll Active Code
  if ($.fn.slimscroll) {
    $("#chooseLayout, #quickSettingPanel").slimscroll({
      height: "100%",
      size: "2px",
      position: "right",
      color: "#ebebeb",
      alwaysVisible: false,
      distance: "0px",
      railVisible: false,
      wheelStep: 15,
    });
  }

  // :: Menu Active Code
  $("#menuCollasped").on("click", function () {
    pageWrapper.toggleClass("menu-collasped-active");
  });

  $("#mobileMenuOpen").on("click", function () {
    pageWrapper.toggleClass("mobile-menu-active");
  });

  $("#rightSideTrigger").on("click", function () {
    $(".right-side-content").toggleClass("active");
  });

  sideMenuArea.on("mouseenter", function () {
    pageWrapper.addClass("sidemenu-hover-active");
    pageWrapper.removeClass("sidemenu-hover-deactive");
  });

  sideMenuArea.on("mouseleave", function () {
    pageWrapper.removeClass("sidemenu-hover-active");
    pageWrapper.addClass("sidemenu-hover-deactive");
  });

  // :: Setting Panel Active Code
  $("#settingTrigger").on("click", function () {
    $(".choose-layout-area").toggleClass("active");
  });

  $("#settingCloseIcon").on("click", function () {
    $(".choose-layout-area").removeClass("active");
  });

  $("#quicksettingTrigger").on("click", function () {
    $(".quick-settings-panel").toggleClass("active");
  });

  $("#quicksettingCloseIcon").on("click", function () {
    $(".quick-settings-panel").removeClass("active");
  });

  // :: Dark Switch Active Code
  const darkModeBtn = $(".action-dark");
  const body = $("body");
  const lightIcon = $(".icon-light");
  const darkIcon = $(".icon-dark");

  function setActiveMode(button) {
    $(".light-action, .dark-action").removeClass("active-mode");
    button.addClass("active-mode");
  }

  darkModeBtn.click(function () {
    const isDark = body.attr("data-bs-theme") === "dark";

    // Toggle theme
    body.attr("data-bs-theme", isDark ? "light" : "dark");
    localStorage.setItem("theme", isDark ? "light" : "dark");

    // Toggle icons
    darkIcon.toggle(!isDark);
    lightIcon.toggle(isDark);

    // Set active mode
    setActiveMode(isDark ? $(".light-action") : $(".dark-action"));
  });
  // Load theme from localStorage on page load
  const savedTheme = localStorage.getItem("theme") || "light";
  body.attr("data-bs-theme", savedTheme);

  // Set initial icons and active mode based on saved theme
  if (savedTheme === "dark") {
    darkIcon.show();
    lightIcon.hide();
    setActiveMode($(".dark-action"));
  } else {
    lightIcon.show();
    darkIcon.hide();
    setActiveMode($(".light-action"));
  }

  // Handle light mode action
  $(".light-action").click(function () {
    body.attr("data-bs-theme", "light");
    localStorage.setItem("theme", "light");
    setActiveMode($(".light-action"));
    lightIcon.show();
    darkIcon.hide();
  });

  // Handle dark mode action
  $(".dark-action").click(function () {
    body.attr("data-bs-theme", "dark");
    localStorage.setItem("theme", "dark");
    setActiveMode($(".dark-action"));
    darkIcon.show();
    lightIcon.hide();
  });

  // :: Popover Active Code
  if ($.fn.popover) {
    $('[data-toggle="popover"]').popover();
  }

  // :: Nice Select Active Code
  if ($.fn.niceSelect) {
    $("select").niceSelect();
  }

  // :: Active tooltip Code
  const tooltipTriggerList = document.querySelectorAll(
    '[data-bs-toggle="tooltip"]'
  );
  const tooltipList = [...tooltipTriggerList].map(
    (tooltipTriggerEl) => new bootstrap.Tooltip(tooltipTriggerEl)
  );

  // :: Popover Code
  const popoverTriggerList = document.querySelectorAll(
    '[data-bs-toggle="popover"]'
  );
  const popoverList = [...popoverTriggerList].map(
    (popoverTriggerEl) => new bootstrap.Popover(popoverTriggerEl)
  );

  // :: PreventDefault a Click
  $('a[href="#"]').on("click", function ($) {
    $.preventDefault();
  });

  // :: Preloader Active Code
  flapt_window.on("load", function () {
    $("#preloader").fadeOut("slow", function () {
      $(this).remove();
    });
  });
})(jQuery);
