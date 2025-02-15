{if $enable_button_callforprice && $title_button}
      <a class="button ajax_add_to_cart_button btn btn-default exclusive_callforprice" data-id-product="{$id_product|escape:'htmlall':'UTF-8'}" data-id-lang="{$id_lang|escape:'htmlall':'UTF-8'}"  data-id-shop="{$id_shop|escape:'htmlall':'UTF-8'}" data-base-dir="{if isset($force_ssl) && $force_ssl}{$base_dir_ssl|escape:'htmlall':'UTF-8'}{else}{$base_dir|escape:'htmlall':'UTF-8'}{/if}">
          <span class="price_label" > {$title_button|escape:'htmlall':'UTF-8'} </span>
      </a>
{/if}

{if $disable_button_add}
    <style>
        .CallForPriceButtonCategory .ajax_add_to_cart_button.exclusive_callforprice{
            display: inline-block !important
        }
        .CallForPriceButtonCategory .ajax_add_to_cart_button:before,
        .CallForPriceButtonCategory .ajax_add_to_cart_button:after,
        .CallForPriceButtonCategory .ajax_add_to_cart_button
        {
            display: none !important;
        }
    </style>
{/if}

