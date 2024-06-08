$(function () {
    'use strict';
    
    //Calls the selectBoxIt method on your HTML select box.
    $("select").selectBoxIt({
        autoWidth: false,
        showEffect: "fadeIn",
        hideEffect: "fadeOut",
        //showFirstOption: false,
        //theme: "bootstrap"
    });
    
    // إخفاء أول خيار من الشيك بوكسعند الضغط عليه
    $('.delete-dot').click(function () {
        $(this).find('.selectboxit-option-first').hide();
    });
    
    // hide placeholder on form focus
    
    $('[placeholder]').focus(function () {
        $(this).attr('data-text',$(this).attr('placeholder'));
        $(this).attr('placeholder','');
    }).blur(function () {
        $(this).attr('placeholder',$(this).attr('data-text'));
    });
    
    //add asterisk on reauired field 
    
    $('input').each(function () {
        if ($(this).attr('required') === 'required'){
            $(this).after('<span class="asterisk">*</span>');
        }        
    });
    
    $('textarea').each(function () {
        if ($(this).attr('required') === 'required'){
            $(this).after('<span class="asterisk">*</span>');
        }        
    });    
    
    //Convert password field to text field on hover
    
    var passField = $('.password');
    $('.show-pass').hover(function () {
        passField.attr('type','text');
    }, function () {
        passField.attr('type','password');
    });
    
    //confirmation message on button 
    
    $('.confirm').click( function() {
        return confirm('Are You Sure ?');
    });
    
    // category view classic or full 
    
    $('.cat h3').click( function (){
       $(this).next('.full-view').fadeToggle(200); 
    });
    
    $('.categories .option span').click( function () {
       $(this).addClass('active').siblings('span').removeClass('active');
        if($(this).data('view') === 'full'){
            $('.cat .full-view').fadeIn(200);
        }else{
            $('.cat .full-view').fadeOut(200);
        }
    });
    /*
    $('.test').click(function (){
        var div = $('.container .test-panel').text();
        $('.container .copy-panel').text(div);
    });
    */
    
    // create lang cookie 
    function create_cookie(name, value, days2expire, path){
        var date = new Date();
        date.setTime(date.getTime() + (days2expire * 24 * 60 * 60 +1000));
        var expires = date.toUTCString();
        document.cookie = name + '=' + value + ';'
                          'expires=' + expires + ';'
                          'path=' + path + ';';
    }
    $('.footer .lang .english-mode').on('click', function(){
        create_cookie('lang', 'english', 30, '/');
        location.reload();
    });
    $('.footer .lang .arabic-mode').on('click', function(){
        create_cookie('lang', 'arabic', 30, '/');
        location.reload();
    });
});