<?php 

    function lang( $phrase ){
        
        static $lang = array(
            
            // control button
            
            'EDIT'      => 'تعديل',
            'ACTIVATE'  => 'تفعيل',
            'APPROVE'   => 'قبول',
            'DELETE'    => 'حذف',
            'UPDATE'    => 'تحديث',
            'ALL'       => 'الكل',
            'IDENTIFIER_NAME' => 'الاسم المعرف',
            'REGISTERD_DATE'  => 'تاريخ التسجيل',
            'EMAIL'     => 'الإيمل',
            'CONTROL'   => 'التحكم',
            'PASSWORD'  => 'كلمة السر',
            'ENGLISH_LANG' => 'أنكليزي',
            'ARABIC_LANG'  => 'عربي',            
            //
            
            'MESSAGE' => 'Welcome',
            'ADMIN'   => 'المدير',
            
            // index 
            
            'ADMIN_LOGIN' => 'تسجيل دخول الأدمن',
            'ADMIN_USER_PLACEHOLDER' => 'اسم المستخدم لتسجيل الدخول',
            'ADMIN_PASS_PLACEHOLDER' => 'كلمة المرور لتسجيل الدخول',
            'LOGIN' => 'تسجيل الدخول',
            'OR' => 'أو',
            
            // navbar
            
            'HOME_PAGE'     => 'الصفحة الرئيسية',
            'CHARTS'        => 'مخططات',
            'CATEGORIES'    => 'الأصناف',
            'ITEMS'         => 'المنتجات',
            'MEMBERS'       => 'الأعضاء',
            'SHOPS'         => 'المتاجر',
            'COMMENTS'      => 'التعليقات',
            'CONTACT'       => 'التواصل',
            'VISIT_SHOP'    => 'الذهاب الى الموقع',
            'EDIT_PROFILE'  => 'تعديل الملف الشخصي',
            'SETTINGS'      => 'الأعدادات',
            'LOGOUT'        => 'تسجيل الخروج',
            
            // Dashboard
            
            'DASHBOARD'     => 'لوحة التحكم',
            'TOTAL_MEMBERS' => 'كل الأعضاء',
            'PENDING_SHOPS' => 'المتاجر الغير مفعلة',
            'TOTAL_ITEMS'   => 'كل المنتجات',
            'TOTAL_COMMENTS'=> 'كل التعليقات',
        
            // Categories
            
            'MANAGE_CATEGORIES' => 'إدارة الأصناف',
            'ADD_NEW_CATEGORY'  => 'إضافة صنف جديد',
            'HIDDEN'            => 'مخفي',
            'NO_COMMENT'        => 'لا تعليقات',
            'NO_ADS'            => 'لا منتجات',
            
            // Categories -> Add
            
            'NAME_CAT'              => 'الأسم',
            'PLACEHOLDER_NAME_CAT'  => 'اسم الصنف الجديد',
            'DESCRIPTION_CAT'       => 'الوصف',
            'PLACEHOLDER_DESC_CAT'  => 'وصف للصنف الجديد',
            'PARENT_?'              => 'الأصناف الأباء',
            'ORDERING'              => 'الترتيب',
            'PLACEHOLDER_ORDERING_CAT' => 'رقم لترتيب الأصناف',
            'VISIBLE'               => 'مرئي',
            'ALLOW_COMMENTING'      => 'السماح بالتعليقات',
            'ALLOW_ADS'             => 'السماح بالمنتجات',
            
            // Categories -> insert
            
            'INSERT_CATEGORY' => 'إضافة صنف',
            
            // Categories -> edit
            
            'EDIT_CATEGORY'     => 'تعديل صنف',
            'CATEGORY_HAS_CHILDS' => 'هذا الصنف يحوي على اصناف أولاد',
            
            // Categories -> update
            
            'UPDATE_CATEGORY' => 'تحديث معلومات الصنف',
            
            // Categories -> delete
            
            'DELETE_CATEGORY' => 'حذف صنف',
            
            // Items
            
            'MANAGE_ITEMS'  => 'إدارة المنتجات',
            'ADD_NEW_ITEM'  => 'إضافة منتج جديد',
            '#ID'           => '#ID',
            'NAME_ITEM'     => 'اسم المنتج',
            'PRICE'         => 'السعر',
            'ADD_DATE'      => 'تاريخ الإضافة',
            'COUNTRY_MADE'  => 'بلد الصنع',
            'STATUS'        => 'الحالة',
            'CATEGORY'      => 'الصنف',
            //'SHOPS'         => 'Shops',
            
            'DELETE_ITEM'   => 'حذف منتج',
            'APPROVE_ITEM'  => 'الموافقة على منتج',
            
            // Members
            
            'MANAGE_MEMBERS' => 'إدارة الأعصاء',
            'MEMBER_NAME'    => 'أسم العضو',
            'DELETE_MEMBER'  => 'حذف العضو',
            'ACTIVATE_MEMBER'=> 'تفعيل العضو',
            'EDIT_MEMBER'    => 'تعديل العضو',
            'PLACEHOLDER_PASSWORD_MEMBER' => 'اترك حقل كمة المرور فارغ اذا لا تريد تفييرها',
            'IMAGE'          => 'الصورة',
            'UPDATE_MEMBER'  => 'تحديث معلومات العضو',
            
            // Shops
            
            'MANAGE_SHOPS'   => 'إدارة المتاجر',
            'SHOP_NAME'      => 'اسم المتجر',
            'ADRESS'         => 'العنوان',
            'DELETE_SHOP'    => 'حذف المتجر',
            'ACTIVATE_SHOP'  => 'تفعيل المتجر',
            
            // Comments
            
            'MANAGE_COMMENTS' => 'إدارة التعليقات',
            'COMMENT'         => 'التعليق',
            'DELETE_COMMENT'  => 'حذف التعليق',
            'APPROVE_COMMENT' => 'قبول التعليق',
            
            // Contact
            
            'CONTACT_US_MESSAGES' => 'رسائل التواصل معنا',
            'SENDER_NAME'         => 'اسم المرسل',
            'SUBJECT'             => 'موضوع الرسالة',
            'CONTENT'             => 'محتوى الرسالة',
            'REPLAY_NOW'          => 'رد الأن',
            'REPLIED'             => 'تم الرد',
            'ADMIN_REPLAY'        => 'رد الأدمن',
            'REPLAY'              => 'الرد',
            'SEND_SUCCESS'        => '<strong>نجاح : </strong>لقد تم إرسال الرسالة',
            'SEND_ERROR'          => '<strong>فشل : </strong>لم يتم إرسال الرسالة',
            
            // Setting
            
            'SETTING'           => 'الأعدادت',
            'LEFT_ITEM'         => 'المنتج اليساري',
            'LEFT_ITEM_DESC'    => 'هذا المنتج سوف يظهر تحت الأصناف في الصفحة الرئيسة',
            'CATEGORY_TOP'      => 'الصنف في الأعلى',
            'CATEGORY_TOP_DESC' => 'المنتجات الخاصة بهذا الصنف سوف تظهر تحت سلايد المنتجات الجديدة',
            'THREE_ITEMS'       => 'المنتجات الثلاثة',
            'THREE_ITEMS_DESC'  => 'هذه المنتجات سوف تظهر تحت السلايد الخاص بالصنف الذي تم اختياره في الخطوة السابقة',
            'CATEGORY_DOWN'     => 'الصنف في الأسفل',
            'CATEGORY_DOWN_DESC'=> 'المنتجات الخاصة بهذا الصنف سوف تظهر تحت المنتجات الثلاث السابقة',
            'SPECIAL_SHOP'      => 'متجر مميز',
            'UPDATE_INDEX_PAGE' => 'تحديث الصفحة الرئيسية',
            
            'MAIN_SLIDE'        => 'السلايد الأساسي',
            'CHECK_SLIDE'       => 'أختيار السلايد',
            'ADS_SLIDE'         => 'موجودة في السلايد الأساسي',
            'UPDATE_MAIN_SLIDE' => 'تحديث السلايد الأساسي',
            
        );
        return $lang[$phrase];
        
    }