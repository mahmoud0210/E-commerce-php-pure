$(function () {
    'use strict';
 
//----------------------------------------------------------------------------------
    // details of the item
    
    var detailNum = $('.each-detail').length;
    $('.add-detail').on('click',function(){
        
        detailNum +=1;
        
        $('.all-detail').append("<div class='each-detail'><div class='form-group form-group-lg'><div class='col-sm-2 col-md-3 control-label '><label>Detail " + detailNum +" </label></div><div class='col-sm-10 col-md-8'><div class=''><input type='text'name='detail_name[]'class='form-control'placeholder='name of the detail'/></div><div class=''><input type='text'name='detail_desc[]' class='form-control' placeholder='description of the detail'/></div></div></div></div>");
        
        //console.log($('.each-detail').length);
    });
    
    
//----------------------------------------------------------------------------------
    
    $('.item-image .img-responsive').on('click', function(){
        $('.xx .item-image .big-image').fadeIn(500);
    });
    $('.item-image .big-image .close-image i').on('click', function(){
        $('.xx .item-image .big-image').fadeOut(500);
    });
    
    $('.xx .item-image .big-image').click(function(){
        $(this).fadeOut(500);
    });
    $('.item-image .big-image .image-big-container img').click(function(e){
        e.stopPropagation();
    });
    //للإخفاء البوب اب عند الضغط على زر ايسكيب
    // 27 هو رمز زر الايسكيب
    $(document).keydown(function(e){
        if(e.keyCode == 27){
            $('.xx .item-image .big-image').fadeOut(500);
        }
    });    
//----------------------------------------------------------------------------------
    
                    // **** navbar js **** //
    
    $('.navbar-right .dropdown-menu .cat-childs').each(function(){
        $(this).hover(function(){
            $(this).css("display","block");
        },function(){
            $(this).css("display","none");
        });
    })    
    
    $('.navbar-right .dropdown-menu .cat-parent').each(function(){
        $(this).hover(function(){
            $(this).find($(this).data('id')).fadeIn();
        },function(){
            $(this).find($(this).data('id')).fadeOut();
        });
    })
    $('body').css("paddingTop",$('.navbar').innerHeight());
                    //--------------------    
    $('.navbar .collapse .navbar-nav .cat-lg-md').on('click', function(){
       $('.navbar .collapse .navbar-nav .cat-lg-md .dropdown-menu').slideToggle();
    });
//----------------------------------------------------------------------------------    
    //function slide cat 

    $('.new-item .right-control').on('click', function() {
       $(this).parent('.new-item').find('.new-slide-item.show').removeClass('show').siblings().addClass('show');
    });
    $('.new-item .left-control').on('click', function() {
       $(this).parent('.new-item').find('.new-slide-item.show').removeClass('show').siblings().addClass('show');
    });
//----------------------------------------------------------------------------------
    $('.carousel').carousel({
      interval: 4500
    })
    
    // gallery slider thumbnails 
    /*
    var numberImg = $('.thumbnails-img ').children().length,
        marginBetwen = 0.5,
        marginAll = (numberImg - 1) * marginBetwen,
        widthImg = (100 - marginAll) / numberImg,
        counterImgs = 1;
    $('.thumbnails-img .thumbnail').css({
        'width' : widthImg + '%',
        'marginRight' : marginBetwen + '%'
    });

    $('.gallery-container .main-img').css('width',$(window).width() * 5);
    $('.gallery-container .main-img img').css('width',$('.gallery-container .main-img').width()*0.2);

    $('.gallery-container .gallery .thumbnails-img .thumbnail').on('click', function(){
        $(this).addClass('active').siblings().removeClass('active');
        //console.log($(window).width());
      
        $('.gallery-container .main-img').animate({
            left: '-=100%'
        },700,function(){
            counterImgs += 1;
            console.log(counterImgs);
            if(counterImgs === 6) {
                counterImgs = 1;
                $('.gallery-container .main-img').css('left',0);
            }
        });
    });
    
    var autoChangeSlide;
    startautoChangeSlide();
    function startautoChangeSlide(){
        autoChangeSlide = setInterval(function(){
            if($('.thumbnails-img .active').is(':last-child')){
                $('.thumbnails-img .thumbnail').eq(0).click();
            }else{
                $('.active').next().click();
            }
        },1000);
    }
    
    $('.gallery-container .main-img').hover(function () {
        clearInterval(autoChangeSlide);
    },function(){
        startautoChangeSlide();
    });
    */
    //startautoChangeSlide();
//----------------------------------------------------------------------------------    
    
    $('#myCarousel').carousel();
    
    $(".live").keyup(function (){
        $($(this).data('class')).text($(this).val());
    })
    
    $('.login-signup h1 span').click(function(){
        if($(this).data('class') == 'login'){
            $('.login-signup .xx').css("background","#6d95b7");   
        }else{
            $('.login-signup .xx').css("background","#6cbd6c");
        }
        $(this).addClass('show-form').siblings().removeClass('show-form');
        $('.login-signup .form').hide();
        $('.login-signup .' + $(this).data('class')).fadeIn(200);
    });
    
//---------------------------------------------------------------------------------- 
    //Calls the selectBoxIt method on your HTML select box.
    $(".form-group select").selectBoxIt({
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
    
  /*  
   $('.categories .selectboxit-container ul li.selectboxit-option').on('click',function(){
    //console.log($(this).data('val'));
       var subcat = ".subcat_" + parseInt($(this).data('val'));
       $('.subcategory').hide();
       $(subcat).show();
       
   });*/ 
//----------------------------------------------------------------------------------    
    // hide placeholder on form focus
    
    $('[placeholder]').focus(function () {
        $(this).attr('data-text',$(this).attr('placeholder'));
        $(this).attr('placeholder','');
    }).blur(function () {
        $(this).attr('placeholder',$(this).attr('data-text'));
    });
//----------------------------------------------------------------------------------    
    
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
//----------------------------------------------------------------------------------    
    //confirmation message on button 
    
    $('.confirm').click( function() {
        return confirm('Are You Sure ?');
    });
//----------------------------------------------------------------------------------
    

//----------------------------------------------------------------------------------    
$('.comments-container img').hover(function(e){
        e.preventDefault();

        //console.log($(this).data('user'));

        var data = {
          "action": "user",
          "User_ID": $(this).data('user')   
        };
        data = $.param(data);

          //console.log(data);

        $.ajax({
          type: "POST",
          dataType: "json",
          url: "response.php", //Relative or absolute path to response.php file
          data: data,
          success: function(data) {
              console.log(data);
                $(".the-return .user-" + data['UserID'] + "  .full-name").html(
                    "<span class='first'><i class='fa fa-user fa-fw'></i> Full Name </span><span class='second'>" + data["Fullname"] + '</span>'
                );

                $(".the-return .user-" + data['UserID'] + "  .id-name").html(
                    "<span class='first'><i class='fa fa-id-card fa-fw'></i> Identifier Name </span><span class='second'>" + data["Username"] + '</span>'
                );

                $(".the-return .user-" + data['UserID'] + "  .adress").html(
                    "<span class='first'><i class='fa fa-map-marker  fa-fw'></i> Adress </span><span class='second'> " + data["Adress"] + '</span>'
                );
                $(".the-return .user-" + data['UserID'] + "  .email").html(
                    "<span class='first'><i class='fa fa-envelope-o  fa-fw'></i>  Email </span><span class='second'>" + data["Email"] + '</span>'
                );
                $(".the-return .user-" + data['UserID'] + "  .phone").html(
                    "<span class='first'><i class='fa fa-phone fa-fw'></i> Phone Number </span><span class='second'>" + data["Phone_Number"] + '</span>'
                );


                //alert("Form submitted successfully.\nReturned json: " + data["Username"]);
          },
            error: function(data){
                $(".comments-container .the-return").html(
                    "no" 
                );
            }
        });

        $(this).parentsUntil('.row').find('.the-return').fadeIn();
        return false;
    
    },function(){
        $(this).parentsUntil('.row').find('.the-return').fadeOut();
});
//----------------------------------------------------------------------------------
    // حذف الكوكيز recently
    $('.recently-content h3 span').on('click', function(){
        document.cookie = "recently=; expires=Thu, 01 Jan 1970 00:00:00 GMT; path=/";
        $('.recently-content').html('');
    });
    
    // وضع الكوكيز تغير نمط الموقع اذا غامق أو فاتح 
    
    function create_cookie(name, value, days2expire, path) {
        var date = new Date();
        date.setTime(date.getTime() + (days2expire * 24 * 60 * 60 * 1000));
        var expires = date.toUTCString();
        document.cookie = name + '=' + value + ';'
                          'expires=' + expires + ';'
                          'path=' + path + ';';
    }
    
    $('.layout-change .light-mode').on('click', function (){
        create_cookie('layout', 'light', 30, '/');
        location.reload();
    });
    $('.layout-change .dark-mode').on('click', function (){
        create_cookie('layout', 'dark', 30, '/');
        location.reload();
    });    
    /*
    The reload() function takes an optional parameter that can be set
    to true to force a reload from the server rather than the cache.
    The parameter defaults to false,
    so by default the page may reload from the browser's cache.    
    */
//----------------------------------------------------------------------------------    
   
   $('.categories .selectboxit-container ul li.selectboxit-option').on('click',function(){
    //console.log($(this).data('val'));
       
       var data = {
           "action" : "category",
           "Cat_ID" : $(this).data('val')
       };
       data = $.param(data);
       $.ajax({
           type : "POST",
           datatye : "HTML",
           url : "response.php",
           data : data,
           success : function(data){
               $('.interactive-content #subcategory').html(
                   data
               );
               // كتبت الكود هنا لان هذه النتيجة لا تظهر في الكود سورس في صفحة اضافة منتج
               $('.interactive-content #subcategory .form-group .input-group-btn ul li').on('click',function(){
                   
                   $('.interactive-content #subcategory .form-group .input-group-btn ul li').each(function(){
                       $(this).find('input').prop('checked', false);
                   });
                   
                   $(this).find('input').prop('checked', true);
                   var subCatName = $(this).find('label').html();
                    $('.interactive-content #subcategory .form-group .input-group-btn button').html(subCatName + '<span class="caret"></span>');
               });
           },
           error : function(data){
               $('.interactive-content').html(
                   "no"
               );
           }
       });
   }); 

   // هنا في صفحة تعديل منتج تكون الاقسام الفرعية ظاهرة في الكود سورس 
   $('.interactive-content  .form-group .input-group-btn ul li').on('click',function(){

       $('.interactive-content  .form-group .input-group-btn ul li').each(function(){
           $(this).find('input').prop('checked', false);
       });

       $(this).find('input').prop('checked', true);
       var subCatName = $(this).find('label').html();
        $('.interactive-content  .form-group .input-group-btn button').html(subCatName + '<span class="caret"></span>');
   });
//---------------------------------------------------------------------------------- 
    // الكود الخاص بجلب رسائل كل محادثة 
    
    var autoChange;
    var autoUnreaded;
    var browser = $('.messages-panel .messages-chat .messages-dialog').data('val');
       
    $('.messages-panel .messages-sender-list .messages-sender').on('click', function() {
        
        $(this).addClass('active').siblings().removeClass('active');
        var data = {
            "action"  : "chat",
            "Chat_ID" : $(this).data('chat') 
        };
        //console.log(data);
        data = $.param(data);
        $.ajax({
            type : "POST",
            datatype : "HTML",
            url : 'response.php',
            data : data,
            success : function(data){
                $('.messages-panel .messages-chat .messages-dialog .messages').html(data); 
                $('.messages-panel .messages-chat .messages-dialog .show-more-messages').show();
                // اذا كنت فاتح محادثة وبعدين فتت عمحادثة تانية لازم صفر قيمة ماسيج نمبر
                $('.messages-panel .messages-chat .messages-dialog .show-more-messages').data('mesnum',0);
                // حذف السبان الذي يعرض عدد الرسائل الغير مقروئة
                $(".messages-panel .messages-sender-list .messages-sender.active .badge").hide();
                
                //console.log($('.messages-panel .messages-chat .messages-dialog .messages').innerHeight());
                $('.messages-panel .messages-chat .messages-dialog').animate({
                    scrollTop : $('.messages-panel .messages-chat .messages-dialog .messages').innerHeight() + 1000
                });
                
                clearInterval(autoChange);
                startAutoChange();
            },
            error : function(data){
                console.log("nnn");
            } 
        });

    }); 
    
    // عرض الرسائل بعد كبس زر شو مور
    $('.messages-panel .messages-chat .messages-dialog .show-more-messages').on('click', function() {
        
        //var mesnum = $(this).data('mesnum') + 7;
        var mesnum = $('.messages-panel .messages-chat .messages-dialog .messages .message').length;
        var dataChat = $('.messages-panel .messages-sender-list .messages-sender.active').data("chat");
        
        var data = {
            "action"  : "chat",
            "Chat_ID" : dataChat,
            "mesnum"  : mesnum
        };
        //console.log($('.messages-panel .messages-chat .messages-dialog .messages .message').length);
        data = $.param(data);
        $.ajax({
            type : "POST",
            datatype : "HTML",
            url : 'response.php',
            data : data,
            success : function(datax){
                $('.messages-panel .messages-chat .messages-dialog .messages').prepend('<div class="messages-block">' + datax + '</div>');
                //$('.messages-panel .messages-chat .messages-dialog .show-more-messages').data('mesnum',mesnum);
                //$('.messages-panel .messages-chat .messages-dialog .show-more-messages').hide();
                if(datax == ''){
                    //  اذا لم يجد رسائل لعرضها فأنه يخفي زر الشومور 
                    $('.messages-panel .messages-chat .messages-dialog .show-more-messages').hide();
                }
            },
            error : function(data){
                console.log("mesnum");
            } 
        });

    });    
    //////////////////////////////////////////////////

    if ($('title').text() == "Messages"){
    //اذا فات عصفحة الرسائل من رابط من صفحة تاجر ما
    //لازم خليه يكبس عاسم المحادثة مشان فعل استقبال الرسائل بشكل تلقائي        
        if($('.messages-panel .messages-sender-list .messages-sender.active').data("chat") ){
            $('.messages-panel .messages-sender-list .messages-sender.active').click();   
        }
    }
    
    //////////////////////////////////////////////////
    function startAutoChange(){
        autoChange = setInterval(function(){
            // تابع استقبال الرسائل للمحادثة المفتوحة بشكل تلقائي
            var dataChat = $('.messages-panel .messages-sender-list .messages-sender.active').data("chat");
            var data = {
                "action" : "receiveMessage",
                "Chat_ID" : dataChat
            };
            data = $.param(data);
            $.ajax({
                type : "POST",
                datatype : "HTML",
                url : "response.php",
                data : data,
                success : function(data){
                    $('.messages-panel .messages-chat .messages-dialog .messages').append(data);
                },
                error : function(data){
                    
                }
            });
            
        },3000);
        // تابع تحديث عدد الرسائل الغير مقروئة لكل محادثة
        
        autoUnreaded = setInterval(function(){
            
            var data = {
                "action" : "unreadedMessage",
                "browser" : browser
            };
            data = $.param(data);
            $.ajax({
                type : "POST",
                datatype : "JSON",
                url : "response.php",
                data : data,
                success : function(data){
                    var counter = 0;
                    // بما ان البيانات قد اتت بتنسيق جسون بعد ان كانت مصفوفة
                    //بالعادة بجيب نتائج استعلام كجسون
                    //هالمرة جبت مصفوفة كجسون لهس استخدمت التابع تحت
                    var json = $.parseJSON(data);
                    console.log(data);
                    console.log(json);
                    $('.messages-panel .messages-sender-list .messages-sender').each(function(){
                        //console.log("browser");
                        if(json['count_' + counter] != 0){
                            $(this).find('.badge-container').html('<span class="badge badge-primary"> ' + json['count_' + counter] + '</span>');    
                        }else{
                            $(this).find('.badge-container').html('');
                        }                                                
                        counter += 1;
                    });
                   
                },
                error : function(data){
                    
                }
            });
            
        },60000);        
    }
    
    /////////////////////////////////////////////////
    // الكود الخاص بارسال الرسائل
  $('.messages-panel .messages-chat .messages-form button').on('click', function(e) {
      e.preventDefault();      
      //console.log($('.messages-panel .messages-chat .messages-form textarea').val());
      var message = $('.messages-panel .messages-chat .messages-form textarea').val();
      var dataChat = $('.messages-panel .messages-sender-list .messages-sender.active').data("chat");
      //console.log(dataChat);
      var data = {
          "action" : "sendMessage",
          "Chat_ID" : dataChat,
          "message" : message
      };
      data = $.param(data);
      $.ajax({
          type : "POST",
          datatype : "HTML",
          url : 'response.php',
          data : data,
          success : function(data){
              $('.messages-panel .messages-chat .messages-form textarea').val('');
              $('.messages-panel .messages-chat .messages-dialog .messages').append(data);
          },
          error : function(data){
              
          }
      });
      
  });  
    
    
//----------------------------------------------------------------------------------    
/*    
$('.interactive-content .subcategory .form-group .col-md-8 .input-group-btn ul li').on('click',function(){
    console.log('mdfds');
});    
*/ 
//----------------------------------------------------------------------------------    
        // subscribe now btn 
    $('.subscribe .subscribe-now').on('click', function(){
        var User_ID = $(this).data('user');
        var Shop_ID = $(this).data('shop');
    
        var data = {
            "action"  : "subscribeNow",
            "User_ID" : User_ID,
            "Shop_ID" : Shop_ID
        };
        
        data = $.param(data);
        $.ajax({
            type : "POST",
            dataType : "HTML",
            url : "response.php",
            data :data,
            success :function(data){
                // هنا تم تحديث محتوى الديف ووضع ايقونتي الجرس وبشكل افتراضي
                // ايقونة عدم تلقي الاشعارات تكون ظاهرة
                // وايقونة تلقي الاشعرات تكون مخفية
                $('.subscribe').html(data);
                
                $('.subscribe .subscribed i').on('click', function(){
                    var User_ID = $('.subscribe .subscribed').data('user');
                    var Shop_ID = $('.subscribe .subscribed').data('shop');

                    var data = {
                        "action"  : "bell",
                        "User_ID" : User_ID,
                        "Shop_ID" : Shop_ID
                    };

                    data = $.param(data);
                    $.ajax({
                        type : "POST",
                        dataType : "HTML",
                        url : "response.php",
                        data :data,
                        success :function(data){
                            $('.subscribe .subscribed .active').removeClass('active').siblings().addClass('active');
                        },
                        error : function(data){

                        }
                    });

                });
            },
            error : function(data){
                
            }
        });
    
    });
    
//----------------------------------------------------------------------------------
    //عند كبس زر الجرس لتفعيل تلقي الأشعارات 
    
    $('.subscribe .subscribed i').on('click', function(){
        var User_ID = $('.subscribe .subscribed').data('user');
        var Shop_ID = $('.subscribe .subscribed').data('shop');
    
        var data = {
            "action"  : "bell",
            "User_ID" : User_ID,
            "Shop_ID" : Shop_ID
        };
        
        data = $.param(data);
        $.ajax({
            type : "POST",
            dataType : "HTML",
            url : "response.php",
            data :data,
            success :function(data){
                // ايقونة عدم تلقي الاشعارات تصبح مخفية
                // وايقونة تلقي الاشعرات تصبح ظاهرة                
                $('.subscribe .subscribed .active').removeClass('active').siblings().addClass('active');
            },
            error : function(data){
                
            }
        });
    
    });
    
//----------------------------------------------------------------------------------    
    $('.preventDefaultTags').click(function(e){
        e.preventDefault();
    });
//----------------------------------------------------------------------------------
    /* Requsets Show More Information function */
    
    $('.request-show .request-showmore').on('click', function(){
        $(this).css('display' , 'none');
        $(this).siblings('.request-show .request-details').fadeIn(200);
    });
    
    $('.item-cart .request-control').on('click', function(){
        if($(this).hasClass('accepted')){
            console.log("yes");
        }else{
            var Request_ID = $(this).data('request');
            var data = {
                "action" : 'requestAdd',
                "Request_ID" : Request_ID
            };
            data = $.param(data);
            $.ajax({
                type : "POST",
                dataType : "HTML",
                url : "response.php",
                data : data,
                success : function(data){
                    $('.item-cart .request-control').each(function(){
                        if($(this).data('request') == Request_ID){
                            $(this).removeClass('accept').addClass('accepted');
                            $(this).html(data);
                        }
                    });
                },
                error : function(data){

                }
            });
        }
    });
});
