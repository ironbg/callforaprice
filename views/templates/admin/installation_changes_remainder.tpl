<div class="mpm-installation-changes-remainder-overlay"></div>
<div class="mpm-installation-changes-remainder">
    <img src="{$img_folder}exclamation-triangle-solid.svg">
    <div class="main-title">{l s='Warning!' mod='callforprice'}</div>
    <div class="message-block-1">{l s='The required changes for the correct operation of this module are not prepared!' mod='callforprice'}</div>
    <div class="message-block-2">{l s='Instructions for the changes ' mod='callforprice'}
        <a href="{$link_to_readme}" target="_blank">{l s='can be found here.' mod='callforprice'}</a></div>
    <div class="close-btn">{l s='Close' mod='callforprice'}</div>
</div>

<style>

    .mpm-installation-changes-remainder {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        z-index: 99999;
        background: #fff;
        width: 450px;
        border-radius: 10px;
        padding: 40px;
    }

    .mpm-installation-changes-remainder > img {
        max-width: 60px;
        margin: 0 auto;
        display: block;
        -webkit-font-smoothing: subpixel-antialiased;
        -webkit-transform: translateZ(0) scale(1.0, 1.0);
    }

    .mpm-installation-changes-remainder > .main-title {
        font-family: "Open Sans";
        font-style: normal;
        font-weight: normal;
        font-size: 24px;
        color: #515151;
        text-align: center;
        margin-top: 25px;
        -webkit-font-smoothing: subpixel-antialiased;
        -webkit-transform: translateZ(0) scale(1.0, 1.0);
    }

    .mpm-installation-changes-remainder > .message-block-1,
    .mpm-installation-changes-remainder > .message-block-2 {
        font-size: 16px;
        color: #515151;
        text-align: center;
        -webkit-font-smoothing: subpixel-antialiased;
        -webkit-transform: translateZ(0) scale(1.0, 1.0);
    }

    .mpm-installation-changes-remainder > .message-block-1 {
        margin-top: 16px;
    }

    .mpm-installation-changes-remainder > .message-block-2 {
        margin-top: 12px;
    }

    .mpm-installation-changes-remainder > .message-block-2 > a{
        margin-top: 12px;
        color: #3498db;
    }

    .mpm-installation-changes-remainder > .message-block-2 > a:hover {
        color: #326588;
    }

    .mpm-installation-changes-remainder > .close-btn {
        width: 120px;
        height: 40px;
        background: #2ECC71;
        border: 2px solid #2ECC71;
        color: #fff;
        border-radius: 5px;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 14px;
        margin: 40px auto 0 auto;
        -webkit-font-smoothing: subpixel-antialiased;
        -webkit-transform: translateZ(0) scale(1.0, 1.0);
    }

    .mpm-installation-changes-remainder > .close-btn:hover {
        background: #fff;
        color: #2ECC71;
    }

    .mpm-installation-changes-remainder-overlay {
        position: fixed;
        top: 0;
        width: 100%;
        height: 100%;
        background: black;
        z-index: 1000;
        opacity: 0.8;
    }
</style>