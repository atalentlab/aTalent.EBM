export function init() {

    var tabContainerTL;


    var screenSize = $(window).width();

    if(screenSize > 767){
        //initializing about srm section
        initTabSection();
    }

    /* note:- layer tranform will change the position during scroll so need to calculate the section position top by ourself to nav scroll to position
     scroll to position - nav click */

    $('.js-nav-item').on('click', function () {
        var scrollTo = $(this).attr('data-id');
        var scrollToTop = $("#" + scrollTo).offset().top;
        var postion = 100;

        if(screenSize > 767) {
            // if (scrollTo == 'contact-form') {
            //     postion = scrollToTop / 3.2;
            // }
            //
            // if (scrollTo == 'about-srm') {
            //     postion = scrollToTop / 4.5;
            // }

            $('html, body').animate({
                scrollTop: scrollToTop - postion
            }, 600);
        }else{
            $('html, body').animate({
                scrollTop: scrollToTop
            }, 600);
        }
    });


    //layer scroll feature
    $(window).scroll(function(){

    var ws = $(window).scrollTop();

    if(screenSize > 767) {

        $('.layer-dx-3').css({
            '-webkit-transform': `translate(-${ws / 2.5}px,0px)`,
            'transform': `translate(-${ws / 2.5}px, 0)`
        });

        $('.layer4-rev').css({
            '-webkit-transform': `translate(0px, ${ws / 4}px)`,
            'transform': `translate(0px, ${ws / 4}px)`,
        });

        $('.layer1').css({
            '-webkit-transform': `translate(0px, -${ws / 1.8}px)`,
            'transform': `translate(0px, -${ws / 1.8}px)`,
        });

        $('.layer2').css({
            '-webkit-transform': `translate(0px, -${ws / 2.5}px)`,
            'transform': `translate(0px, -${ws / 2.5}px)`,
        });



        $('.layer4').css({
            '-webkit-transform': `translate(0px, -${ws / 4}px)`,
            'transform': `translate(0px, -${ws / 4}px)`,
            'margin-bottom': `-${ws / 4}px`
        });

        $('.layer5').css({
            '-webkit-transform': `translate(0px, -${ws / 5}px)`,
            'transform': `translate(0px, -${ws / 5}px)`,
        });


        $('.layer5-no-margin').css({
            '-ms-transform': `translate(0px, -${ws / 5}px)`,
            '-moz-tansform': `translate(0px, -${ws / 5}px)`,
            '-webkit-transform': `translate(0px, -${ws / 5}px)`,
            'transform': `translate(0px, -${ws / 5}px)`,
            'margin-bottom': `-${ws / 5}px`
        });

        $('.layer6').css({
            '-webkit-transform': `translate(0px, -${ws / 6}px)`,
            'transform': `translate(0px, -${ws / 6}px)`,
            'margin-bottom': `-${ws / 6}px`
        });

        $('.layer10').css({
            '-webkit-transform': `translate(0px, -${ws / 10}px)`,
            'transform': `translate(0px, -${ws / 10}px)`,
            'margin-bottom': `-${ws / 10}px`
        });


        //animate when reach section
        $('.js-animate').each(function() {
            if (isScrolledIntoView(this) === true) {
                console.log('section in view');
                $(this).removeClass('js-animate');
                showTabSection();
            }else{
                initTabSection();
            }
        });

    }

//scroll Top Button Bottom
    if (ws > 500){
            $('.js-scroll-top').fadeIn();
        } else {
            $('.js-scroll-top').fadeOut();
        }

    });


    function isScrolledIntoView(elem) {
        var docViewTop = $(window).scrollTop();
        var docViewBottom = docViewTop + $(window).height();

        var elemTop = $(elem).offset().top;
        var elemBottom = elemTop + $(elem).height();


        if(elemBottom > (elemTop + 300)) { //for big elements
            elemBottom = elemTop + 300;
        }


        return ((elemBottom <= docViewBottom) && (elemTop >= docViewTop));
    }




    $('.js-full-view').click(function() {

        if($('.js-table-container').hasClass('full-screen')){
            $('body').removeClass('no-scroll');
            $('.js-table-container').removeClass('full-screen');
        }else {
            $('body').addClass('no-scroll');
            $('.js-table-container').addClass('full-screen');

            // $('html, body').animate({
            //     scrollTop: 0
            // }, 0);
        }
    });




    function showTabSection() {

        tabContainerTL = anime.timeline({
            direction: 'straight',
        });

        tabContainerTL.add({
            targets: '.tab-container .tab-container__inner',
            translateX: 0,
            duration: 500,
            easing: 'easeInExpo',
        }).add({
            targets: '.tab-container .tab-container__right',
            translateX: 0,
            duration: 250,
            easing: 'easeInExpo',
        }).add({
            targets: '.tab-container .tabs .tab-item',
            translateY: 0,
            duration: 250,
            easing: 'spring',
            delay: function(el, i) { return i * 250 },
        }).add({
            targets: '.tab-container .tab-slider-section',
            translateX: 0,
            opacity:[0,1],
            duration: 600,
            easing: 'easeOutExpo',
        },'-=1000').add({
            targets: '.tab-container .tab-left-logo',
            translateY: 0,
            opacity:[0,1],
            duration: 300,
            easing: 'spring',
        },'-=300')
            .add({
            targets: '.tab-container .slick-dots',
            translateY: 0,
            duration: 300,
            easing: 'spring',
            opacity: 1,
        },'-=1000');
    }


    function initTabSection(){
        tabContainerTL = anime.timeline({
            direction: 'straight',
        });

        tabContainerTL.add({
            targets: '.tab-container .tab-container__right, .tab-container .tab-container__inner',
            translateX: '100%',
            duration: 0,
        }).add({
            targets: '.tab-container .tab-container__inner',
            translateX: '80%',
            duration: 0,
        }).add({
            targets: '.tab-container .tabs .tab-item',
            translateY: '100px',
            duration: 0,
        }).add({
                targets: '.tab-container .tab-left-logo',
                translateY: '100px',
                opacity:[0,1],
                duration: 0
        }).add({
                targets: '.tab-container .tab-slider-section',
                translateX: '100%',
                opacity:[0,1],
                duration: 0
        }).add({
                targets: '.tab-container .slick-dots',
                translateY: -100,
                opacity: 0,
                duration: 0,
        })

        // tabContainerTL.add({
        //     targets: '',
        //     duration: 0,
        //     translateX: '80%',
        //
        // })

    }



}
