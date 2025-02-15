var formFieldSwitchIds = "input[name='show_name'], input[name='show_email'], input[name='show_phone'],input[name='show_message'], input[name='show_captcha'], input[name='show_recaptcha'], input[name='show_delay'], input[name='show_consent_checkbox']";
var formRequiredSwitchIds = "input[name='required_name'], input[name='required_email'], input[name='required_phone'],input[name='required_message']";

var formFieldIds = {
    name: {
        switch_id: "input[name='show_name']",
        in_form_id: "#callback_form_preview_name_form_group",
        switch_required_sign: "input[name='required_name']",
        input_field_type: "input",
    },
    email: {
        switch_id: "input[name='show_email']",
        in_form_id: "#callback_form_preview_email_form_group",
        switch_required_sign: "input[name='required_email']",
        input_field_type: "input",
    },
    phone: {
        switch_id: "input[name='show_phone']",
        in_form_id: "#callback_form_preview_phone_form_group",
        switch_required_sign: "input[name='required_phone']",
        input_field_type: "input",
    },
    message: {
        switch_id: "input[name='show_message']",
        in_form_id: "#callback_form_preview_message_form_group",
        switch_required_sign: "input[name='required_message']",
        input_field_type: "textarea",
    },
    captcha: {
        switch_id: "input[name='show_captcha']",
        in_form_id: "#callback_form_preview_captcha_form_group",
        switch_required_sign: undefined,
        input_field_type: "input",
    },
    recaptcha: {
        switch_id: "input[name='show_recaptcha']",
        in_form_id: "#callback_form_preview_recaptcha_form_group",
        switch_required_sign: undefined,
        input_field_type: "input",
    },
    delay: {
        switch_id: "input[name='show_delay']",
        in_form_id: "#callback_form_preview_delay_form_group",
        switch_required_sign: undefined,
        input_field_type: "select",
    },
    consent_checkbox: {
        switch_id: "input[name='show_consent_checkbox']",
        in_form_id: "#consent_checkbox_container",
        switch_required_sign: undefined,
        input_field_type: "input",
    },

};

var form_text_ids = {
    title: {
        "switch_id": "#settings_title_font_size",
        "in_form_id": "#mpm_callforprice_form_title_preview",
        "indicator_id": "#settings_title_font_size_value_indicator",
    },
    product_name: {
        "switch_id": "#settings_product_name_font_size",
        "in_form_id": ".productNameTitle_preview",
        "indicator_id": "#settings_product_name_font_size_value_indicator",
    },
    button_text: {
        "switch_id": "#settings_button_text_font_size",
        "in_form_id": "#button_large_call_preview",
        "indicator_id": "#settings_button_text_font_size_value_indicator",
    },
    footer_message: {
        "switch_id": "#settings_footer_message_font_size",
        "in_form_id": "#mpm_callforprice_form_additional_msg_preview",
        "indicator_id": "#settings_footer_message_font_size_value_indicator",
    }
};

function setConsentMessage(language_id) {
    var consent_message_from_tinymce = tinymce.get("consent_checkbox_message_" + language_id).getContent();
    $("#consent_message").html(consent_message_from_tinymce);
}

function toggleElement(switch_id, in_form_id) {
    if ($(switch_id + ":checked").val() === "1") {
        $(in_form_id).css("display", "block");
    } else {
        $(in_form_id).css("display", "none");
    }
}

function toggleRequiredSign(switch_id, in_form_id, input_field_type) {
    var input_selector = in_form_id + " " + input_field_type;
    var placeholder = $(input_selector).attr("placeholder");

    var placeholder_not_required = placeholder.replace('*', '');
    var placeholder_required = placeholder_not_required + " *";

    if ($(switch_id + ":checked").val() === "1") {
        $(input_selector).attr("placeholder", placeholder_required);
    } else {
        $(input_selector).attr("placeholder", placeholder_not_required);
    }
}

function changePreviewFormFontSize(text_id) {
    $(form_text_ids[text_id].indicator_id).text($(form_text_ids[text_id].switch_id).val() + "px");
    $(form_text_ids[text_id].in_form_id).css("font-size", $(form_text_ids[text_id].switch_id).val() + "px");
}

function setPreviewFormFontSize() {
    $.each(form_text_ids, function() {
        $(this.indicator_id).text($(this.switch_id).val() + "px");
        $(this.in_form_id).css("font-size", $(this.switch_id).val() + "px");
    });
}

function getLanguageId(href_with_id) {
    if (href_with_id == false) {
        return null;
    }

    var lang_id = href_with_id.replace(/\D+/g, '');
    
    return lang_id;
}

function onFocusInputFieldPreview(input_field, header_color, font_color, is_captcha) {
    $(input_field).css({"border-color": header_color, "background-color": "#ffffff"});
    $(input_field).prev("span").css({"border-color": header_color, "color":  font_color});

    if (is_captcha === false) {
        $(input_field).prev("span").css({"background-color": header_color});
    }
}

