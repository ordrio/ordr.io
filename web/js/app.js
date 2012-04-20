/* Foundation v2.2 http://foundation.zurb.com */
jQuery(document).ready(function ( $ ) {

  //$('#options-menu').hide();
  //$('#options-link').show();

  $('#options a').on('click', function () {
    var pm = !$('#options-menu').is(':visible');
    $('#options-menu').slideToggle();
    $('#options span.toggle').html(pm ? 'v' : '&gt;');
  });

  $("#uvTab").addClass("hide-on-phones");

  if ( !Modernizr.input.placeholder ) {
    $("input").each(
      function () {
        if ( $(this).val() == "" && $(this).attr("placeholder") != "" ) {
          $(this).val($(this).attr("placeholder"));
          $(this).addClass("placeholder");
          $(this).focus(function () {
            if ( $(this).val() == $(this).attr("placeholder") ) {
              $(this).val("");
              $(this).removeClass("placeholder");
            }
          });
          $(this).blur(function () {
            if ( $(this).val() == "" ) {
              $(this).val($(this).attr("placeholder"));
              $(this).addClass("placeholder");
            }
          });
        }
      }
    );
  }

  /* ALERT BOXES ------------ */
  $(".alert-box").delegate("a.close", "click", function ( event ) {
    event.preventDefault();
    $(this).closest(".alert-box").fadeOut(function ( event ) {
      $(this).remove();
    });
  });


  if ( ordr.locale !== "en" ) {
    $.datepicker.setDefaults($.datepicker.regional[ordr.locale]);
  }


  $(".input-date").datetimepicker({
    showSecond: true,
    timeFormat: 'hh:mm:ss'
  });

  $('select').chosen();

  var basePath = /app_dev/.test(window.location.href) ? window.location.href : "";

  var sumTop = function () {
    return $("#sum span").offset().top
  };
  var sumLeft = function () {
    return $("#sum span").offset().left + $("#sum span").css("width");
  };

  var hit = function ( sign, amount ) {
    var $amount = $("<div/>").text(sign + amount);

    $("body").append($amount);
    $amount.css({
      "position": "absolute",
      "top": sumTop(),
      "left": sumLeft(),
      "font-size": "16px",
      "font-weight": "bold"
    });

    $amount.animate({top: 0, opacity: 0}, 1000, function () {
      $amount.remove();
    });
  };

  /*$("#revert-ordr").on("click", function () {
   $.ajax({
   url: basePath + "/revert",
   type: "POST",
   beforeSend: function () {
   $('#form').block({
   message: '',
   css: { }
   });
   }
   }).success(function ( data ) {
   $("#revert-ordr").slideUp();

   hit("-", data.amount);

   $("#sum span").fadeOut(function () {
   var sum = parseFloat($("#sum span").text());
   sum -= parseFloat(data.amount);
   $("#sum span").html(sum).fadeIn();
   });
   })
   .complete(function () {
   $("#form").unblock();
   });
   });*/

  /*$("#form form").on("submit", function ( event ) {
   event.preventDefault();

   var reName = /[a-zA-Z][a-zA-Z0-9 ,.'-_]+/;
   var reAmount = /\d+/;

   var valid = reName.test($("#name").val());
   valid = valid && reAmount.test($("#amount").val());

   if ( valid ) {
   $.ajax({
   url: basePath + "/create",
   type: "POST",
   data: {
   "ordr_appbundle_ordrtype[name]": $("#name").val(),
   "ordr_appbundle_ordrtype[description]": $("#description").val(),
   "ordr_appbundle_ordrtype[amount]": $("#amount").val()
   },
   beforeSend: function () {
   $('#form').block({
   message: '',
   css: { }
   });
   }
   })
   .success(function ( data ) {
   hit("+", $("#amount").val());

   $("#sum span").fadeOut(function () {
   $("#sum span").html(data.sum).fadeIn();
   });
   $("#form form")[0].reset();
   $("#revert-ordr").fadeIn();
   })
   .complete(function () {
   $("#form").unblock();
   });
   }
   });*/

});
