
    <div class="gomakoil_call_block_preview">
        <div class="header_call_back_preview">
            <div class="title_call_back_preview">
                <h1 id="mpm_callforprice_form_title_preview"></h1>
                <h2 class="productNameTitle_preview">Product Name</h2>
            </div>
            <div id="header_call_back_background_preview"></div>
            <div class="close_block_preview" onclick=""><i class="m-cancel"></i></div>
        </div>

        <div class="header_call_back_preview_after"></div>
        <div id="gomakoilFreeCall_preview">
            <div class="callback_content_preview">
                <div class="form">
                        <div class="form-group" id="callback_form_preview_name_form_group">
                            <div class="input-group">
                                <span class="input-group-addon"><i class="m-user-1"></i></span>
                                <input type="text" class="form-control" placeholder="{l s='Name' mod='callforprice'}" autofocus>
                            </div>
                        </div>

                        <div class="form-group" id="callback_form_preview_email_form_group">
                            <div class="input-group">
                                <span class="input-group-addon"><i class="m-envelope"></i></span>
                                <input type="text" class="form-control" placeholder="{l s='E-mail' mod='callforprice'}"  >
                            </div>
                        </div>

                        <div class="form-group" id="callback_form_preview_phone_form_group">
                            <div class="input-group">
                                <span class="input-group-addon"><i class="m-smartphone-1"></i></span>
                                <input type="text" class="form-control" placeholder="{l s='Phone number' mod='callforprice'}">
                            </div>
                        </div>

                        <div class="form-group" id="callback_form_preview_delay_form_group">
                            <div class="input-group">
                                <span class="input-group-addon"><i class="m-clock"></i></span>
                                <select class="form-control">
                                    <option value="" selected>{l s='Callback convenient hour' mod='callforprice'}</option>
                                    {foreach $hours as $key=>$value}
                                        <option value="{$value|escape:'htmlall':'UTF-8'}">{$value|escape:'htmlall':'UTF-8'}</option>
                                    {/foreach}
                                </select>
                            </div>
                        </div>

                        <div class="form-group" id="callback_form_preview_message_form_group">
                            <div class="input-group">
                                <span class="input-group-addon"><i class="m-speech-bubble"></i></span>
                                <textarea class="form-control" rows="5" placeholder="{l s='Message' mod='callforprice'}"></textarea>
                            </div>
                        </div>

                        <div class="form-group" id="callback_form_preview_captcha_form_group">
                            <div class="input-group">
                                <span class="input-group-addon" id="mpm_callforprice_form_captcha"><img src="{$captcha_url|escape:'htmlall':'UTF-8'}"></span>
                                <input type="text" class="form-control" id="mpm_callforprice_form_captcha_input" placeholder="{l s='Captcha' mod='callforprice'}">
                            </div>
                        </div>

                        <div class="form-group" id="callback_form_preview_recaptcha_form_group">
                            <script src='https://www.google.com/recaptcha/api.js'></script>
                            <div class="g-recaptcha" data-callback="checkIfRecaptchaIsVerified" data-expired-callback="checkIfRecaptchaIsVerified" data-sitekey="{$config['recaptcha_key']|escape:'htmlall':'UTF-8'}"></div>
                        </div>

                    <label id="consent_checkbox_container">
                        <input type='checkbox' name="consent_checkbox" id="consent_checkbox">
                        <span class="custom-checkbox"></span>
                        <span id="consent_message"></span>
                    </label>

                    <div class="form-group">
                        <button class="btn btn-primary btn-lg btn-block" id="button_large_call_preview">{l s='Send Request' mod='callforprice'}</button>
                    </div>
                    <div id="mpm_callforprice_form_additional_msg_preview">{l s='We\'ll contact you as soon as possible' mod='callforprice'}</div>
                </div>
            </div>
        </div>
    </div>
