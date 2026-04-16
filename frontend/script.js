// ✅ Safe menu toggle
const toggle = document.querySelector(".menu-toggle");
const navLinks = document.querySelector(".nav-links");

if (toggle && navLinks) {
    toggle.addEventListener("click", () => {
        navLinks.classList.toggle("active");
    });
}

// ✅ Testimonial slider
const slides = document.querySelectorAll(".slide");
const dotsContainer = document.querySelector(".dots");

if (slides.length > 0 && dotsContainer) {
    let index = 0;

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

    function autoSlide() {
        index++;
        if (index >= slides.length) index = 0;
        showSlide(index);
    }

    setInterval(autoSlide, 3000);
    showSlide(0);
}
// wishlist
document.addEventListener("DOMContentLoaded", function () {
    const wishlistButtons = document.querySelectorAll(".wishlist-btn");

    wishlistButtons.forEach(button => {
        button.addEventListener("click", function (e) {
            e.preventDefault();

            const productId = this.getAttribute("data-id");
            const currentBtn = this;
            const wishlistCard = this.closest(".wishlist-card");

            fetch("../backend/add_to_wishlist.php", {
                method: "POST",
                headers: {
                    "Content-Type": "application/x-www-form-urlencoded"
                },
                body: "product_id=" + encodeURIComponent(productId)
            })
            .then(response => response.text())
            .then(data => {
                data = data.trim();

                if (data === "login") {
                    window.location.href = "login.php";
                } 
                else if (data === "added") {
                    currentBtn.classList.add("active");
                    currentBtn.innerHTML = "♥";
                } 
                else if (data === "removed") {
                    if (wishlistCard) {
                        wishlistCard.remove();

                        const grid = document.querySelector(".wishlist-grid");
                        if (grid && grid.children.length === 0) {
                            grid.innerHTML = '<div class="empty-msg">Your wishlist is empty.</div>';
                        }
                    } else {
                        currentBtn.classList.remove("active");
                        currentBtn.innerHTML = "♡";
                    }
                } 
                else {
                    console.log("Unexpected response:", data);
                }
            })
            .catch(error => {
                console.log("Wishlist Error:", error);
            });
        });
    });
});