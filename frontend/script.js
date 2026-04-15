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

    document.querySelectorAll('.wishlist-btn').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();

            let id = this.getAttribute('data-id');
            let button = this;

            fetch('../backend/add_to_wishlist.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: 'id=' + id
            })
            .then(res => res.text())
            .then(data => {

                if (data === 'login') {
                    window.location.href = 'login.php';
                }

                if (data === 'added') {
                    button.innerHTML = '♥';
                    button.classList.add('active');
                }

                if (data === 'removed') {
                    button.innerHTML = '♡';
                    button.classList.remove('active');
                }
            });
        });
    });

});