function onBlurInputFieldPreview(input_field) {
    $(input_field).css("border", "1px solid #dadada");
    $(input_field).prev("span").css({"border":"1px solid #dadada", "background": "#ffffff", "color": "#7a7a7a", "border-right":"none"});
    $(input_field).next("i").css("display", "none");
}

function setFontSizeIndicators() {
    $.each(form_text_ids, function() {
        var indicator = this.indicator_id;
        var switch_id = this.switch_id;
        $(indicator).text($(switch_id).val() + "px");
    });
}

function setPreviewFormInputFields() {
    $.each(formFieldIds, function () {
        toggleElement(this.switch_id, this.in_form_id);

        if (this.switch_required_sign !== undefined) {
            toggleRequiredSign(this.switch_required_sign, this.in_form_id, this.input_field_type);
        }
    });
}

function setPreviewFormProductNameInTitle() {
    toggleElement("input[name='show_product_name_in_title']", ".productNameTitle_preview");
}

function setPreviewFormHeaderColors(form_header, form_header_after, header_color, content_color) {
    $(form_header).css("background-color", header_color);
    $(form_header_after).css({"border-top-color": header_color, "background-color": content_color});
}

function setPreviewFormHeaderFontColors(form_title_block, form_close_block, font_color) {
    $(form_title_block).css("color", font_color);
    $(form_close_block).css("color", font_color);
}

function setPreviewFormContentBlockBackground(form_content_block, content_background_color_config) {
    form_content_block.css("background-color", $(content_background_color_config).val());
}

function setPreviewFormSubmitButtonColors(form_submit_button, header_color_config, font_color_config) {
    form_submit_button.css({"background-color": $(header_color_config).val(), "color": $(font_color_config).val()});
}

function setPreviewFormFooterMessageValues( form_footer_message, form_footer_message_color_config, form_footer_message_config) {
    form_footer_message.css({"color": $(form_footer_message_color_config).val()});
    form_footer_message.text(form_footer_message_config.val());
}

function setPreviewFormTitle(form_title, form_title_config) {
    form_title.text(form_title_config.val());
}



