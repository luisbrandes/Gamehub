const imagensBanner = [
    'img/BlackMith-banner.png',
    'img/fortinite-banner.png',
    'img/Its-take-two-banner.png',
    'img/fall-guys-banner.png'
];

const buttons = document.querySelectorAll('.banner-button');
let indiceImagem = 0;
let imagemAtual = imagensBanner[indiceImagem];
const banner = document.getElementById('banner');
const bannerImg = banner.querySelector('img');
let intervaloTroca;

function trocaAutomaticaBanner() {
    intervaloTroca = setInterval(() => {
        indiceImagem++;
        if (indiceImagem >= imagensBanner.length) {
            indiceImagem = 0;
        }
        trocarBanner(indiceImagem, true);
    }, 5000);
}

function trocarBanner(index, isAutomatic = false) {
    if (isAutomatic) {
        bannerImg.classList.remove('show');
        setTimeout(() => {
            bannerImg.src = imagensBanner[index];
            bannerImg.alt = `Banner ${index + 1}`;
            bannerImg.classList.add('show');
            atualizarBotoes(index);
        }, 1000);
    } else {
        bannerImg.classList.remove('show');
        setTimeout(() => {
            bannerImg.src = imagensBanner[index];
            bannerImg.alt = `Banner ${index + 1}`;
            bannerImg.classList.add('show');
            atualizarBotoes(index);
        }, 100);
    }

    imagemAtual = imagensBanner[index];
}

function atualizarBotoes(index) {
    buttons.forEach(button => {
        button.classList.remove('active');
    });
    buttons[index].classList.add('active');
}

buttons.forEach((button, index) => {
    button.addEventListener('click', () => {
        clearInterval(intervaloTroca);
        trocarBanner(index, false);
        indiceImagem = index;
        trocaAutomaticaBanner();
    });
});

document.addEventListener('DOMContentLoaded', function() {
    trocarBanner(indiceImagem, true);
    trocaAutomaticaBanner();
});
