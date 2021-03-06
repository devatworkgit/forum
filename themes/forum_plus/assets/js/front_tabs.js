(function ($) {
  
  Drupal.behaviors.front_tabs = {
    attach: function (context) {
      
      $('body.path-frontpage #block-forum-plus-content, body.path-frontpage #block-views-block-q-a-block-1, body.path-frontpage #block-views-block-blogs-block-1').addClass('hidden');
      
      if ($(location).attr('hash') == '' && $('#block-fronttabs').length) {
        $('a[href="/#forum"], a[href="/#q&a"], a[href="/#blogs"]').each( function(index) {
          if ($(this).attr('href') == '/#forum') {
            if (index == 0) {
              $('#block-forum-plus-content').removeClass('hidden');
            }
            if(!$('#block-forum-plus-content').hasClass('hidden')) {
              $(this).addClass('active');
            }
          } else if ($(this).attr('href') == '/#q&a') {
            if (index == 0) {
              $('#block-views-block-q-a-block-1').removeClass('hidden');
            }
            if(!$('#block-views-block-q-a-block-1').hasClass('hidden')) {
              $(this).addClass('active');
            }
          } else if ($(this).attr('href') == '/#blogs') {
            if (index == 0) {
              $('#block-views-block-blogs-block-1').removeClass('hidden');
            }
            if(!$('#block-views-block-blogs-block-1').hasClass('hidden')) {
              $(this).addClass('active');
            }
          }
        });
      }
      
      if ($(location).attr('hash') == '#forum') {
        $('#block-forum-plus-content').removeClass('hidden');
        $('a[href="/#forum"]').addClass('active');
      } else if ($(location).attr('hash') == '#q&a') {
        $('#block-views-block-q-a-block-1').removeClass('hidden');
        $('a[href="/#q&a"]').addClass('active');
      } else if ($(location).attr('hash') == '#blogs') {
        $('#block-views-block-blogs-block-1').removeClass('hidden');
        $('a[href="/#blogs"]').addClass('active');
      }
      
      $('a[href="/#forum"], a[href="/#q&a"], a[href="/#blogs"]').click( function(event) {
        if ($('body').hasClass('front')) {
          event.preventDefault();
        }
        $('a[href="/#forum"], a[href="/#q&a"], a[href="/#blogs"]').removeClass('active');
        $('#block-forum-plus-content, #block-views-block-q-a-block-1, #block-views-block-blogs-block-1').addClass('hidden');
        if ($(this).attr('href') == '/#forum') {
          $('#block-forum-plus-content').removeClass('hidden');
          $('a[href="' + $(this).attr('href') + '"]').addClass('active');
        } else if ($(this).attr('href') == '/#q&a') {
          $('#block-views-block-q-a-block-1').removeClass('hidden');
          $('a[href="' + $(this).attr('href') + '"]').addClass('active');
        } else if ($(this).attr('href') == '/#blogs') {
          $('#block-views-block-blogs-block-1').removeClass('hidden');
          $('a[href="' + $(this).attr('href') + '"]').addClass('active');
        }
      })
    }
  }

})(jQuery);