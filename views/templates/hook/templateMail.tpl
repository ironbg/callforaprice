<div style="width:100%;">
    <div id="templateMailTableWrapper" style="
                                            width: 633px;
                                            background-color: #FBFBFB;
                                            border: 1px solid #dadada;
                                            min-height: 520px;
                                            margin: 0 auto;
                                            -moz-box-shadow: 0 0px 15px #898A8E;
                                            -webkit-box-shadow: 0 0px 15px #898A8E;
                                            box-shadow: 0 0px 15px #898A8E;
                                        ">

        <div id="templateMailTableLogo" style="
                                                min-height: 50px;
                                                text-align: center;
                                                padding: 20px;

                                          ">
            <a href="{$baseUrl|escape:'htmlall':'UTF-8'}" style="
                                                                min-height: 100px;
                                                                width: 100%;
                                                            ">
                <img  src="{literal}{shop_logo}{/literal}">
            </a>
        </div>
        <div style="
                    margin-top: 25px;
                    min-height: 45px;
                    text-align: center;
            ">
            <span class="title" style="
                                        font-weight:normal;
                                        font-size:22px;
                                        color: #000000;
                                        line-height:25px
                                ">
            {l s='Call For Price' mod='callforprice'}
            </span>
            <br/>
        </div>
        <div>
            <table id="templateMailTable" style="
                                            margin-top: 15px;
                                            margin-bottom: 20px;
                                            width: 590px;
                                            min-height: 240px;
                                            margin-left: 20px;
                                            border-radius: 7px;
                                            background-color: #fefdfd;
                                            border: 1px solid #b1b0af;
                                            border-collapse: collapse;
                                      ">
                <thead>
                <tr style="
                            border: 1px solid #b1b0af;
                            border-collapse: collapse;
                           ">
                    <th colspan="2" style="
                                            text-align: center;
                                            font-size: 17px;
                                            background-color: #f0f0f0;
                                            padding: 15px;
                                            height: 20px;
                                    ">
                        {l s='Report' mod='callforprice'}</th>
                </tr>
                </thead>
                <tbody>
                {if $productName}
                    <tr style="
                           border: 1px solid #b1b0af;
                           border-collapse: collapse;
                         ">
                        <td class="templateMailTable" style="
                                                            border: 1px solid #b1b0af;
                                                            border-collapse: collapse;
                                                            min-width: 150px;
                                                            font-size: 17px;
                                                            background-color: #fefdfd;
                                                            padding: 15px;
                                                            min-height: 20px;
                                                            word-break: break-all;
                                                ">
                            {l s='Product' mod='callforprice'}
                        </td>
                        <td class="templateMailTable" style="
                                                            border: 1px solid #b1b0af;
                                                            border-collapse: collapse;
                                                            min-width: 150px;
                                                            font-size: 17px;
                                                            background-color: #fefdfd;
                                                            padding: 15px;
                                                            min-height: 20px;
                                                            word-break: break-all;
                                                ">
                            <a href="{$productLink|escape:'htmlall':'UTF-8'}">{$productName|escape:'htmlall':'UTF-8'}</a></td>
                    </tr>
                {/if}
                {if $fio}
                    <tr style="border: 1px solid #b1b0af;
                    border-collapse: collapse;">
                        <td class="templateMailTable" style="
                                                            border: 1px solid #b1b0af;
                                                            border-collapse: collapse;
                                                            min-width: 150px;
                                                            font-size: 17px;
                                                            background-color: #fefdfd;
                                                            padding: 15px;
                                                            min-height: 20px;
                                                            word-break: break-all;
                                                ">
                            {l s='Name' mod='callforprice'}
                        </td>
                        <td class="templateMailTable" style="
                                                            border: 1px solid #b1b0af;
                                                            border-collapse: collapse;
                                                            min-width: 150px;
                                                            font-size: 17px;
                                                            background-color: #fefdfd;
                                                            padding: 15px;
                                                            min-height: 20px;
                                                            word-break: break-all;
                                                    ">
                            {$fio|escape:'htmlall':'UTF-8'}
                        </td>
                    </tr>
                {/if}
                {if $tel_number}
                    <tr style="border: 1px solid #b1b0af;
                    border-collapse: collapse;">
                        <td class="templateMailTable" style="
                                                            border: 1px solid #b1b0af;
                                                            border-collapse: collapse;
                                                            min-width: 150px;
                                                            font-size: 17px;
                                                            background-color: #fefdfd;
                                                            padding: 15px;
                                                            min-height: 20px;
                                                            word-break: break-all;
                                                ">
                            {l s='Phone number' mod='callforprice'}
                        </td>
                        <td class="templateMailTable" style="
                                                            border: 1px solid #b1b0af;
                                                            border-collapse: collapse;
                                                            min-width: 150px;
                                                            font-size: 17px;
                                                            background-color: #fefdfd;
                                                            padding: 15px;
                                                            min-height: 20px;
                                                            word-break: break-all;
                                                ">
                            {$tel_number|escape:'htmlall':'UTF-8'}
                        </td>
                    </tr>
                {/if}
                {if $email}
                    <tr style="border: 1px solid #b1b0af;
                    border-collapse: collapse;">
                        <td class="templateMailTable" style="
                                                            border: 1px solid #b1b0af;
                                                            border-collapse: collapse;
                                                            min-width: 150px;
                                                            font-size: 17px;
                                                            background-color: #fefdfd;
                                                            padding: 15px;
                                                            min-height: 20px;
                                                            word-break: break-all;
                                                ">
                            {l s='E-mail' mod='callforprice'}
                        </td>
                        <td class="templateMailTable" style="
                                                            border: 1px solid #b1b0af;
                                                            border-collapse: collapse;
                                                            min-width: 150px;
                                                            font-size: 17px;
                                                            background-color: #fefdfd;
                                                            padding: 15px;
                                                            min-height: 20px;
                                                            word-break: break-all;
                                                ">
                            {$email|escape:'htmlall':'UTF-8'}
                        </td>
                    </tr>
                {/if}
                {if $delay}
                    <tr style="border: 1px solid #b1b0af;
                    border-collapse: collapse;">
                        <td class="templateMailTable" style="
                                                            border: 1px solid #b1b0af;
                                                            border-collapse: collapse;
                                                            min-width: 150px;
                                                            font-size: 17px;
                                                            background-color: #fefdfd;
                                                            padding: 15px;
                                                            min-height: 20px;
                                                            word-break: break-all;
                                                ">
                            {l s='Callback convenient hour' mod='callforprice'}
                        </td>
                        <td class="templateMailTable" style="
                                                            border: 1px solid #b1b0af;
                                                            border-collapse: collapse;
                                                            min-width: 150px;
                                                            font-size: 17px;
                                                            background-color: #fefdfd;
                                                            padding: 15px;
                                                            min-height: 20px;
                                                            word-break: break-all;
                                                ">
                            {$delay|escape:'htmlall':'UTF-8'}
                        </td>
                    </tr>
                {/if}
                {if $message}
                    <tr style="border: 1px solid #b1b0af;
                    border-collapse: collapse;">
                        <td class="templateMailTable" style="
                                                            border: 1px solid #b1b0af;
                                                            border-collapse: collapse;
                                                            min-width: 150px;
                                                            font-size: 17px;
                                                            background-color: #fefdfd;
                                                            padding: 15px;
                                                            min-height: 20px;
                                                            word-break: break-all;
                                                ">
                            {l s='Message' mod='callforprice'}
                        </td>
                        <td class="templateMailTable" style="
                                                            border: 1px solid #b1b0af;
                                                            border-collapse: collapse;
                                                            min-width: 150px;
                                                            font-size: 17px;
                                                            background-color: #fefdfd;
                                                            padding: 15px;
                                                            min-height: 20px;
                                                            word-break: break-all;
                                                ">
                            {$message|escape:'htmlall':'UTF-8'}
                        </td>
                    </tr>
                {/if}
                </tbody>
            </table>
        </div>
    </div>
  </div>