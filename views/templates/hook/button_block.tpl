{if $enable_button_callforprice && $title_button}
    <div class="product-add-to-cart-callforprice">
        <div>
            <button class="btn btn-primary exclusive exclusive_callforprice"  data-id-product="{$id_product|escape:'htmlall':'UTF-8'}" data-id-lang="{$id_lang|escape:'htmlall':'UTF-8'}"  data-id-shop="{$id_shop|escape:'htmlall':'UTF-8'}" data-base-dir="{$base_dir|escape:'htmlall':'UTF-8'}">
                <span class="price_label" > {$title_button|escape:'htmlall':'UTF-8'} </span>
            </button>
        </div>
    </div>
{/if}

{if $disable_button_add}
    <style>
	 .CallForPriceButton .product-variants,
        .CallForPriceButton .exclusive_callforprice:before,
        .CallForPriceButton .exclusive_callforprice:after,
        .CallForPriceButton .product-add-to-cart
        {
            display: none;
        }

        .product-add-to-cart-callforprice{
            display: block !important;
        }
    </style>
{/if}

