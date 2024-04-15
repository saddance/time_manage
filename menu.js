document.querySelector('.toggle-button').addEventListener('click', function() {
    document.querySelector('.left-menu').classList.toggle('active');
    document.querySelector('.header').style.marginLeft = document.querySelector('.left-menu').classList.contains('active') ? '0' : '250px';
});