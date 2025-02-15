.header_call_back, .header_call_back_info{
    background: {$config['background_button']|escape:'htmlall':'UTF-8'};
}
.title_call_back, #inform_callback_msg_sign i{
    color: {$config['color_form']|escape:'htmlall':'UTF-8'};
}
.inform_call_block .close_block,
.gomakoil_call_block .close_block{
    color: {$config['color_form']|escape:'htmlall':'UTF-8'};
}

.inform_call_block,
.callback_content {
    background: {$config['background_form']|escape:'htmlall':'UTF-8'};
}
.callback_content #button_large_call{
    color: {$config['color_form']|escape:'htmlall':'UTF-8'};
    background: {$config['background_button']|escape:'htmlall':'UTF-8'};
}

.CallForPriceLabel .product-prices.product-prices-callforprice{
    display: block !important;
}
.CallForPriceLabel .product-variants,
.CallForPriceLabel .product-prices {
    display: none;
}

.CallForPriceLabelCategory .product-price-and-shipping
{
    display: none;
}
.CallForPriceLabelCategory .product-price-and-shipping.product-price-and-shipping-callforprice
{
    display: block;
}

.header_call_back:after, .header_call_back_info:after {
    border-top-color: {$config['background_button']|escape:'htmlall':'UTF-8'};
}

#button_large_call {
    background: {$config['background_button']|escape:'htmlall':'UTF-8'};
    color: {$config['background_form']|escape:'htmlall':'UTF-8'};
    font-size: {$config['form_button_text_font_size']|escape:'htmlall':'UTF-8'}px;
}

#button_large_call:hover {
  background-color: {$config['hover_color']|escape:'htmlall':'UTF-8'};
}

#button_large_call.disabled-submit-btn:hover {
  background: {$config['background_button']|escape:'htmlall':'UTF-8'} !important;
}

.inform_call_block .close_block i:hover,
.gomakoil_call_block .close_block i:hover {
    color: {$config['hover_color']|escape:'htmlall':'UTF-8'};
}

#mpm_callforprice_form_additional_msg {
    color: {$config['form_footer_message_color']|escape:'htmlall':'UTF-8'};
}

#mpm_callforprice_form_title {
    font-size: {$config['form_title_font_size']|escape:'htmlall':'UTF-8'}px;
}

.productNameTitle {
    font-size: {$config['form_product_name_font_size']|escape:'htmlall':'UTF-8'}px;
}

#mpm_callforprice_form_additional_msg {
    font-size: {$config['form_footer_message_font_size']|escape:'htmlall':'UTF-8'}px;
}

.inform_call_block .content_info {
    color: {$config['form_footer_message_color']|escape:'htmlall':'UTF-8'};
}

#gomakoilFreeCall .mpm-callforprice-focused-input .input-group .form-control {
    border-color: {$config['background_button']|escape:'htmlall':'UTF-8'};
    background-color: #ffffff;
}

#gomakoilFreeCall .mpm-callforprice-focused-input .input-group-addon {
    border-color: {$config['background_button']|escape:'htmlall':'UTF-8'} !important;
    background-color: {$config['background_button']|escape:'htmlall':'UTF-8'};
    color: {$config['color_form']|escape:'htmlall':'UTF-8'};
}

#gomakoilFreeCall .captcha_res-form-group .input-group-addon {
  background-color: #ffffff !important;
}

.gomakoil_call_block #consent_checkbox_container input:checked ~ .custom-checkbox {
  background-color: {$config['background_button']|escape:'htmlall':'UTF-8'} !important;
}

.gomakoil_call_block #consent_checkbox_container #consent_message,
.gomakoil_call_block #consent_checkbox_container #consent_message p {
  color: {$config['form_footer_message_color']|escape:'htmlall':'UTF-8'} !important;
}


{if isset($config['css_code']) && $config['css_code']}
    {$config['css_code']|escape:'htmlall':'UTF-8'}
{/if}