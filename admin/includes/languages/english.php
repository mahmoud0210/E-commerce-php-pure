<?php 

    function lang( $phrase ){
        
        static $lang = array(
            
            // control button
            
            'EDIT'      => 'Edit',
            'ACTIVATE'  => 'Activate',
            'APPROVE'   => 'Approve',
            'DELETE'    => 'Delete',
            'UPDATE'    => 'Update',
            'ALL'       => 'All',
            'IDENTIFIER_NAME' => 'Identifier Name',
            'REGISTERD_DATE'  => 'Registerd Date',
            'EMAIL'     => 'Email',
            'CONTROL'   => 'Control',
            'PASSWORD'  => 'Password',
            'ENGLISH_LANG' => 'English',
            'ARABIC_LANG'  => 'Arabic',
            //
            
            'MESSAGE' => 'Welcome',
            'ADMIN'   => 'Admin',
            
            // index 
            
            'ADMIN_LOGIN' => 'Admin Login',
            'ADMIN_USER_PLACEHOLDER' => 'User Name To Login',
            'ADMIN_PASS_PLACEHOLDER' => 'Password To Login',
            'LOGIN' => 'Login',
            'OR'    => 'or',
            
            // navbar
            
            'HOME_PAGE'     => 'Home',
            'CHARTS'        => 'Charts',
            'CATEGORIES'    => 'Categories',
            'ITEMS'         => 'Items',
            'MEMBERS'       => 'Members',
            'SHOPS'         => 'Shops',
            'COMMENTS'      => 'Comments',
            'CONTACT'       => 'Contact',
            'VISIT_SHOP'    => 'Visit Shop',
            'EDIT_PROFILE'  => 'Edit Profile',
            'SETTINGS'      => 'Settings',
            'LOGOUT'        => 'Logout',
            
            // Dashboard
            
            'DASHBOARD'     => 'Dashboard',
            'TOTAL_MEMBERS' => 'Total Members',
            'PENDING_SHOPS' => 'Pending Shops',
            'TOTAL_ITEMS'   => 'Total Items',
            'TOTAL_COMMENTS'=> 'Total Comments',
        
            // Categories
            
            'MANAGE_CATEGORIES' => 'Manage Categories',
            'ADD_NEW_CATEGORY'  => 'Add New Category',
            'HIDDEN'            => 'Hidden',
            'NO_COMMENT'        => 'No Comment',
            'NO_ADS'            => 'No ADS',
            
            // Categories -> Add
            
            'NAME_CAT'              => 'Name',
            'PLACEHOLDER_NAME_CAT'  => 'Name of the category',
            'DESCRIPTION_CAT'       => 'Description',
            'PLACEHOLDER_DESC_CAT'  => 'Describe the category',
            'PARENT_?'              => 'Parent ?',
            'ORDERING'              => 'Ordering',
            'PLACEHOLDER_ORDERING_CAT' => 'Number to arrange the categories',
            'VISIBLE'               => 'Visible',
            'ALLOW_COMMENTING'      => 'Allow Commenting',
            'ALLOW_ADS'             => 'Allow ADS',
            
            // Categories -> insert
            
            'INSERT_CATEGORY' => 'Insert Category',
            
            // Categories -> edit
            
            'EDIT_CATEGORY'     => 'Edit Category',
            'CATEGORY_HAS_CHILDS' => 'This category has childs categories',
            
            // Categories -> update
            
            'UPDATE_CATEGORY' => 'Update Category',
            
            // Categories -> delete
            
            'DELETE_CATEGORY' => 'Delete Category',
            
            // Items
            
            'MANAGE_ITEMS'  => 'Manage Items',
            'ADD_NEW_ITEM'  => 'Add New Item',
            '#ID'           => '#ID',
            'NAME_ITEM'     => 'Item Name',
            'PRICE'         => 'Price',
            'ADD_DATE'      => 'Add Date',
            'COUNTRY_MADE'  => 'Country Made',
            'STATUS'        => 'Status',
            'CATEGORY'      => 'Category',
            'SHOPS'         => 'Shops',
            
            'DELETE_ITEM'   => 'Delete Item',
            'APPROVE_ITEM'  => 'Approve Item',
            
            // Members
            
            'MANAGE_MEMBERS' => 'Manage Members',
            'MEMBER_NAME'    => 'Member Name',
            'DELETE_MEMBER'  => 'Delete Member',
            'ACTIVATE_MEMBER'=> 'Activate Member',
            'EDIT_MEMBER'    => 'Edit Member',
            'PLACEHOLDER_PASSWORD_MEMBER' => 'Leave This If You Dont Want To Change',
            'IMAGE'          => 'Image',
            'UPDATE_MEMBER'  => 'Update Member',
            
            // Shops
            
            'MANAGE_SHOPS'   => 'Manage Shops',
            'SHOP_NAME'      => 'Shop Name',
            'ADRESS'         => 'Adress',
            'DELETE_SHOP'    => 'Delete Shop',
            'ACTIVATE_SHOP'  => 'Avtivate Shop',
            
            // Comments
            
            'MANAGE_COMMENTS' => 'Manage Comments',
            'COMMENT'         => 'Comment',
            'DELETE_COMMENT'  => 'Delete Comment',
            'APPROVE_COMMENT' => 'Approve Comment',
            
            // Contact
            
            'CONTACT_US_MESSAGES' => 'Contact Us Messages',
            'SENDER_NAME'         => 'Sender Name',
            'SUBJECT'             => 'Subject',
            'CONTENT'             => 'Content',
            'REPLAY_NOW'          => 'Replay Now',
            'REPLIED'             => 'Replied',
            'ADMIN_REPLAY'        => 'Admin Replay',
            'REPLAY'              => 'Replay',
            'SEND_SUCCESS'        => '<strong>SUCCESS : </strong>The Message Has Been Sent',
            'SEND_ERROR'          => '<strong>ERROR : </strong>The Message Has Not Been Sent',
            
            // Setting
            
            'SETTING'           => 'Setting',
            'LEFT_ITEM'         => 'Left Item',
            'LEFT_ITEM_DESC'    => 'This is the item under categories in index page',
            'CATEGORY_TOP'      => 'The Category On Top',
            'CATEGORY_TOP_DESC' => 'The items from this catigories well be showen under new items slide',
            'THREE_ITEMS'       => 'Three Items',
            'THREE_ITEMS_DESC'  => 'The items well be showen under category slide wetch selected from previous input',
            'CATEGORY_DOWN'     => 'The Category On Down',
            'CATEGORY_DOWN_DESC'=> 'The items from this catigories well be showen under previous three items',
            'SPECIAL_SHOP'      => 'Special Shop',
            'UPDATE_INDEX_PAGE' => 'Update Index Page',
            
            'MAIN_SLIDE'        => 'Main Slide',
            'CHECK_SLIDE'       => 'Check Slide',
            'ADS_SLIDE'         => 'In Main Slide',
            'UPDATE_MAIN_SLIDE' => 'Update Main Slide',
            
        );
        return $lang[$phrase];
        
    }