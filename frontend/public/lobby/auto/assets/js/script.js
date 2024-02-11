var $gridlayout=undefined; 
var $qsRegexisotope=undefined; 
(function (){

    'use strict'; 
        $gridlayout = $('.projects').isotope({
        itemSelector: '.item',
        layoutMode: 'fitRows',
        filter: function() {
          return $qsRegexisotope ? $(this).text().match( $qsRegexisotope ) : true;
        }
      });
      // use value of search field to filter
    var $quicksearch = $('.input_searchgames').keyup( debounce( function() { 
        $qsRegexisotope = new RegExp( $quicksearch.val(), 'gi' );
        $gridlayout.isotope();
    }, 200 ) );
    
    // debounce so filtering doesn't happen every millisecond
    function debounce( fn, threshold ) {
        var timeout;
        threshold = threshold || 100;
        return function debounced() {
        clearTimeout( timeout );
        var args = arguments;
        var _this = this;
        function delayed() {
            fn.apply( _this, args );
        }
        timeout = setTimeout( delayed, threshold );
        };
    }
    /*
    var $projects = $('.projects'); 
    $projects.isotope({
        itemSelector: '.item',
        layoutMode: 'fitRows'
    }); 
    $('ul.filters > li').on('click', function(e){ 
        e.preventDefault(); 
        var filter = $(this).attr('data-filter'); 
        $('ul.filters > li').removeClass('active');
        $(this).addClass('active'); 
        $projects.isotope({filter: filter}); 
    });
  */
    $('.card').mouseenter(function(){ 
        $(this).find('.card-overlay').css({'top': '-100%'});
        $(this).find('.card-hover').css({'top':'0'}); 
    }).mouseleave(function(){ 
        $(this).find('.card-overlay').css({'top': '0'});
        $(this).find('.card-hover').css({'top':'100%'}); 
    });

})(jQuery);

function setCookie(cname, cvalue, hourex) {
    const d = new Date();
    d.setTime(d.getTime() + hourex);
    let expires = "expires="+d.toUTCString();
    document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
  } 
  function getCookie(cname) {
    let name = cname + "=";
    let ca = document.cookie.split(';');
    for(let i = 0; i < ca.length; i++) {
      let c = ca[i];
      while (c.charAt(0) == ' ') {
        c = c.substring(1);
      }
      if (c.indexOf(name) == 0) {
        return c.substring(name.length, c.length);
      }
    }
    return "";
  }
