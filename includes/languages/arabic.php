<?php 

    function lang( $phrase ){
        
        static $lang = array(
            
            //Control
            'ADD' => 'إضافة',
            'ADDED' => 'تم إضافته',
            'DELETE'    => 'حذف',
            'UPDATE'    => 'تحديث',
            'DELETE_ALL' => 'حذف الكل',
            
            //             
            'MESSAGE' => 'مرحبا',
            'ADMIN' => 'المدير',
            
            // navbar
            'HOME_PAGE' => 'الصفحة الرئيسية',
            'CATEGORIES' => 'الأصناف',
            'ALL_CATEGORIES' => 'كل الأصناف',
            'BASKET' => 'السلة الشرائية',
            'ACCOUNT' => 'الحساب',
            'CHATS' => 'المحادثات',
            'LOGOUT' => 'تسجيل الخروج',
            'MY_SHOP' => 'متجري',
            'ADD_ITEM' => 'إضافة منتج',
            'ADD_REQUEST' => 'إضافة طلب',
            'LOGIN_SIGNUP' =>'التسجيل',
            'SELECT_CATEGORY' => 'اختر صنف',
            'ALL' => 'الكل',
            'SEARCH_PLACEHOLDER' => 'أبحث عن المنتجات',
            'LIGHT' => 'فاتح',
            'DARK' => 'غامق',

            // Index 
            'NEW_ITEMS' => 'منتجات جديدة',
            'SPECIAL_ITEMS' => 'منتجات مميزة',
            
            // Shop Cart
            'ERROR_QUANTITY' => 'الكمية المتاحة من المنتج  ',
            'ERROR_IS' => ' هي ',
            'SUCCESS_1' => ' انت اشتريت ',
            'SUCCESS_2' => ' منتجات من ',
            'SHOP_CART' => 'السلة الشرائية',
            'PRICE_ONE_ITEM_IS_:' => ' : سعر منتج واحد هو ',
            '|_PRICE' => ' | سعر',
            'ITEMS_IS_:' => ' منتجات هو : ',
            'LOGIN_OR_SIGNUP' => 'سجل دخول أو أنشىء حساب جديد',
            'LOGOUT_FIRST' => ' سجل الخروج ثم سجل الدخول كمستخدم عادي ',
            'CLICK_TO_BUY' => 'أضغط للشراء',
            'P_1' => 'انت لم تختار اي منتج لشرائه',
            'P_2' => 'عد الى الموقع واملىء السلة ببعض المنتجات',
            'P_3' => 'ثم بضع ساعات وطلبك سوف يصل الى المنزل',
            
            //contact 
            'CONTACT_ERROR_NAME' => ' يجب أن تكتب <strong>أسمك</strong>',
            'CONTACT_ERROR_EMAIL_EMPTY' => ' يجب أن تكتب <strong>إيميلك</strong>',
            'CONTACT_ERROR_EMAIL_VALIDATE' => '<strong> إيميلك </strong> غير <strong> صالح </strong>',
            'CONTACT_ERROR_SUBJECT' => ' يجب أن تكتب <strong>موضوع</strong> الرسالة',
            'CONTACT_ERROR_CONTENT' => ' يجب أن تكتب <strong>محتوى</strong> الرسالة',
            'CONTACT_SUCCESS' => 'رسالتك تم ارسالها وقريبا سوف نرد عليها',
            'FOR_MORE_INFORMATION' => 'معلومات أكثر عنا',
            'CONTACT_TELEPHONE' => 'هاتف',
            'CONTACT_EMAIL' => 'ايميل',
            'CONTACT_LOCATION' => 'الموقع',
            'CONTACT_US_FORM' => 'تواصل معنا',
            'CONTACT_US_FORM_NAME' => 'ادخل اسمك',
            'CONTACT_US_FORM_EMAIL' => 'ادخل ايميلك',
            'CONTACT_US_FORM_SUBJECT' => 'الموضوع',
            'CONTACT_US_FORM_MESSAGE' => 'الرسالة',
            'CONTACT_US_FORM_SEND' => 'ارسال',
            
        );
        return $lang[$phrase];
        
    }