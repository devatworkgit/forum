(function($) {

  Drupal.ForumPlus = Drupal.ForumPlus || {};

  Drupal.behaviors.actionForumPlus = {
    attach: function(context) {
      $('.btn-btt').smoothScroll({ speed: 300 });
      $('.forum-jump-links a').smoothScroll({ speed: 300 });
      $('.bootstrap-slideshow').carousel();
      Drupal.ForumPlus.setInputPlaceHolder('keys', 'Keywords', '#search-block-form');
      Drupal.ForumPlus.moveComments();
      Drupal.ForumPlus.moveSearch();
      Drupal.ForumPlus.initFloatPanel();
      Drupal.ForumPlus.extendDefaultMenu();
      Drupal.ForumPlus.mobileMenu();

      $('.three-column .view-col-inner').matchHeight(true);
      $('.two-column .view-col-inner').matchHeight(true);

      $(window).resize(function() {
        Drupal.ForumPlus.checkScroll();
      })
    }
  };

  Drupal.ForumPlus.setInputPlaceHolder = function(name, text, selector) {
    selector = selector == undefined ? '' : selector + ' ';

    if ($.support.placeholder) {
      $(selector + 'input[name="' + name + '"]').attr('placeholder', Drupal.t(text));
    } else {
      $(selector + 'input[name="' + name + '"]').val(Drupal.t(text));
      $(selector + 'input[name="' + name + '"]').focus(function() {
        if (this.value == Drupal.t(text)) {
          this.value = '';
        }
      }).blur(function() {
        if (this.value == '') {
          this.value = Drupal.t(text);
        }
      });
    }
  }

  Drupal.ForumPlus.moveComments = function() {
    if ($('article').hasClass('node-product')) {
      $('#comments').appendTo('.group-reviews');
      $('.group-reviews').css('height', '');
      $('.group-reviews').find('.field-name-field-status').hide();
    }
  }

  Drupal.ForumPlus.moveSearch = function() {
    $('#block-search-form .content').appendTo($('#forum-search .container'));
    $('#block-search-form').click(function() {
      if ($('#forum-search').hasClass('open')) {
        $('#forum-search').removeClass('open');
      } else {
        $('#forum-search').addClass('open');
      }
    })
  }

  Drupal.ForumPlus.showSidebar = function() {
    this.sidebarButton.removeClass('close');
    this.sidebarFirst.css({
      'opacity': '1',
      'display': 'block'
    });
    this.mainSection.addClass('has-sidebar');
    this.mainArea.removeClass('col-lg-12').addClass('col-lg-9');
    this.mainArea.removeClass('col-md-12').addClass('col-md-9');
  }

  Drupal.ForumPlus.initFloatPanel = function() {
    var self = this;
    if ($('#sidebar-first').hasClass('sidebar') || $('body').hasClass('page-blogs') || $('body').hasClass('page-blog') || $('body').hasClass('node-type-blog')) {
      self.sidebarButton = $('<a class="button-sidebar-link" href=""></a>');
      self.sidebarFirst = $('#sidebar-first');
      self.mainSection = $('#main');
      self.mainArea = $('#main-area');

      self.sidebarFirst.after(self.sidebarButton);
      self.mainSection.addClass('has-sidebar');
      self.sidebarButton.click(function() {
        if (self.mainSection.hasClass('has-sidebar')) {
          self.sidebarButton.addClass('close');
          self.sidebarFirst.css({
            'opacity': '0',
            'display': 'none'
          });
          self.mainSection.removeClass('has-sidebar');
          self.mainArea.removeClass('col-lg-9').addClass('col-lg-12');
          self.mainArea.removeClass('col-md-9').addClass('col-md-12');
        } else {
          self.showSidebar();
        }
        return false;
      })
    }
  }

  Drupal.ForumPlus.checkScroll = function() {
    if ($('.button-sidebar-link').css('display') == 'none') {
      this.showSidebar();
    }
  }

  Drupal.ForumPlus.extendDefaultMenu = function() {
    jQuery(".header .navbar .block .content > ul > li").hover(
      function() {
        jQuery(".header .navbar .block .content > ul > li.active-trail")
          .removeClass('active-trail')
          .addClass('active-trail-off');
      },
      function() {
        jQuery(".header .navbar .block .content > ul > li.active-trail-off")
          .removeClass('active-trail-off')
          .addClass('active-trail');
      }
    );
  }

  Drupal.ForumPlus.mobileMenu = function() {
    $('.navbar-toggle').mobileMenu({
      targetWrapper: '#main-menu'
    });
  }

  $.support.placeholder = (function() {
    var i = document.createElement('input');
    return 'placeholder' in i;
  })();

})(jQuery);
