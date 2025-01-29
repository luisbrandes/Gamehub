$(document).ready(function () {
    const imagensBanner = [
        'img/BlackMith-banner.png',
        'img/fortinite-banner.png',
        'img/Its-take-two-banner.png',
        'img/fall-guys-banner.png'
    ];

    const $buttons = $('.banner-button');
    let indiceImagem = 0;
    let imagemAtual = imagensBanner[indiceImagem];
    const $bannerImg = $('#banner img');
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
        $bannerImg.removeClass('show');
        setTimeout(() => {
            $bannerImg.attr('src', imagensBanner[index])
                .attr('alt', `Banner ${index + 1}`)
                .addClass('show');
            atualizarBotoes(index);
        }, isAutomatic ? 1000 : 100);

        imagemAtual = imagensBanner[index];
    }

    function atualizarBotoes(index) {
        $buttons.removeClass('active');
        $buttons.eq(index).addClass('active');
    }

    $buttons.each(function (index) {
        $(this).on('click', function () {
            clearInterval(intervaloTroca);
            trocarBanner(index, false);
            indiceImagem = index;
            trocaAutomaticaBanner();
        });
    });

    trocarBanner(indiceImagem, true);
    trocaAutomaticaBanner();

    const $dev = $('#footer');
    const $hover = $('#pop-up');

    $dev.on('mouseover', function () {
        $hover.css('display', 'inline-block');
    });

    $dev.on('mouseout', function () {
        $hover.css('display', 'none');
    });
});
