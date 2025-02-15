<div class="gomakoilFreeCallContent {if $is_mobile}mobile_version{/if}" >
    <div class="gomakoil_overlay" style="display: none"></div>
    <div class="gomakoil_call_block">
        <div class="header_call_back">
            <div class="title_call_back">
                <h1 id="mpm_callforprice_form_title">{$config['title_form']|escape:'htmlall':'UTF-8'}</h1>
                {if $config['show_product_name_in_title']}
                    <h2 class="productNameTitle">{$productName|escape:'htmlall':'UTF-8'}</h2>
                {/if}
            </div>
            <div id="header_call_back_background"></div>
            <div class="close_block" onclick=""><i class="m-cancel"></i></div>
        </div>
        <div id="gomakoilFreeCall">
            <div class="callback_content">
                <div class="form">
                    {if $config['show_name']}
                        <div class="form-group fio-form-group">
                            <div class="mpm-callforprice-form-validation-message">Something went wrong</div>
                            <div class="input-group">
                                <span class="input-group-addon"><i class="m-user-1"></i></span>
                                <input type="text" name="fio" class="form-control" placeholder="{l s='Name' mod='callforprice'}{if $config['required_name']} *{/if}" {if isset($name) && $name}value="{$name|escape:'htmlall':'UTF-8'}" {/if} autofocus>
                                <i class="m-error-circle mpm-callforprice-form-input-info-sign"></i>
                            </div>
                        </div>
                    {/if}
                    {if $config['show_email']}
                        <div class="form-group email-form-group">
                            <div class="mpm-callforprice-form-validation-message">Something went wrong</div>
                            <div class="input-group">
                                <span class="input-group-addon"><i class="m-envelope"></i></span>
                                <input type="text" name="email" class="form-control" placeholder="{l s='E-mail' mod='callforprice'}{if $config['required_email']} *{/if}" {if isset($email) && $email}value="{$email|escape:'htmlall':'UTF-8'}" {/if} >
                                <i class="m-error-circle mpm-callforprice-form-input-info-sign"></i>
                            </div>
                        </div>
                    {/if}
                    {if $config['show_phone']}
                        <div class="form-group tel_number-form-group">
                            <div class="mpm-callforprice-form-validation-message">Something went wrong</div>
                            <div class="input-group">
                                <span class="input-group-addon"><i class="m-smartphone-1"></i></span>
                                <input type="text" name="tel_number" class="form-control" placeholder="{l s='Phone number' mod='callforprice'}{if $config['required_phone']} *{/if}" {if isset($phone) && $phone}value="{$phone|escape:'htmlall':'UTF-8'}" {/if} >
                                <i class="m-error-circle mpm-callforprice-form-input-info-sign"></i>
                            </div>
                        </div>
                    {/if}

                    {if isset($config['show_delay']) && $config['show_delay']}
                        <div class="form-group delay_request-form-group">
                            <div class="input-group">
                                <span class="input-group-addon"><i class="m-clock"></i></span>
                                <select class="form-control" name="delay_request">
                                    <option value="" selected>{l s='Callback convenient hour' mod='callforprice'}</option>
                                    {foreach $hours as $key=>$value}
                                        <option value="{$value|escape:'htmlall':'UTF-8'}">{$value|escape:'htmlall':'UTF-8'}</option>
                                    {/foreach}
                                </select>
                            </div>
                        </div>
                    {/if}

                    {if $config['show_message']}
                        <div class="form-group message-form-group">
                            <div class="mpm-callforprice-form-validation-message">Something went wrong</div>
                            <div class="input-group">
                                <span class="input-group-addon"><i class="m-speech-bubble"></i></span>
                                <textarea name="message" class="form-control" rows="3" placeholder="{l s='Message' mod='callforprice'}{if $config['required_message']} *{/if}"></textarea>
                                <i class="m-error-circle mpm-callforprice-form-input-info-sign"></i>
                            </div>
                        </div>
                    {/if}

                    {if $config['show_captcha']}
                        <div class="form-group captcha_res-form-group">
                            <div class="mpm-callforprice-form-validation-message">Something went wrong</div>
                            <div class="input-group">
                                <span class="input-group-addon" id="mpm_callforprice_form_captcha"><img src="{$captcha_url|escape:'htmlall':'UTF-8'}"></span>
                                <input type="text" class="form-control" name="captcha_res" id="mpm_callforprice_form_captcha_input" placeholder="{l s='Captcha' mod='callforprice'}">
                                <i class="m-error-circle mpm-callforprice-form-input-info-sign"></i>
                            </div>
                        </div>
                    {/if}

                    {if $config['show_recaptcha']}
                        <div class="form-group recaptcha_res-form-group">
                            <script src='https://www.google.com/recaptcha/api.js'></script>
                            <div class="g-recaptcha" data-callback="checkIfRecaptchaIsVerified" data-expired-callback="checkIfRecaptchaIsVerified" data-sitekey="{$config['recaptcha_key']|escape:'htmlall':'UTF-8'}"></div>
                        </div>
                    {/if}

                    {if $config['show_consent_checkbox']}
                        <label id="consent_checkbox_container">
                            <input type='checkbox' name="consent_checkbox" id="consent_checkbox">
                            <span class="custom-checkbox"></span>
                            <span id="consent_message">{$config['consent_checkbox_message'] nofilter}</span>
                        </label>
                    {/if}
                    <div class="form-group submit_callforprice">
                        <button type="submit" class="btn btn-primary btn-lg btn-block {if $config['show_consent_checkbox']}disabled-submit-btn{/if}" id="button_large_call" data-id-product="{$id_product|escape:'htmlall':'UTF-8'}" data-id-lang="{$id_lang|escape:'htmlall':'UTF-8'}"  data-id-shop="{$id_shop|escape:'htmlall':'UTF-8'}" data-base-dir="{$base_dir|escape:'htmlall':'UTF-8'}" {if $config['show_consent_checkbox']}disabled{/if}>{l s='Send Request' mod='callforprice'}</button>
                    </div>
                    <div id="mpm_callforprice_form_additional_msg">{$config['form_footer_message']|escape:'htmlall':'UTF-8'}</div>
                </div>
            </div>
        </div>
        <div class="progres_bar_call_for_price"><div class="loading"><div></div></div></div>
    </div>
</div>