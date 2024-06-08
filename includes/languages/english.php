<?php 

    function lang( $phrase ){
        
        static $lang = array(
            
            //Control
            'ADD'       => 'Add',
            'ADDED'     => 'Added',
            'DELETE'    => 'Delete',
            'UPDATE'    => 'Update',
            'DELETE_ALL' => 'Delete All',
            
            // 
            'MESSAGE' => 'Welcome',
            'ADMIN' => 'administrator',
            
            // navbar
            'HOME_PAGE' => 'Home',
            'CATEGORIES' => 'Categories',
            'ALL_CATEGORIES' => 'All Categories',
            'BASKET' => 'Basket',
            'ACCOUNT' => 'Account',
            'CHATS' => 'Charts',
            'LOGOUT' => 'Logout',
            'MY_SHOP' => 'My Shop',
            'ADD_ITEM' => 'Add Item',
            'ADD_REQUEST' => 'Add Request',
            'LOGIN_SIGNUP' =>'Login | Signup',
            'SELECT_CATEGORY' => 'Select Category',
            'ALL' => 'All',
            'SEARCH_PLACEHOLDER' => 'Search For Items',
            'LIGHT' => 'Light',
            'DARK' => 'Dark',
            
            // Index 
            'NEW_ITEMS' => 'New items',
            'SPECIAL_ITEMS' => 'Special Items',
            
            // Shop Cart
            'ERROR_QUANTITY' => 'The Available Quantity Of The Product ',
            'ERROR_IS' => ' IS ',
            'SUCCESS_1' => 'You Buy ( ',
            'SUCCESS_2' => ' ) Items From ',
            'SHOP_CART' => 'Shop Cart',
            'PRICE_ONE_ITEM_IS_:' => 'Price One Item IS : ',
            '|_PRICE' => ' | Price',
            'ITEMS_IS_:' => ' Items Is : ',
            'LOGIN_OR_SIGNUP' => 'Login Or Signup',
            'LOGOUT_FIRST' => 'Logout First Then Login As User',
            'CLICK_TO_BUY' => 'Click To Buy',
            'P_1' => 'You Don\'t Chose Any Product To Buy It',
            'P_2' => 'Return To Website And Shoping Until Fill This Basket',
            'P_3' => 'Then Just Few Hours Then Your Products Are Arrived To Your Home',
            
            //contact 
            'CONTACT_ERROR_NAME' => 'You must write your <strong>NAME</strong>',
            'CONTACT_ERROR_EMAIL_EMPTY' => 'You must write your <strong>EMAIL</strong>',
            'CONTACT_ERROR_EMAIL_VALIDATE' => 'Your <strong> EMAIL </strong> is not <strong> VALIDE </strong>',
            'CONTACT_ERROR_SUBJECT' => 'You must write the <strong>SUBJECT</strong> of message',
            'CONTACT_ERROR_CONTENT' => 'You must write the <strong>CONTENT</strong> of message',
            'CONTACT_SUCCESS' => 'Your message sent and we will replay as soon as we can ',
            'FOR_MORE_INFORMATION' => '<strong>FOR MORE </strong>INFORMATION',
            'CONTACT_TELEPHONE' => 'TELEPHONE',
            'CONTACT_EMAIL' => 'EMAIL',
            'CONTACT_LOCATION' => 'LOCATION',
            'CONTACT_US_FORM' => '<strong>CONTACT </strong>US',
            'CONTACT_US_FORM_NAME' => 'Inter Your Name',
            'CONTACT_US_FORM_EMAIL' => 'Inter Your Email',
            'CONTACT_US_FORM_SUBJECT' => 'Subject',
            'CONTACT_US_FORM_MESSAGE' => 'Message',
            'CONTACT_US_FORM_SEND' => 'SEND',
            
        );
        return $lang[$phrase];
        
    } /* <?php echo lang(''); ?> */