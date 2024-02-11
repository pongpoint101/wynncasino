const navbar = document.querySelector('.navbar');
window.onscroll = () => {
    if (window.scrollY > 100) {
        navbar.classList.add('scroll');
    } else {
        navbar.classList.remove('scroll');
    }
};