$(document).ready(function () {
  captchaCode();

  $('#time').datetimepicker({
    format: 'HH:mm',
    defaultDate: moment().format('LLL'),

  });

  $('#date').datetimepicker({
    format: 'YYYY-MM-DD',
    defaultDate: moment().format('L'),
    minDate: moment().format('L'),
  });

  $('.btn-submit').click(function (e) {
    e.preventDefault();

    var error = false;
    var name = $('#name').val();
    var phone = $('#phone').val();
    var email = $('#email').val();
    var captchaVal = $("#code").text();
    var captchaCode = $(".captcha").val();

    $(".error").remove();

    if (captchaVal == captchaCode) {
      $(".captcha").css({
        "color": "#609D29"
      });
    }
    else {
      $(".captcha").css({
        "color": "#CE3B46"
      });

      $('.captcha').after('<span class="error">Enter a valid captcha</span>');

      error = true;
    }

    if (name.length < 1) {
      $('#name').after('<span class="error">This field is required</span>');
      error = true;
    }
    if (email.length < 1) {
      $('#email').after('<span class="error">This field is required</span>');
      error = true;
    } else {
      var regEx = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
      var validEmail = regEx.test(email);
      if (!validEmail) {
        $('#email').after('<span class="error">Enter a valid email</span>');
        error = true;
      }
    }
    if (phone.length < 1) {
      $('#phone').after('<span class="error">This field is required</span>');
      error = true;
    }
    else {
      var regEx = /^[\+]?[(]?[0-9]{3}[)]?[-\s\.]?[0-9]{3}[-\s\.]?[0-9]{4,6}$/im;

      var validPhone = regEx.test(phone);
      if (!validPhone) {
        $('#phone').after('<span class="error">Enter a valid phone number <br> Your phone number must have 10 or more digits </span>');
        error = true;
      }
    }

    if (error != true) {
      $('.form').submit();
    }


  });

});


function captchaCode() {
  var Numb1, Numb2, Numb3, Numb4, Code;
  Numb1 = (Math.ceil(Math.random() * 10) - 1).toString();
  Numb2 = (Math.ceil(Math.random() * 10) - 1).toString();
  Numb3 = (Math.ceil(Math.random() * 10) - 1).toString();
  Numb4 = (Math.ceil(Math.random() * 10) - 1).toString();

  Code = Numb1 + Numb2 + Numb3 + Numb4;
  $("#captcha span").remove();
  $("#captcha input").remove();
  $("#captcha").append("<span id='code'>" + Code + "</span><input type='button' onclick='captchaCode();'>");
}