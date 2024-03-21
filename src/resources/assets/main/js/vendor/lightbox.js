$.fn.lightboxLite = function(options) {

  var $element = $(this);
  // default options
  var settings = $.extend({
    openTarget:  $element,
    size: 'large',
    lightbox: '.js-lightbox-lite',
    afterOpen: function() {},
    afterClose: function() {}
  }, options);

  var $lightbox = $(settings.lightbox);
  var $contentPlaceholder = $lightbox.find('.js-lightbox-lite-content-placeholder');

  var sizeClass = '';

  switch(settings.size) {
    case 'large':
    sizeClass = 'lightbox-lite__box--l';
    break;
    case 'medium':
    sizeClass = 'lightbox-lite__box--m';
    break;
    case 'small':
    sizeClass = 'lightbox-lite__box--s';
    break;
  }


  // close
  var lightboxClose = function() {
    $('body').focus(); // Firefox fix
    $('body').removeClass('lightbox-lite-open');
    $lightbox.removeClass('lightbox-lite-box-open');
    setTimeout(function() {
      // $lightbox.find('.js-lightbox-lite-content-placeholder').html('<div class="lightbox-lite__box__loading"></div>');
      if (sizeClass) $lightbox.find('.lightbox-lite__box').removeClass(sizeClass);
      setState('normal');
      setFeedback('');
      $lightbox.trigger('lightboxLite:close');
    }, 500); // compensate for CSS animation delay
    settings.afterClose();
  };

  // open
  var lightboxOpen = function() {
    lightboxCloseAllBoxes();
    $('body').addClass('lightbox-lite-open');
    $lightbox.addClass('lightbox-lite-box-open');
    if (sizeClass) $lightbox.find('.lightbox-lite__box').addClass(sizeClass);
    $('body').focus(); // IE fix
    settings.afterOpen();
  };

  var lightboxAddContent = function (content) {
    $contentPlaceholder.html(content);
  };

  var lightboxCloseAllBoxes = function() {
    $('.js-lightbox-lite').removeClass('lightbox-lite-box-open');
  };

  var setState = function(state) {
    switch(state) {
      case 'loading':
      $lightbox.removeClass('has-feedback');
      $lightbox.addClass('is-loading');
      break;
      case 'normal':
      $lightbox.removeClass('is-loading has-feedback');
      break;
      case 'feedback':
      $lightbox.removeClass('is-loading');
      $lightbox.addClass('has-feedback');
      $lightbox.find('.lightbox-lite__box').scrollTop(0);
      break;
    }
  };

  var setFeedback = function(feedback) {
    $lightbox.find('.js-lightbox-lite-feedback').html(feedback);
  };

  // clicking on close button
  $lightbox.find('.js-lightbox-lite-close').on('click', function(e) {
    e.preventDefault();
    e.stopPropagation();
    lightboxClose();
  });

  // pressing esc key
    if($('.js-welcome-lightbox').length <= 0) { //prevent lightbox close for welcome form
        $(document).keydown(function (e) {
            if (e.keyCode == 27) {
                e.preventDefault();
                e.stopPropagation();
                lightboxClose();
            }
        });
    }

  $(document).on('click', function(){
//    lightboxClose();
  });

  /*$lightbox.find('.lightbox-lite__box').on('click', function(e) {
    e.stopPropagation();
  });*/

  // clicking on open link (openTarget)
  if (settings.openTarget != 'none') {
    settings.openTarget.on('click', function(e) {
      e.preventDefault();
      e.stopPropagation();
      lightboxOpen();
    });
  }

  // make methods available
  return {
    open: function() {
      lightboxOpen();
      return $element;
    },
    close: function() {
      lightboxClose();
      return $element;
    },
    addContent: function(content) {
      lightboxAddContent(content);
      return $element;
    },
    getBox: function() {
      return $lightbox;
    },
    setState: function(state) {
      setState(state);
      return $element;
    },
    setFeedback: function(feedback) {
      setFeedback(feedback);
      return $element;
    }

  }

};