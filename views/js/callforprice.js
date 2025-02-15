
$(document).ready(function(){
  $(window).resize(function() {
      if ($(window).width() > 430) {
          $("#mpm_callforprice_form_captcha").css({"border-top": "1px solid #dadada", "border-right": "none", "border-bottom": "1px solid #dadada", "border-left": "1px solid #dadada",});
      }
  });

  $(document).on('click', '.exclusive_callforprice', function(e){
    e.stopPropagation();
    e.preventDefault();
    showCallForPriceForm($(this).attr('data-id-product'), $(this).attr('data-id-lang'), $(this).attr('data-id-shop'), $(this).attr('data-base-dir'));
  });

  $(document).on('click', '#button_large_call', function(e){
    e.preventDefault();
    sendEmail($(this).attr('data-id-product'), $(this).attr('data-id-lang'), $(this).attr('data-id-shop'), $(this).attr('data-base-dir'));
  });

  $(document).on('click', '.gomakoil_call_block .close_block, .inform_call_block .close_block, .gomakoil_overlay', function(){
    $('.inform_call_block').fadeOut('500');
    $('.gomakoil_call_block').css('opacity',0);
    $('.gomakoil_overlay').fadeOut('900');
    setTimeout(" $('.gomakoilFreeCallContent ').remove()", 1000);
    setTimeout(" $('.inform_call_block').remove()", 100);
  });


  direction = 0;

  window.onload = function() {
    if (window.addEventListener) window.addEventListener("DOMMouseScroll", mouse_wheel, false);
    window.onmousewheel = document.onmousewheel = mouse_wheel;
  };

  var mouse_wheel = function(event) {
    if (false == !!event) event = window.event;
    direction = ((event.wheelDelta) ? event.wheelDelta/120 : event.detail/-3) || false;
  };

  $(document).scroll(function () {
    if($('.gomakoil_call_block').length>0){
      scrollCallforpriceForm($('.gomakoil_call_block'), 0);
    }
  });

  if ($(window).width() <= 430) {
    $(document).on("click focus", "#mpm_callforprice_form_captcha", function() {
      $("#mpm_callforprice_form_captcha").css("border", "none");
    });
  }

  $(document).on("focus", ".form-control", function() {
    onFocusInputField($(this).attr("name"));
  });

  $(document).on("blur", ".form-control", function(e) {
    onBlurInputField(e, $(this).attr("name"));
  });

  $(document).keydown(function(e) {
    var key = e.keyCode;

    if (key === 13 && e.target.localName !== "textarea") {
        $("#button_large_call").trigger("click");
    } else if (key === 27) {
        $(".close_block").trigger("click");
    }
  });
  
  getConsentForProcessingData();
  $(document).on("click change", "#gomakoilFreeCall #consent_checkbox", function() {
      getConsentForProcessingData();
  });
});

function checkIfRecaptchaIsVerified() {
    getConsentForProcessingData();
}

function scrollCallforpriceForm(el, show) {

  var top = $(document).scrollTop();
  var height_window = $(window).outerHeight();
  var height_form = el.outerHeight();
  var margin = (height_window - height_form)/2;
  var form_offset = el.offset().top;

  if(show && margin < 11){
    margin = 11;
  }

  if(margin > 10){
    var margin_top = margin+top;
    el.css('top', margin_top+'px');
  }
  else {
    if (direction >= 0) {

      if( top < form_offset ){
        el.css({top: (top + 10)});
      }
    }
    if (direction < 0) {
      if (top > (height_form + 10 - height_window)) {
        el.css({top: (top - (height_form - height_window + 10))});
      }
    }
  }
}

function sendEmail(id_product, id_lang, id_shop, base_dir){
  if ($("#gomakoilFreeCall #consent_checkbox").length && !$("#gomakoilFreeCall #consent_checkbox").is(":checked")) {
      return false;
  }
  
  $.ajax({
    type: "POST",
    url: base_dir+'index.php?rand=' + new Date().getTime(),
    dataType: 'json',
    async: true,
    cache: false,
    data: {
      ajax	: true,
      token: "",
      controller: 'AjaxForm',
      fc: 'module',
      module : 'callforprice',
      action: 'sendEmail',
      id_shop: id_shop,
      id_lang: id_lang,
      id_product: id_product,
      fio: $('#gomakoilFreeCall input[name="fio"]').val(),
      email: $('#gomakoilFreeCall input[name="email"]').val(),
      tel_number: $('#gomakoilFreeCall input[name="tel_number"]').val(),
      message: $('#gomakoilFreeCall textarea[name="message"]').val(),
      captcha_value: $('#gomakoilFreeCall input[name="captcha_res"]').val(),
      delay: $('#gomakoilFreeCall select[name="delay_request"]').val(),
    },
    beforeSend: function(){
      $(".progres_bar_call_for_price").show();
    },
    complete: function(){
      $(".progres_bar_call_for_price").hide();
    },
    success: function(json) {
      if(json['success']){
        $('.gomakoil_call_block').remove();
        fancyboxMessage(json['success'], true);
      } else if (json['error']) {
        $('.gomakoil_call_block').remove();
        fancyboxMessage(json["error"], false);
      } else if (json["error_field"]) {
        var form_input_names = ["fio", "email", "tel_number", "delay_request", "message", "captcha_res"];
        var error_field = json["error_field"];
        var error_message = json["error_message"];
        var input_type = error_field === "message" ? "textarea" : "input";

        if (form_input_names.indexOf(error_field) !== -1) {
            handleFormValidationError(error_field, error_message, input_type)
        }
      }
    },
    error: function() {
      $(".gomakoil_call_block").remove();
      fancyboxMessage("Message is not send. Unexpected error.", false);
    }
  });
}


