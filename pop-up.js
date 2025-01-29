let dev = document.querySelector('#footer');
let hover = document.querySelector('#pop-up');

dev.addEventListener('mouseover', function() {
    hover.style.display = 'inline-block';
});
dev.addEventListener('mouseout', function() {
    hover.style.display = 'none';
});