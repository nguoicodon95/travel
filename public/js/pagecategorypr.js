var FilterJs = function () {
    var updateQueryStringParameter = function (uri, key, value) {
      var re = new RegExp("([?&])" + key + "=.*?(&|$)", "i");
      var separator = uri.indexOf('?') !== -1 ? "&" : "?";
      if (uri.match(re)) {
        return uri.replace(re, '$1' + key + "=" + value + '$2');
      }
      else {
        return uri + separator + key + "=" + value;
      }
    }

    var getUrlParameter = function getUrlParameter(sParam) {
       var sPageURL = decodeURIComponent(window.location.search.substring(1)),
           sURLVariables = sPageURL.split('&'),
           sParameterName,
           i;

       for (i = 0; i < sURLVariables.length; i++) {
           sParameterName = sURLVariables[i].split('=');

           if (sParameterName[0] === sParam) {
               return sParameterName[1] === undefined ? true : sParameterName[1];
           }
       }
   }

   function removeParam(key, sourceURL) {
      var rtn = sourceURL.split("?")[0],
          param,
          params_arr = [],
          queryString = (sourceURL.indexOf("?") !== -1) ? sourceURL.split("?")[1] : "";
      if (queryString !== "") {
          params_arr = queryString.split("&");
          for (var i = params_arr.length - 1; i >= 0; i -= 1) {
              param = params_arr[i].split("=")[0];
              if (param === key) {
                  params_arr.splice(i, 1);
              }
          }
          rtn = rtn + "?" + params_arr.join("&");
      }
      return rtn;
  }

  var hs_filter = function () {
      var $col_filter = $('.filterbx');
      var w = $(window).width();
      if (w < 977) {
        swap_columns($col_filter);
        $('#ft-button').removeClass('hidden');
        $('#ft-button').show();

        $('#ft-button').click(function () {
            $col_filter.toggle();
            $col_filter.removeClass('hidden-xs');
        })
      }

      $(window).resize(function() {
          swap_columns($col_filter);
      });
  }

  function swap_columns(col_filter)
  {
      var w = $(window).width();
      if (w < 977)
      {
          $('#ft-button').show();
          $('#ft-button').removeClass('hidden');
          col_filter.hide();
      }
      else
      {
          col_filter.show();
      }
  }

   var Elemchange = function (elm, q) {
        $(elm).on('change', function (event) {
            event.preventDefault();
            var param = '';
            var bind = '?';
            var Crurl = window.location.pathname;
            var value = $(elm + ":checked").map(function(){
                return $(this).val();
            }).get();
            $.each(value, function( i, v ) {
                param += v + ',';
            });
            param = encodeURIComponent(param.substring(0,param.length - 1))
            var row = getUrlParameter(q);

            var query = window.location.search;
            (query == '') ? bind = '?' : bind = '&';
            if(typeof row === "undefined") {
                row =  query + bind + q + '=' + param
            } else {
                row = updateQueryStringParameter(query, q, param)
            }
            if(param === '') row = removeParam(q, query)
            var url = Crurl + row;
            window.location.href = url;
        });
    };

    return {
        Elemchange: function (elm, q) {
            Elemchange(elm, q)
        },
        hs_filter: function () {
            hs_filter()
        }
    }
}();