function fancyboxMessage(msg, is_success) {

  var top_sign = "<i class='m-cancel'></i>";

  if (is_success) {
    top_sign = "<i class='m-checked'></i>";
  }

  var itemTemplate = "<div class='inform_call_block'>";
  itemTemplate += "<div class='header_call_back_info'>";
  itemTemplate += "<div id='header_call_back_background'></div>";
  itemTemplate += "<div class='close_block'><i class='m-cancel'></i></div>";
  itemTemplate += "<span id='inform_callback_msg_sign'>"+top_sign+"</span>";
  itemTemplate += "</div>";
  itemTemplate += "<div class='content_info'>";
  itemTemplate += "<span>" + msg + "</span>";
  itemTemplate += "</div>";
  itemTemplate += "</div>";

  var top = 100;
  if($('.gomakoilFreeCallContent').hasClass('mobile_version')){
    top = 10;
  }
  $(".gomakoilFreeCallContent").prepend(itemTemplate);
  $(".inform_call_block").css("top", ($(window).scrollTop()+top)+ "px" );

}



function showCallForPriceForm(id_product, id_lang, id_shop, base_dir){
  $.ajax({
    type: "POST",
    url: base_dir+'index.php?rand=' + new Date().getTime(),
    dataType: 'json',
    async: true,
    cache: false,
    data: {
      ajax	: true,
      token: "",
      controller: 'AjaxForm',
      fc: 'module',
      module : 'callforprice',
      action: 'showForm',
      id_shop: id_shop,
      id_lang: id_lang,
      id_product: id_product,
    },
    success: function(json) {

      if(json['form']){

        $(".modal.quickview").remove();
        $(".modal-backdrop").remove();

        $("body").removeClass('modal-open');
        $("body").prepend(json['form']);

        $('.gomakoil_overlay').fadeIn();
        $('.gomakoil_call_block').css("opacity", "1");
    
        var consent_checkbox_container = "#gomakoilFreeCall #consent_checkbox_container";
  
        if ($(consent_checkbox_container).length) {
            $(consent_checkbox_container).css("visibility", "visible").fadeIn();
      
            $(window).load(function () {
                if (jQuery().uniform) {
                    $.uniform.restore();
                }
            });
      
            $(window).resize(function () {
                if (jQuery().uniform) {
                    $.uniform.restore();
                }
            });
        }
        
        scrollCallforpriceForm($('.gomakoil_call_block'), 1);
      }
    }
  });
}

function getConsentForProcessingData() {
    var consent_checkbox = "#gomakoilFreeCall #consent_checkbox";
    var recaptcha_block = "#gomakoilFreeCall .g-recaptcha";
    var submit_button = "#button_large_call";
    var validated = true;
    
    if ($(consent_checkbox).length && !$(recaptcha_block).length) {
        validated = $(consent_checkbox).is(":checked") ? true : false;
    } else if ($(recaptcha_block).length && !$(consent_checkbox).length) {
        var recaptcha_response = grecaptcha.getResponse();
        validated = recaptcha_response != 0 ? true : false;
    } else if ($(recaptcha_block).length && $(consent_checkbox).length) {
        var consent_is_given = $(consent_checkbox).is(":checked");
        var not_robot = grecaptcha.getResponse() != 0;
        validated = consent_is_given && not_robot;
    }
    
    if (validated) {
        $(submit_button).attr("disabled", false);
        $(submit_button).removeClass("disabled-submit-btn");
    } else {
        $(submit_button).attr("disabled", true);
        $(submit_button).addClass("disabled-submit-btn");
    }
}

function handleFormValidationError(error_field_name, error_message) {
    $("#gomakoilFreeCall .form-group").removeClass("mpm-callforprice-focused-input");
    $("#gomakoilFreeCall .form-group").removeClass("mpm-callforprice-validation-error");

    $("#gomakoilFreeCall ."+error_field_name+"-form-group input, #gomakoilFreeCall ."+error_field_name+"-form-group textarea").focus();
    $("#gomakoilFreeCall .form-group").removeClass("mpm-callforprice-focused-input");
    $("#gomakoilFreeCall ."+error_field_name+"-form-group").addClass("mpm-callforprice-validation-error");
    $("#gomakoilFreeCall ."+error_field_name+"-form-group .mpm-callforprice-form-validation-message").text(error_message);

    return true;
}


function onFocusInputField(input_field_name) {
    $("#gomakoilFreeCall .form-group").removeClass("mpm-callforprice-focused-input");

    if ($("#gomakoilFreeCall ."+input_field_name+"-form-group").hasClass("mpm-callforprice-validation-error") === false) {
        $("#gomakoilFreeCall ."+input_field_name+"-form-group").addClass("mpm-callforprice-focused-input");
        $("#gomakoilFreeCall ."+input_field_name+"-form-group").removeClass("mpm-callforprice-validation-error");
    }

    return true;
}

function onBlurInputField(event, input_field_name) {
    if (event.relatedTarget && event.relatedTarget.id == "button_large_call") {
        $("#button_large_call").trigger("click");
    }

    $("#gomakoilFreeCall ."+input_field_name+"-form-group").removeClass("mpm-callforprice-focused-input");
    $("#gomakoilFreeCall ."+input_field_name+"-form-group").removeClass("mpm-callforprice-validation-error");

    return true;
}
