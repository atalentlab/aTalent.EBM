export function init() {


    $('.js-tab-item').on('click', function () {
        let purple = 'purple';
        let purpleMedium = 'purple-medium';
        let purpleLight = 'purple-light';

        var type = $(this).data('type');

        $('.js-slider-tab-purple-medium').slick('slickPause');
        $('.js-slider-tab-purple-light').slick('slickPause');
        $('.js-slider-tab-purple').slick('slickPause');


        //slider main container
        $('.js-tab-content-container' ).removeClass('active');
        $('.js-tab-content-container .tab-slider-section' ).removeClass('active');
        $('.js-tab-content-container.'+type ).addClass('active');
        $('.js-tab-content-container.'+type+ ' .tab-slider-section' ).addClass('active');

        //slider nav container
        $('.slider-nav').removeClass('active');
        $('.js-slider-nav-'+type).addClass('active');

        //sliders
        if(type == purpleLight) {

            $('.js-tab-content-container__overlay .'+purpleMedium ).removeClass('hide');
            $('.js-tab-content-container__overlay .'+purpleLight ).removeClass('hide');

            $('.js-tab-content-container__overlay .'+purpleMedium ).addClass('active');
            $('.js-tab-content-container__overlay .'+purpleLight ).addClass('active');
        }

        if(type == purpleMedium) {
            $('.js-tab-content-container__overlay .'+purpleMedium).addClass('active');
            $('.js-tab-content-container__overlay .'+purpleMedium ).removeClass('hide');

            $('.js-tab-content-container__overlay .'+purpleLight).removeClass('active');
            $('.js-tab-content-container__overlay .'+purpleLight ).addClass('hide');
        }

        if(type == purple) {
            $('.js-tab-content-container__overlay .'+purpleMedium ).removeClass('active');
            $('.js-tab-content-container__overlay .'+purpleLight ).removeClass('active');
            $('.js-tab-content-container__overlay .'+purpleMedium ).addClass('hide');
            $('.js-tab-content-container__overlay .'+purpleLight ).addClass('hide');
        }
           // $('.js-tab-content-container.'+purpleLight ).addClass('active');


        $('.js-slider-tab-'+type).slick('slickPlay');
    });


    if ($(".js-slider-tab-purple").length > 0) {

        // $('.js-slider-tab-purple-medium').slick('slickPause');
        // $('.js-slider-tab-purple-light').slick('slickPause');

        initSlick($('.js-slider-tab-purple'), $('.js-slider-nav-purple'));
        initSlick($('.js-slider-tab-purple-medium'), $('.js-slider-nav-purple-medium'));
        initSlick($('.js-slider-tab-purple-light'), $('.js-slider-nav-purple-light'));

        $('.js-tab-content-container.purple .tab-slider-section' ).addClass('active');

        $('.js-slider-tab-purple-medium').slick('slickPause');
        $('.js-slider-tab-purple-light').slick('slickPause');


    }

    function initSlick(elem, sliderNav){
        $(elem).slick({
            dots: true,
            customPaging: function(slider, i) {
                return '<span class="dot"></span>';
            },
            appendDots: sliderNav,
            slidesToShow: 1,
            slidesToScroll: 1,
            autoplay: true,
            autoplaySpeed: 5000,
            infinite: true,
            speed: 500,
            fade: true,
            cssEase: 'linear',
            prevArrow: '.slider-nav--left',
            nextArrow: '.slider-nav--right',
            pauseOnHover: true,
            adaptiveHeight: true,
            responsive: [
                {
                    breakpoint: 767,
                    settings: {
                        dots: false,
                        adaptiveHeight: false,
                    }
                }
                ]
        });

    }



}