<div id="product-callforprice" class="product-tab col-md-9" data-product-id="{$id_product}" data-token="{$callforprice_token}" data-admin-url="{$admin_url}">


    <fieldset class="form-group">
        <label class="switch" >
            <input data-toggle="switch" class="" id="logged" data-inverse="true" type="checkbox" name="logged" {if $logged}checked="checked"{/if}>
            {l s='Show just for not logged customers:'  mod='callforprice'}
        </label>
    </fieldset>

    <fieldset class="form-group filter-by-customer-group-switch">
        <label class="switch">
            <input data-toggle="switch" type="checkbox" data-inverse="true" name="filter_by_customer_group" id="filter_by_customer_group" {if $filter_by_customer_group}checked="checked"{/if}>
            {l s='Activate only for customers from selected groups'  mod='callforprice'}
        </label>
    </fieldset>

    <fieldset class="form-group customer_group_list">
        <div class="col-lg-9">
            <div class="row">
                <div>
                    <table class="table table-bordered">
                        <thead>
                        <tr>
                            <th class="fixed-width-xs">
                                <span class="title_box">
                                  {l s='Check'  mod='callforprice'}
                                </span>
                            </th>
                            <th>
                                <span class="id-box">
                                 {l s='ID'  mod='callforprice'}
                                </span>
                            </th>
                            <th>
                                <a href="#" id="show_checked" class="btn btn-default"><i class="icon-check-sign"></i> {l s='Show Checked'  mod='callforprice'}</a>
                            &nbsp;   <a href="#" id="show_all" class="btn btn-default"><i class="icon-check-empty"></i> {l s='Show All'  mod='callforprice'}</a>
                                <span class="title_box" style="float: right;">
                                  <input type="text" class="form-control search_checkbox_table" placeholder="{l s='search...'  mod='callforprice'}">
                                </span>
                            </th>
                        </tr>
                        </thead>
                        <tbody>
                        {foreach $all_customer_groups as $key => $customer_group}
                            <tr>
                                <td>
                                    <input type="checkbox" class="checkbox_table select_customer_groups" name="customer_group_ids_{$customer_group['id_group']|escape:'htmlall':'UTF-8'}" id="customer_group_ids_{$customer_group['id_group']|escape:'htmlall':'UTF-8'}" value="{$customer_group['id_group']|escape:'htmlall':'UTF-8'}"
                                            {if !empty($checked_customer_groups) && in_array($customer_group['id_group'], $checked_customer_groups)}
                                                checked="checked"
                                            {/if}
                                    />
                                </td>
                                <td>{$customer_group['id_group']|escape:'htmlall':'UTF-8'}</td>
                                <td>
                                    <label for="customer_group_ids_{$customer_group['id_group']|escape:'htmlall':'UTF-8'}">
                                        {$customer_group['name']|escape:'htmlall':'UTF-8'}
                                    </label>
                                </td>
                            </tr>
                        {/foreach}
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </fieldset>

    <fieldset class="form-group">
        <label class="switch">
            <input data-toggle="switch" class="" id="disable_price" data-inverse="true" type="checkbox" name="disable_price" {if $disable_price}checked="checked"{/if}>
            {l s='Disable price display:'  mod='callforprice'}
        </label>
    </fieldset>

    <fieldset class="form-group">
        <label class="form-control-label" >
            {l s='Price label text:'  mod='callforprice'}
        </label>
        <div>
            <div class="translations tabbable">
                <div class="translationsFields tab-content bordered">
                    {foreach from=$languages item=language}
                        <div class="translatable-field tab-pane {if $language.id_lang == 1}active{/if}  translation-label-{$language.iso_code|escape:'htmlall':'UTF-8'}">
                            <textarea id="price_text_{$language.id_lang|escape:'htmlall':'UTF-8'}" name="price_text_{$language.id_lang|escape:'htmlall':'UTF-8'}" class="autoload_rte rte textarea-autosize">{if isset($label_price[$language.id_lang])}{$label_price[$language.id_lang]|escape:'htmlall':'UTF-8' nofilter}{/if}</textarea>
                        </div>
                    {/foreach}
                </div>
            </div>
        </div>
    </fieldset>

    <fieldset class="form-group">
        <label class="switch">
            <input data-toggle="switch" class="" id="disable_button_add" data-inverse="true" type="checkbox" name="disable_button_add" {if $disable_button_add}checked="checked"{/if}>
            {l s='Disable "Add to cart" button:'  mod='callforprice'}
        </label>
    </fieldset>

    <fieldset class="form-group">
        <label class="switch">
            <input data-toggle="switch" class="" id="enable_button_callforprice" data-inverse="true" type="checkbox" name="enable_button_callforprice" {if $enable_button_callforprice}checked="checked"{/if}>
            {l s='Show "Call For Price" button:'  mod='callforprice'}
        </label>
    </fieldset>

    <fieldset class="form-group">
        <label class="form-control-label form-control-label-title" >
			{l s='Button title:' mod='callforprice'}
        </label>
        <div class="col-sm-test">
            <div class="translations tabbable">
                <div class="translationsFields tab-content ">
                    {foreach $languages as $language}
                        <div class="translatable-field tab-pane {if $language.id_lang == 1}active{/if}  translation-label-{$language.iso_code|escape:'htmlall':'UTF-8'}">
                           <input type="text" class="form-control" name="title_button_{$language['id_lang']|escape:'htmlall':'UTF-8'}" value="{if isset($title_button[$language['id_lang']]) && isset($title_button[$language['id_lang']])}{$title_button[$language['id_lang']]|escape:'htmlall':'UTF-8'}{/if}" />
                        </div>
                    {/foreach}
                </div>
            </div>
        </div>
    </fieldset>

    <fieldset class="form-group">
        <button id="save_callforprice_item_settings" class="btn btn-primary btn-lg">{l s='Save Callforprice settings' mod='callforprice'}</button>
        <button id="delete_callforprice_item_settings" class="btn btn-danger btn-lg">{l s='Delete Callforprice settings' mod='callforprice'}</button>
    </fieldset>
</div>
<script type="text/javascript">
    $(document).ready(function(){
        tinySetup({
            editor_selector :"autoload_rte"
        });
    });
</script>
