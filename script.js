// Mouse light effect
document.addEventListener('DOMContentLoaded', function() {
    // Create mouse light element
    const mouseLight = document.createElement('div');
    mouseLight.className = 'mouse-light';
    document.body.appendChild(mouseLight);
    
    // Mouse move event
    document.addEventListener('mousemove', function(e) {
        mouseLight.style.left = e.clientX + 'px';
        mouseLight.style.top = e.clientY + 'px';
    });
    
    // Mobile menu toggle
    const hamburger = document.querySelector('.hamburger');
    const navMenu = document.querySelector('.nav-menu');
    
    if (hamburger) {
        hamburger.addEventListener('click', function() {
            hamburger.classList.toggle('active');
            navMenu.classList.toggle('active');
        });
    }
    
    // Close mobile menu when clicking on links
    const navLinks = document.querySelectorAll('.nav-link');
    navLinks.forEach(link => {
        link.addEventListener('click', function() {
            hamburger.classList.remove('active');
            navMenu.classList.remove('active');
        });
    });
    
    // Donation buttons
    const donateButtons = document.querySelectorAll('.donate-btn');
    donateButtons.forEach(button => {
        button.addEventListener('click', function() {
            const cause = this.getAttribute('data-cause');
            alert(`Redirecting to donation for: ${cause}`);
            // Redirect to donation page or show modal
            window.location.href = 'donate.php?cause=' + cause;
        });
    });
    
    // Smooth scrolling for navigation links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });
});