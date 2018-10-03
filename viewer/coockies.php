<?php?>
<div>
<div id="cookie-notice" role="banner" class="cn-bottom bootstrap" 
style="color: rgb(255, 255, 255); background-color: rgb(0, 0, 0); display: block;">
<div class="cookie-notice-container">
    <span id="cn-notice-text">
        We use cookies to ensure that we give you the best experience on our website. 
        If you continue to use this site we will assume that you are happy with it.</span>
        <a href="#" id="cn-accept-cookie" data-cookie-set="accept" class="cn-set-cookie button bootstrap">Ok</a>
</div>
</div>
<script>
    document.querySelector('#cn-accept-cookie').addEventListener('click',function(){
        document.querySelector('#cookie-notice').style.display ='none';
    })
</script>
<style>
    #cookie-notice {
        bottom: 0;
    position: fixed;
    min-width: 100%;
    height: auto;
    z-index: 100000;
    font-size: 13px;
    line-height: 20px;
    left: 0;
    text-align: center;
    color: rgb(255, 255, 255);
    background-color: rgb(0, 0, 0); 
}
.cookie-notice-container {
    padding: 10px;
    text-align: center;
}
#cookie-notice .button.bootstrap {
    margin-right: .3em;
    margin-bottom: 0;
    line-height: 20px;
    text-align: center;
    vertical-align: middle;
    color: #fff;
    text-shadow: 0 -1px 0 rgba(0,0,0,.25);
    background-color: #006dcc;
    background-image: -moz-linear-gradient(top,#08c,#04c);
    background-image: -webkit-gradient(linear,0 0,0 100%,from(#08c),to(#04c));
    background-image: -webkit-linear-gradient(top,#08c,#04c);
    background-image: -o-linear-gradient(top,#08c,#04c);
    background-image: linear-gradient(to bottom,#08c,#04c);
    background-repeat: repeat-x;
    border-color: #04c #04c #002a80;
    border-color: rgba(0,0,0,.1) rgba(0,0,0,.1) rgba(0,0,0,.25);
    box-shadow: 0 1px 0 rgba(255,255,255,.2) inset, 0 1px 2px rgba(0,0,0,.05);
    filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#ff0088cc', endColorstr='#ff0044cc', GradientType=0);
    filter: progid:DXImageTransform.Microsoft.gradient(enabled=false);
    padding: 2px 10px;
    -webkit-border-radius: 3px;
    -moz-border-radius: 3px;
    border-radius: 3px;
}
        
</style>
</div>