const imagensBanner = [
    'img/BlackMith-banner.png',
    'img/fortinite-banner.png',
    'img/Its-take-two-banner.png',
    'img/fall-guys-banner.png'
];

const $buttons = $('.banner-button');
let indiceImagem = 0;
let imagemAtual = imagensBanner[indiceImagem];
const $banner = $('#banner');
const $bannerImg = $banner.find('img');
let intervaloTroca;

function trocaAutomaticaBanner() {
    intervaloTroca = setInterval(function() {
        indiceImagem++;
        if (indiceImagem >= imagensBanner.length) {
            indiceImagem = 0;
        }
        trocarBanner(indiceImagem, true);
    }, 5000);
}

function trocarBanner(index, isAutomatic = false) {
    if (isAutomatic) {
        $bannerImg.removeClass('show');
        setTimeout(function() {
            $bannerImg.attr('src', imagensBanner[index])
                      .attr('alt', `Banner ${index + 1}`);
            $bannerImg.addClass('show');
            atualizarBotoes(index);
        }, 1000);
    } else {
        $bannerImg.removeClass('show');
        setTimeout(function() {
            $bannerImg.attr('src', imagensBanner[index])
                      .attr('alt', `Banner ${index + 1}`);
            $bannerImg.addClass('show');
            atualizarBotoes(index);
        }, 100);
    }

    imagemAtual = imagensBanner[index];
}

function atualizarBotoes(index) {
    $buttons.removeClass('active');
    $buttons.eq(index).addClass('active');
}

$buttons.each(function(index) {
    $(this).on('click', function() {
        clearInterval(intervaloTroca);
        trocarBanner(index, false);
        indiceImagem = index;
        trocaAutomaticaBanner();
    });
});

$(document).ready(function() {
    trocarBanner(indiceImagem, true);
    trocaAutomaticaBanner();
});