$(document).ready(function() {
    var language_id = $("#callforprice_settings_form #idLang").val();

    if ($(".translatable-field:not([style*='none']) a").attr("href")) {
        language_id = getLanguageId($(".translatable-field:not([style*='none']) a").attr("href"));
    }

    var header_color_config = ".background_button";
    var content_background_color_config = ".background_form";
    var font_color_config = ".color_form";
    var form_footer_message_color_config = ".form_footer_message_color";
    var form_title_config = $(".callback-form-title #title_form_" + language_id);
    var form_footer_message_config = $(".form-footer-message-group #form_footer_message_" + language_id);

    var form_header = ".header_call_back_preview";
    var form_header_after = ".header_call_back_preview_after";
    var form_title_block = ".title_call_back_preview";
    var form_title = $("#mpm_callforprice_form_title_preview");
    var form_close_block = ".close_block_preview";
    var form_content_block = $(".callback_content_preview");
    var form_submit_button = $("#button_large_call_preview");
    var form_footer_message = $("#mpm_callforprice_form_additional_msg_preview");
    var consent_checkbox_message = "";

    setFontSizeIndicators();
    setPreviewFormInputFields();
    setPreviewFormProductNameInTitle();
    setPreviewFormHeaderColors(form_header, form_header_after, $(header_color_config).val(), $(content_background_color_config).val());
    setPreviewFormHeaderFontColors(form_title_block, form_close_block, $(font_color_config).val());
    setPreviewFormFontSize();
    setPreviewFormContentBlockBackground(form_content_block, content_background_color_config);
    setPreviewFormSubmitButtonColors(form_submit_button, header_color_config, font_color_config);
    setPreviewFormFooterMessageValues( form_footer_message, form_footer_message_color_config, form_footer_message_config);
    setPreviewFormTitle(form_title, form_title_config);
    
    $(window).load(function() {
        setTimeout(function() {
            if (typeof tinymce !== 'undefined' && $("#consent_checkbox_message_" + language_id).length > 0) {
                setConsentMessage(language_id);
            }
                
            tinymce.get("consent_checkbox_message_" + language_id).on("keyup input change live", function () {
                setConsentMessage(language_id);
            });
        }, 100);
    });
    
    $("#consent_checkbox_container #consent_message, #consent_checkbox_container #consent_message p").css("color", $(form_footer_message_color_config).val());
    
    $(document).on("click change live", "#consent_checkbox", function() {
        if ($(this).is(":checked")) {
            $(".custom-checkbox").css("background-color", $(header_color_config).val());
        } else {
            $(".custom-checkbox").css("background-color", "#fff");
        }
    });

    $("#button_large_call_preview").click(function(e) {
        e.preventDefault();
    });

    $(form_submit_button).hover(function() {
        $("#button_large_call_preview").css("background-color", $(".hover_color").val());
    }, function () {
        $("#button_large_call_preview").css("background-color", $(header_color_config).css("background-color"));
    });

    $(form_close_block).hover(function() {
        $(".close_block_preview i").css("color", $(".hover_color").val());
    }, function () {
        $(".close_block_preview i").css("color", $(font_color_config).val());
    });

    $(document).on("focus", ".form-control", function() {
        var is_captcha_field = false;
        if ($(this).attr("id") === "mpm_callforprice_form_captcha_input") {
            is_captcha_field = true;
        }

        onFocusInputFieldPreview(this, $(header_color_config).val(), $(font_color_config).val(), is_captcha_field);
    });

    $(document).on("blur", ".form-control", function() {
        onBlurInputFieldPreview(this);
    });

    $(document).on("change input", "#settings_title_font_size", function() {
        changePreviewFormFontSize("title");
    });
    $(document).on("change input", "#settings_product_name_font_size", function() {
        changePreviewFormFontSize("product_name");
    });
    $(document).on("change input", "#settings_button_text_font_size", function() {
        changePreviewFormFontSize("button_text");
    });
    $(document).on("change input", "#settings_footer_message_font_size", function() {
        changePreviewFormFontSize("footer_message");
    });

    $(document).on("click", ".translatable-field:not([style*='none']) a", function() {
        var lang_id = getLanguageId($(this).attr("href"));

        form_title.text($(".callback-form-title .lang-" + lang_id + " input").val());
        form_footer_message.text($(".form-footer-message-group .lang-" + lang_id + " input").val());
    
        setConsentMessage(lang_id);
    
        tinymce.get("consent_checkbox_message_" + lang_id).on("keyup input change live", function () {
            setConsentMessage(lang_id);
        });
        
        language_id = lang_id;
    });

    $(document).on("change input", form_title_config, function() {
        form_title.text($(".callback-form-title #title_form_" + language_id).val());
    });

    $(document).on("change input", form_footer_message_config, function() {
        form_footer_message.text($(".form-footer-message-group #form_footer_message_" + language_id).val());
    });

    $(document).on("change keyup live", header_color_config, function() {
       $(".header_call_back_preview, #button_large_call_preview").css("background-color", $(header_color_config).css("background-color"));
       $(".header_call_back_preview_after").css("border-top-color", $(header_color_config).css("background-color"));
    
        if ($("#consent_checkbox").is(":checked")) {
            $(".custom-checkbox").css("background-color", $(header_color_config).css("background-color"));
        } else {
            $(".custom-checkbox").css("background-color", "#fff");
        }
    });

    $(document).on("change keyup live", content_background_color_config, function() {
        $(".header_call_back_preview_after, .callback_content_preview").css("background-color", $(content_background_color_config).css("background-color"));
    });

    $(document).on("change keyup live", font_color_config, function() {
        $(".title_call_back_preview, .close_block_preview, #button_large_call_preview").css("color", $(font_color_config).css("background-color"));
    });

    $(document).on("change keyup live", form_footer_message_color_config, function() {
        $(form_footer_message).css("color", $(".form_footer_message_color").css("background-color"));
        $("#consent_checkbox_container #consent_message, #consent_checkbox_container #consent_message p").css("color", $(".form_footer_message_color").css("background-color"));
    });

    $(document).on("change live", formFieldSwitchIds, function() {
        var splited_input_name = this.name.split("_");
        
        if (splited_input_name < 2) {
            return false;
        }
    
        let input_name_unique_id = '';
        switch (splited_input_name.length) {
            case 2:
                input_name_unique_id = splited_input_name[splited_input_name.length -1];
                break;
            default:
                splited_input_name.splice(0, 1);
                input_name_unique_id = splited_input_name.join("_");
        }
        
        toggleElement(formFieldIds[input_name_unique_id].switch_id, formFieldIds[input_name_unique_id].in_form_id);
    });

    $(document).on("change live", formRequiredSwitchIds, function() {
        var splited_input_name = this.name.split("_");
        var input_name_unique_id = splited_input_name[splited_input_name.length -1];
        toggleRequiredSign(formFieldIds[input_name_unique_id].switch_required_sign, formFieldIds[input_name_unique_id].in_form_id, formFieldIds[input_name_unique_id].input_field_type);
    });

    $(document).on("change live", "input[name='show_product_name_in_title']", function() {
        toggleElement("input[name='show_product_name_in_title']", ".productNameTitle_preview");
    });
    
    let show_consent_switch = "input[name='show_consent_checkbox']";
    let show_consent_switch_on = "#show_consent_checkbox_on";
    let consent_checkbox_message_textarea = ".consent_checkbox_message_textarea";
    
    if ($(show_consent_switch_on).is(":checked")) {
        $(consent_checkbox_message_textarea).show();
    } else {
        $(consent_checkbox_message_textarea).hide();
    }
    
    $(document).on("click change", show_consent_switch, function() {
        if ($(show_consent_switch_on).is(":checked")) {
            $(consent_checkbox_message_textarea).show();
        } else {
            $(consent_checkbox_message_textarea).hide();
        }
    });
});