(function($) {

  sliders();
  extendDefaultMenu();
  onFaqTitleClick();
  fixPlaceholder();
  onButtonSidebarClick();
  fixImageSource();
  fixBasePath()



  function fixBasePath() {
    var base_path = drupalSettings.path.baseUrl;
    ($)('.fixed-base-path').each(function(i, e) {
      var self = ($)(this);
      var href = self.attr('href');
      if (href.indexOf('/' == 0)) {
        href = href.slice(1);
      }
      href = base_path + href;
      self.attr('href', href);
    });
  }


  function fixImageSource() {
    var base_path = drupalSettings.path.baseUrl;
    (jQuery)('img').each(function (i, e) {
        var self = (jQuery)(this);
        var src = self.attr('src');
        if (src.indexOf('/sites/default') >= 0) {
            var tmphref = src.split('sites/default')[0];
            src = src.replace(tmphref, base_path);
            self.attr('src', src);
            }
        });
  }

  function sliders() {
    (jQuery)('.product-slideshow').flexslider({
      animation: 'slide',
      selector: '.view-content > .views-row',
      controlNav: false
    });
  }

  function extendDefaultMenu() {
    jQuery("#block-mainnavigation .content > ul.menu > li").hover(
      function() {
        jQuery("#block-mainnavigation .content > ul.menu > li.menu-item--active-trail")
          .removeClass('menu-item--active-trail')
          .addClass('menu-item--active-trail-off');
      },
      function() {
        jQuery("#block-mainnavigation .content > ul.menu > li.menu-item--active-trail-off")
          .removeClass('menu-item--active-trail-off')
          .addClass('menu-item--active-trail');
      }
    );
  }

  function onFaqTitleClick() {
    jQuery('.faq .view-content .views-row .views-field-body').hide();

    jQuery(document).on('click', '.faq .view-content .views-row .views-field-title', function () {
      var self = jQuery(this);
      //jQuery('.faq .view-content .views-row .views-field-body').hide();
      self.next().fadeToggle(200);
    });
  }

  function fixPlaceholder() {
    jQuery('.search-block-form form .form-search').attr('placeholder', Drupal.t('Keywords'));
  }

  function onButtonSidebarClick() {
    var button = jQuery('.button-sidebar-link');
    button.click(function () {
      var sidebar_first_wrapper = jQuery('#sidebar-first-wrapper');
      var main_content_wrapper = jQuery('#main-content-wrapper');

      if (sidebar_first_wrapper.is(':visible')) {
        button.addClass('close');
        sidebar_first_wrapper.hide();
        main_content_wrapper.addClass('col-lg-12');
        main_content_wrapper.addClass('col-md-12');
      } else {
        button.removeClass('close');
        sidebar_first_wrapper.show();
        main_content_wrapper.removeClass('col-lg-12');
        main_content_wrapper.removeClass('col-md-12');
      }

      return false;
    });
  }

})(jQuery);
