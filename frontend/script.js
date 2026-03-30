const toggle = document.querySelector(".menu-toggle");
const navLinks = document.querySelector(".nav-links");

toggle.addEventListener("click", () => {
  navLinks.classList.toggle("active");
});
const slides = document.querySelectorAll(".slide");
const dotsContainer = document.querySelector(".dots");

let index = 0;

// create dots
slides.forEach((_, i) => {
    let dot = document.createElement("span");
    dot.addEventListener("click", () => showSlide(i));
    dotsContainer.appendChild(dot);
});

const dots = document.querySelectorAll(".dots span");

function showSlide(i) {
    slides.forEach(slide => slide.classList.remove("active"));
    dots.forEach(dot => dot.classList.remove("active"));

    slides[i].classList.add("active");
    dots[i].classList.add("active");
    index = i;
}

// auto slide
function autoSlide() {
    index++;
    if (index >= slides.length) index = 0;
    showSlide(index);
}

setInterval(autoSlide, 3000);

// first load
showSlide(0);

let productSlides = [
  "<?php echo $product['image']; ?>",
  "<?php echo $product['image2']; ?>",
  "<?php echo $product['image3']; ?>",
  "<?php echo $product['video']; ?>"
];

let productIndex = 0;

function showProductSlide(i) {
  let el = document.getElementById("mainSlide");

  if (productSlides[i].endsWith(".mp4")) {
    el.outerHTML = `<video id="mainSlide" controls autoplay>
      <source src="../videos/${productSlides[i]}" type="video/mp4">
    </video>`;
  } else {
    el.outerHTML = `<img id="mainSlide" src="../images/${productSlides[i]}">`;
  }
}

function nextSlide() {
  productIndex = (productIndex + 1) % productSlides.length;
  showProductSlide(productIndex);
}

function prevSlide() {
  productIndex = (productIndex - 1 + productSlides.length) % productSlides.length;
  showProductSlide(productIndex);
}

document.addEventListener("DOMContentLoaded", function () {
    document.querySelectorAll(".wishlist").forEach(function(button) {
        button.addEventListener("click", function () {
            let productId = this.getAttribute("data-id");
            let btn = this;

            fetch("add-wishlist.php", {
                method: "POST",
                headers: {
                    "Content-Type": "application/x-www-form-urlencoded"
                },
                body: "id=" + encodeURIComponent(productId)
            })
            .then(response => response.text())
            .then(data => {
                if (data.trim() === "login") {
                    window.location.href = "login.php";
                } 
                else if (data.trim() === "added") {
                    btn.innerHTML = "♥";
                    btn.classList.add("active");
                } 
                else if (data.trim() === "removed") {
                    btn.innerHTML = "♡";
                    btn.classList.remove("active");
                } 
                else {
                    console.log(data);
                }
            });
        });
    });
});


