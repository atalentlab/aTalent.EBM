
export function init() {
    // anime({
    //     targets: '.anime',
    //     translateX: 250,
    //     direction: 'reverse',
    //     easing: 'easeInOutSine',
    // });


    // var colorsExamples = anime.timeline({
    //     endDelay: 1000,
    //     easing: 'easeInOutQuad',
    //     direction: 'alternate',
    //     loop: true
    // })
    //     .add({ targets: '.animeDiv',  background: '' }, 0)
    //     .add({ targets: '.color-rgb',  background: 'rgb(255,255,255)' }, 0)

    // var tl2 = anime.timeline({
    //     targets: '.tab-container__inner',
    //     direction: 'reverse',
    //     easing: 'easeInOutQuad',
    //     duration: 500,
    //     delay: function(el, i) { return 1000 }
    // });
    //
    // tl2.add({
    //     translateX: 250,
    //     translateY: 0,
    // });


    $('.ml12 .letters').each(function(){
        $(this).html($(this).text().replace(/([^\x00-\x80]|\w)/g, "<span class='letter'>$&</span>"));
    });



    anime.timeline({loop: true})
        .add({
            targets: '.ml9 .letter',
            scale: [0, 1],
            duration: 1500,
            elasticity: 600,
            delay: function(el, i) {
                return 45 * (i+1)
            }
        }).add({
        targets: '.ml9',
        opacity: 0,
        duration: 1000,
        easing: "easeOutExpo",
        delay: 1000
    });


    var letterAnimate = function() {
        anime.timeline({loop: false})
            .add({
                targets: '.js-success-box .ml12 .letter',
                translateX: [40,0],
                translateZ: 0,
                opacity: [0,1],
                easing: "easeOutExpo",
                duration: 1200,
                delay: function(el, i) {
                    return 500 + 30 * i;
                }
            });
    }


}