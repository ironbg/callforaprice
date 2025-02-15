

<div class="callBackList">

    <div class="progres_bar_ex"><div class="loading"><div></div></div></div>

    <table>

        <thead>
            <th class="item_one key_list">
                <span>{l s='ID' mod='callforprice'}</span>
               <div class="search_block_thead">
                   <input data-field="id_callforprice_list" class="search" {if $search && $search['id_callforprice_list']}value="{$search['id_callforprice_list']|escape:'htmlall':'UTF-8'}" {/if} >
               </div>
            </th>



            <th class="item_id_product item_one">
                <span>{l s='ID(prod)' mod='callforprice'}</span>
                <div class="search_block_thead">
                    <input  data-field="id_product" class="search" {if $search && $search['id_product']}value="{$search['id_product']|escape:'htmlall':'UTF-8'}" {/if} >
                </div>
            </th>

            <th class="item_prod_name item_one">
                <span>{l s='Name(prod)' mod='callforprice'}</span>
                <div class="search_block_thead">
                    <input  data-field="prod_name" class="search" {if $search && $search['prod_name']}value="{$search['prod_name']|escape:'htmlall':'UTF-8'}" {/if} >
                </div>
            </th>

            <th class="item_name item_one">
                <span>{l s='User' mod='callforprice'}</span>
                <div class="search_block_thead">
                    <input data-field="name" class="search" {if $search && $search['name']}value="{$search['name']|escape:'htmlall':'UTF-8'}" {/if} >
                </div>
            </th>



            <th class="item_phone item_one">
                <span>{l s='Phone' mod='callforprice'}</span>
                <div class="search_block_thead">
                    <input  data-field="phone" class="search" {if $search && $search['phone']}value="{$search['phone']|escape:'htmlall':'UTF-8'}" {/if} >
                </div>
            </th>
            <th class="item_email item_one">
                <span>{l s='Email' mod='callforprice'}</span>
                <div class="search_block_thead">
                    <input data-field="email" class="search" {if $search && $search['email']}value="{$search['email']|escape:'htmlall':'UTF-8'}" {/if} >
                </div>
            </th>
            <th class="item_hour item_one">
                <span>{l s='Hour' mod='callforprice'}</span>
                <div class="search_block_thead">
                    <input data-field="hour" class="search" {if $search && $search['hour']}value="{$search['hour']|escape:'htmlall':'UTF-8'}" {/if} >
                </div>
            </th>
            <th class="item_state item_one">
                <span>{l s='State' mod='callforprice'}</span>
                <div class="search_block_thead">
                    <input  data-field="state" class="search" {if $search && $search['state']}value="{$search['state']|escape:'htmlall':'UTF-8'}" {/if} >
                </div>
            </th>
            <th class="item_message item_one">
                <span>{l s='Message' mod='callforprice'}</span>
                <div class="search_block_thead">
                    <input  data-field="message" class="search" {if $search && $search['message']}value="{$search['message']|escape:'htmlall':'UTF-8'}" {/if} >
                </div>
            </th>
            <th class="item_date_add item_one">
                <span>{l s='Date' mod='callforprice'}</span>
                <div class="search_block_thead">
                    <input  data-field="date_add" class="search"  {if $search && $search['date']}value="{$search['date']|escape:'htmlall':'UTF-8'}" {/if}>
                </div>
            </th>
            <th class="item_one item_delete">
                <span></span>
                <div class="search_block_thead">
                    <a class="btn btn-default btn-default-search">
                        {if $version_new}
                            <i class="material-icons">search</i>
                        {else}
                            <i class="icon-search"></i>
                        {/if}
                    </a>
                </div>
            </th>
        </thead>
        {foreach $items as $key=>$value}
            <tr class="itemList itemList_{$value['id_callforprice_list']|escape:'htmlall':'UTF-8'}">
                <td class="item_one key_list"> {$value['id_callforprice_list']|escape:'htmlall':'UTF-8'}. </td>

                <td class="item_id_product item_one"> {$value['id_product']|escape:'htmlall':'UTF-8'}</td>
                <td class="item_prod_name item_one"> {$value['prod_name']|escape:'htmlall':'UTF-8'}</td>
                <td class="item_name item_one"> {$value['name']|escape:'htmlall':'UTF-8'}</td>

                <td class="item_phone item_one"> {$value['phone']|escape:'htmlall':'UTF-8'}</td>
                <td class="item_email item_one"> {$value['email']|escape:'htmlall':'UTF-8'}</td>
                <td class="item_hour item_one">{$value['hour']|escape:'htmlall':'UTF-8'}</td>
                <td class="item_state item_one">
                    <select class="state_call_back" name="state_call_back" data-id="{$value['id_callforprice_list']|escape:'htmlall':'UTF-8'}">
                        {foreach $states as $key=>$state}
                            <option value="{$key|escape:'htmlall':'UTF-8'}" {if $key == $value['state']} selected{/if} >{$state|escape:'htmlall':'UTF-8'}</option>
                        {/foreach}
                    </select>
                </td>
                <td class="item_message item_one"> {$value['message']|escape:'htmlall':'UTF-8'}</td>
                <td class="item_date_add item_one"> {$value['date_add']|escape:'htmlall':'UTF-8'}</td>
                <td class="item_one item_delete">
                    {if $version_new}
                        <i class="material-icons delete" data-id="{$value['id_callforprice_list']|escape:'htmlall':'UTF-8'}">delete</i>
                    {else}
                        <i class="icon-trash delete" data-id="{$value['id_callforprice_list']|escape:'htmlall':'UTF-8'}"></i>
                    {/if}
                </td>
            </tr>
        {/foreach}

    </table>


    {if $start!=$stop}
        <div style="clear: "></div>
        <div id="callback_pagination">
            <ul class="callback_pagination">
                {if $start==3}
                    <li>
                        <a href="{$path_pagination|escape:'htmlall':'UTF-8'}1">
                            <span>1</span>
                        </a>
                    </li>
                    <li>
                        <a href="{$path_pagination|escape:'htmlall':'UTF-8'}2">
                            <span>2</span>
                        </a>
                    </li>
                {/if}
                {if $start==2}
                    <li>
                        <a href="{$path_pagination|escape:'htmlall':'UTF-8'}1">
                            <span>1</span>
                        </a>
                    </li>
                {/if}
                {if $start>3}
                    <li>
                        <a href="{$path_pagination|escape:'htmlall':'UTF-8'}1">
                            <span>1</span>
                        </a>
                    </li>
                    <li class="truncate">
                        <span>
                          <span>...</span>
                        </span>
                    </li>
                {/if}
                {section name=pagination start=$start loop=$stop+1 step=1}
                    {if $p == $smarty.section.pagination.index}
                        <li data-p="{$p|escape:'html':'UTF-8'}" class="active current">
                          <span>
                            <span>{$p|escape:'html':'UTF-8'}</span>
                          </span>
                        </li>
                    {else}
                        <li>
                            <a href="{$path_pagination|escape:'htmlall':'UTF-8'}{$smarty.section.pagination.index|escape:'htmlall':'UTF-8'}" >
                                <span>{$smarty.section.pagination.index|escape:'html':'UTF-8'}</span>
                            </a>
                        </li>
                    {/if}
                {/section}
                {if $pages_nb>$stop+2}
                    <li class="truncate">
                <span>
                  <span>...</span>
                </span>
                    </li>
                    <li>
                        <a href="{$path_pagination|escape:'htmlall':'UTF-8'}{$pages_nb|escape:'htmlall':'UTF-8'}">
                            <span>{$pages_nb|intval}</span>
                        </a>
                    </li>
                {/if}
                {if $pages_nb==$stop+1}
                    <li>
                        <a href="{$path_pagination|escape:'htmlall':'UTF-8'}{$pages_nb|escape:'htmlall':'UTF-8'}">
                            <span>{$pages_nb|intval}</span>
                        </a>
                    </li>
                {/if}
                {if $pages_nb==$stop+2}
                    <li>
                        <a href="{$path_pagination|escape:'htmlall':'UTF-8'}{($pages_nb-1)|escape:'htmlall':'UTF-8'}">
                            <span>{$pages_nb-1|intval}</span>
                        </a>
                    </li>
                    <li>
                        <a href="{$path_pagination|escape:'htmlall':'UTF-8'}{$pages_nb|escape:'htmlall':'UTF-8'}">
                            <span>{$pages_nb|intval}</span>
                        </a>
                    </li>
                {/if}
            </ul>
        </div>
    {/if}

</div>