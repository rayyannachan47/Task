const progress = document.getElementById("progress");
const prev = document.getElementById("prev");
const next = document.getElementById("next");
const circles = document.querySelectorAll(".circle");
const slidePage = document.querySelector(".slidepage");
const forms = document.querySelectorAll(".form");
const headers = document.querySelectorAll(".header p");

let currentActive = 1;

$(".show").show();
$(".hide").hide();

next.addEventListener("click", () => {
  currentActive++;

  if (currentActive > circles.length) {
    currentActive = circles.length;
  }
  console.log(currentActive);
  update();
});

prev.addEventListener("click", () => {
  currentActive--;

  if (currentActive < 1) {
    currentActive = 1;
  }

  update();
});

$(document).ready(function () {
  $(".submit").click(function () {
    $("#" + currentActive).submit();
  });
  currentActive = window.location.hash.substr(1);
  if (currentActive < 1) {
    currentActive = 1;
  } else if (currentActive > circles.length) {
    currentActive = circles.length;
  }
  update();
});

function update() {
  window.location.hash = currentActive;
  circles.forEach((circle, idx) => {
    if (idx < currentActive) {
      circle.classList.add("active");
    } else {
      circle.classList.remove("active");
    }
  });

  headers.forEach((header, idx) => {
    if (idx < currentActive) {
      header.classList.add("selected");
    } else {
      header.classList.remove("selected");
    }
  });

  forms.forEach((form, idx) => {
    if (idx + 1 == currentActive) {
      $(form).show();
    } else {
      $(form).hide();
    }
  });

  const actives = document.querySelectorAll(".circle.active");
  console.log("actives", actives);
  console.log("circles", circles);
  progress.style.width = (((actives.length - 1) / (circles.length - 1)) * 95) + "%";
  // page.style.marginLeft = (actives.length-1) / (circles.length-1) * 100 + '%';

  if (currentActive == 1) {
    prev.disabled = true;
    next.disabled = false;
  } else if (currentActive == circles.length) {
    next.disabled = true;
    prev.disabled = false;
  } else {
    prev.disabled = false;
    next.disabled = false;
  }
}
