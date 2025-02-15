{if $label_price}
    <div class="product-price-and-shipping product-price-and-shipping-callforprice">
        <span class="price_label" > {if $label_price}{$label_price|escape:'htmlall':'UTF-8' nofilter}{/if} </span>
    </div>
{/if}

