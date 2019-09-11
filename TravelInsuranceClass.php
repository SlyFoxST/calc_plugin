<?php


class TravelInsuranceClass{


    static $class = null;
    public $email;
    public $page;
    public $phone;
    public $data = null;

    static public function init(){
        if(TravelInsuranceClass::$class == null){
            TravelInsuranceClass::$class = new TravelInsuranceClass();
        }
        return TravelInsuranceClass::$class;
    }



    function __construct()
    {

        add_action( 'init', [$this, 'registerPostTypes'] );
        add_action( 'init', [$this, 'registerTaxonomies'] );

        $this->initACF();
        $this->initAdminPage();



        $this->email = get_option('options_yf_tif_settings_email') ? get_option('options_yf_tif_settings_email') : get_option('admin_email');
        $this->phone = get_option('options_yf_tif_settings_phone') ? get_option('options_yf_tif_settings_phone') : '';
        $this->page = get_option('options_yf_tif_settings_form_page') ? get_post(get_option('options_yf_tif_settings_form_page')) : null;



        add_filter('template_include', [$this, 'initForm']);


    }

    public function initACF(){

        $acf_exists = class_exists('acf');

        define( 'MY_ACF_PATH', __DIR__ . '/includes/acf/' );
        define( 'MY_ACF_URL', plugin_dir_url(__FILE__) . 'includes/acf/' );

        include_once( MY_ACF_PATH . 'acf.php' );

        add_filter('acf/settings/url', function($url){
            return MY_ACF_URL;
        });
        add_filter('acf/settings/show_admin', function ( $show_admin )use($acf_exists) {
            return $acf_exists;
        });
        add_filter('acf/settings/save_json', function( $path ) {
            $path = get_stylesheet_directory() . '/includes/acf/acf-json';
            return $path;
        });
        add_filter('acf/settings/load_json', function ( $paths ) {
            unset($paths[0]);
            $paths[] = get_stylesheet_directory() . '/includes/acf/acf-json';
            return $paths;
        });


        if( function_exists('acf_add_local_field_group') ):

            acf_add_local_field_group(array(
                'key' => 'group_5cbd95881dcec',
                'title' => 'Travel insurance - Age Data',
                'fields' => array(
                    array(
                        'key' => 'field_5cbd959a64883',
                        'label' => 'From',
                        'name' => 'from',
                        'type' => 'number',
                        'instructions' => '',
                        'required' => 1,
                        'conditional_logic' => 0,
                        'wrapper' => array(
                            'width' => '',
                            'class' => '',
                            'id' => '',
                        ),
                        'default_value' => '',
                        'placeholder' => '',
                        'prepend' => '',
                        'append' => '',
                        'min' => '',
                        'max' => '',
                        'step' => '',
                    ),
                    array(
                        'key' => 'field_5cbd95a564884',
                        'label' => 'To',
                        'name' => 'to',
                        'type' => 'number',
                        'instructions' => '',
                        'required' => 1,
                        'conditional_logic' => 0,
                        'wrapper' => array(
                            'width' => '',
                            'class' => '',
                            'id' => '',
                        ),
                        'default_value' => '',
                        'placeholder' => '',
                        'prepend' => '',
                        'append' => '',
                        'min' => '',
                        'max' => '',
                        'step' => '',
                    ),
                ),
                'location' => array(
                    array(
                        array(
                            'param' => 'taxonomy',
                            'operator' => '==',
                            'value' => 'yf_tif_age_cat',
                        ),
                    ),
                ),
                'menu_order' => 0,
                'position' => 'normal',
                'style' => 'default',
                'label_placement' => 'top',
                'instruction_placement' => 'label',
                'hide_on_screen' => '',
                'active' => 1,
                'description' => '',
            ));

            acf_add_local_field_group(array(
                'key' => 'group_5cbd8b615a7a9',
                'title' => 'Travel insurance - Company Data',
                'fields' => array(
                    array(
                        'key' => 'field_5cd529704eb40',
                        'label' => 'Hide in main list',
                        'name' => 'hide_in_main_list',
                        'type' => 'true_false',
                        'instructions' => '',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'wrapper' => array(
                            'width' => '',
                            'class' => '',
                            'id' => '',
                        ),
                        'message' => '',
                        'default_value' => 0,
                        'ui' => 0,
                        'ui_on_text' => '',
                        'ui_off_text' => '',
                    ),
                    array(
                        'key' => 'field_5d04b05dc3b82',
                        'label' => 'Limit price by age',
                        'name' => 'limit_prices_by_age',
                        'type' => 'true_false',
                        'instructions' => '',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'wrapper' => array(
                            'width' => '',
                            'class' => '',
                            'id' => '',
                        ),
                        'message' => '',
                        'default_value' => 0,
                        'ui' => 0,
                        'ui_on_text' => '',
                        'ui_off_text' => '',
                    ),
                    array(
                        'key' => 'field_5cbf26c370ce1',
                        'label' => 'Min price',
                        'name' => 'min_price',
                        'type' => 'number',
                        'instructions' => '',
                        'required' => 1,
                        'conditional_logic' => 0,
                        'wrapper' => array(
                            'width' => '',
                            'class' => '',
                            'id' => '',
                        ),
                        'default_value' => '',
                        'placeholder' => '',
                        'prepend' => '',
                        'append' => '',
                        'min' => 0,
                        'max' => '',
                        'step' => '',
                    ),
                    array(
                        'key' => 'field_5cbf26d670ce2',
                        'label' => 'Max price',
                        'name' => 'max_price',
                        'type' => 'number',
                        'instructions' => '',
                        'required' => 1,
                        'conditional_logic' => 0,
                        'wrapper' => array(
                            'width' => '',
                            'class' => '',
                            'id' => '',
                        ),
                        'default_value' => '',
                        'placeholder' => '',
                        'prepend' => '',
                        'append' => '',
                        'min' => 0,
                        'max' => '',
                        'step' => '',
                    ),
                    array(
                        'key' => 'field_5cbf17a5a2b84',
                        'label' => 'Currency',
                        'name' => 'currency',
                        'type' => 'text',
                        'instructions' => '',
                        'required' => 1,
                        'conditional_logic' => 0,
                        'wrapper' => array(
                            'width' => '',
                            'class' => '',
                            'id' => '',
                        ),
                        'default_value' => '',
                        'placeholder' => '',
                        'prepend' => '',
                        'append' => '',
                        'maxlength' => '',
                    ),
                    array(
                        'key' => 'field_5cbf173ca2b81',
                        'label' => 'Age',
                        'name' => 'age',
                        'type' => 'repeater',
                        'instructions' => '',
                        'required' => 1,
                        'conditional_logic' => 0,
                        'wrapper' => array(
                            'width' => '',
                            'class' => '',
                            'id' => '',
                        ),
                        'collapsed' => '',
                        'min' => 1,
                        'max' => 0,
                        'layout' => 'table',
                        'button_label' => '',
                        'sub_fields' => array(
                            array(
                                'key' => 'field_5cbf1763a2b82',
                                'label' => 'Age',
                                'name' => 'age',
                                'type' => 'taxonomy',
                                'instructions' => '',
                                'required' => 1,
                                'conditional_logic' => 0,
                                'wrapper' => array(
                                    'width' => '80',
                                    'class' => '',
                                    'id' => '',
                                ),
                                'taxonomy' => 'yf_tif_age_cat',
                                'field_type' => 'select',
                                'allow_null' => 0,
                                'add_term' => 1,
                                'save_terms' => 0,
                                'load_terms' => 0,
                                'return_format' => 'object',
                                'multiple' => 0,
                            ),
                            array(
                                'key' => 'field_5cbf177ea2b83',
                                'label' => 'Price',
                                'name' => 'price',
                                'type' => 'number',
                                'instructions' => '',
                                'required' => 1,
                                'conditional_logic' => 0,
                                'wrapper' => array(
                                    'width' => '',
                                    'class' => '',
                                    'id' => '',
                                ),
                                'default_value' => '',
                                'placeholder' => '',
                                'prepend' => '',
                                'append' => '',
                                'min' => 0,
                                'max' => '',
                                'step' => '',
                            ),
                        ),
                    ),
                    array(
                        'key' => 'field_5cbf22ae20d62',
                        'label' => 'Options',
                        'name' => 'option_cats',
                        'type' => 'repeater',
                        'instructions' => '',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'wrapper' => array(
                            'width' => '',
                            'class' => '',
                            'id' => '',
                        ),
                        'collapsed' => '',
                        'min' => 0,
                        'max' => 0,
                        'layout' => 'table',
                        'button_label' => '',
                        'sub_fields' => array(
                            array(
                                'key' => 'field_5cbf22b620d63',
                                'label' => 'Option',
                                'name' => 'option_cat',
                                'type' => 'taxonomy',
                                'instructions' => '',
                                'required' => 1,
                                'conditional_logic' => 0,
                                'wrapper' => array(
                                    'width' => '80',
                                    'class' => '',
                                    'id' => '',
                                ),
                                'taxonomy' => 'yf_tif_option_cat',
                                'field_type' => 'select',
                                'allow_null' => 0,
                                'add_term' => 1,
                                'save_terms' => 0,
                                'load_terms' => 0,
                                'return_format' => 'object',
                                'multiple' => 0,
                            ),
                            array(
                                'key' => 'field_5cbf22df20d64',
                                'label' => 'Price',
                                'name' => 'price',
                                'type' => 'number',
                                'instructions' => '',
                                'required' => 1,
                                'conditional_logic' => 0,
                                'wrapper' => array(
                                    'width' => '',
                                    'class' => '',
                                    'id' => '',
                                ),
                                'default_value' => '',
                                'placeholder' => '',
                                'prepend' => '',
                                'append' => '',
                                'min' => 0,
                                'max' => '',
                                'step' => '',
                            ),
                        ),
                    ),
                    array(
                        'key' => 'field_5cbf168eea7f4',
                        'label' => 'Popup image',
                        'name' => 'popup_image',
                        'type' => 'image',
                        'instructions' => '',
                        'required' => 1,
                        'conditional_logic' => 0,
                        'wrapper' => array(
                            'width' => '',
                            'class' => '',
                            'id' => '',
                        ),
                        'return_format' => 'array',
                        'preview_size' => 'thumbnail',
                        'library' => 'all',
                        'min_width' => '',
                        'min_height' => '',
                        'min_size' => '',
                        'max_width' => '',
                        'max_height' => '',
                        'max_size' => '',
                        'mime_types' => '',
                    ),
                    array(
                        'key' => 'field_5cbf1424dd256',
                        'label' => 'Right text',
                        'name' => 'right_text',
                        'type' => 'textarea',
                        'instructions' => '',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'wrapper' => array(
                            'width' => '33',
                            'class' => '',
                            'id' => '',
                        ),
                        'default_value' => '',
                        'placeholder' => '',
                        'maxlength' => '',
                        'rows' => 4,
                        'new_lines' => '',
                    ),
                    array(
                        'key' => 'field_5cbf1432dd257',
                        'label' => 'Middle text',
                        'name' => 'middle_text',
                        'type' => 'textarea',
                        'instructions' => '',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'wrapper' => array(
                            'width' => '33',
                            'class' => '',
                            'id' => '',
                        ),
                        'default_value' => '',
                        'placeholder' => '',
                        'maxlength' => '',
                        'rows' => 4,
                        'new_lines' => '',
                    ),
                    array(
                        'key' => 'field_5cbf143fdd258',
                        'label' => 'Left text',
                        'name' => 'left_text',
                        'type' => 'textarea',
                        'instructions' => '',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'wrapper' => array(
                            'width' => '33',
                            'class' => '',
                            'id' => '',
                        ),
                        'default_value' => '',
                        'placeholder' => '',
                        'maxlength' => '',
                        'rows' => 4,
                        'new_lines' => '',
                    ),
                    array(
                        'key' => 'field_5d04e22238088',
                        'label' => 'Add maximum trip limit',
                        'name' => 'maximum_trip_limit',
                        'type' => 'text',
                        'instructions' => '',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'wrapper' => array(
                            'width' => '',
                            'class' => '',
                            'id' => '',
                        ),
                        'default_value' => '',
                        'placeholder' => '',
                        'prepend' => '',
                        'append' => '',
                        'maxlength' => '',
                    ),
                    array(
                        'key' => 'field_5d04e708657d7',
                        'label' => 'Maximum limit for the entire trip',
                        'name' => 'maximum_limit_for_the_entire_trip',
                        'type' => 'radio',
                        'instructions' => '',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'wrapper' => array(
                            'width' => '',
                            'class' => '',
                            'id' => '',
                        ),
                        'choices' => array(
                            'min'   => '30 days and less',
                            'max'   => '30 days and more',
                        ),
                        'other_choice' => 0,
                        'save_other_choice' => 1,
                        'layout' => 0,
                    ),
                ),
'location' => array(
    array(
        array(
            'param' => 'post_type',
            'operator' => '==',
            'value' => 'yf_tif_company',
        ),
    ),
),
'menu_order' => 0,
'position' => 'normal',
'style' => 'default',
'label_placement' => 'top',
'instruction_placement' => 'label',
'hide_on_screen' => array(
    0 => 'the_content',
),
'active' => 1,
'description' => '',
));

acf_add_local_field_group(array(
    'key' => 'group_5cc01b5497a96',
    'title' => 'Travel insurance - Insurance order Data',
    'fields' => array(
        array(
            'key' => 'field_5cc01f54fdecf',
            'label' => 'Status',
            'name' => 'status',
            'type' => 'select',
            'instructions' => '',
            'required' => 1,
            'conditional_logic' => 0,
            'wrapper' => array(
                'width' => '',
                'class' => '',
                'id' => '',
            ),
            'choices' => array(
                'new' => 'New',
                'viewed' => 'Viewed',
            ),
            'default_value' => array(
            ),
            'allow_null' => 0,
            'multiple' => 0,
            'ui' => 0,
            'return_format' => 'array',
            'ajax' => 0,
            'placeholder' => '',
        ),
        array(
            'key' => 'field_5cc01cba3947d',
            'label' => 'Total',
            'name' => 'total',
            'type' => 'text',
            'instructions' => '',
            'required' => 1,
            'conditional_logic' => 0,
            'wrapper' => array(
                'width' => '',
                'class' => '',
                'id' => '',
            ),
            'default_value' => '',
            'placeholder' => '',
            'prepend' => '',
            'append' => '',
            'maxlength' => '',
        ),
        array(
            'key' => 'field_5cc01deeeb224',
            'label' => 'Date from',
            'name' => 'date_from',
            'type' => 'text',
            'instructions' => '',
            'required' => 1,
            'conditional_logic' => 0,
            'wrapper' => array(
                'width' => '50',
                'class' => '',
                'id' => '',
            ),
            'default_value' => '',
            'placeholder' => '',
            'prepend' => '',
            'append' => '',
            'maxlength' => '',
        ),
        array(
            'key' => 'field_5cc01df9eb225',
            'label' => 'Date to',
            'name' => 'date_to',
            'type' => 'text',
            'instructions' => '',
            'required' => 1,
            'conditional_logic' => 0,
            'wrapper' => array(
                'width' => '50',
                'class' => '',
                'id' => '',
            ),
            'default_value' => '',
            'placeholder' => '',
            'prepend' => '',
            'append' => '',
            'maxlength' => '',
        ),
        array(
            'key' => 'field_5cc01b68194a1',
            'label' => 'Company',
            'name' => 'company',
            'type' => 'post_object',
            'instructions' => '',
            'required' => 1,
            'conditional_logic' => 0,
            'wrapper' => array(
                'width' => '',
                'class' => '',
                'id' => '',
            ),
            'post_type' => array(
                0 => 'yf_tif_company',
            ),
            'taxonomy' => '',
            'allow_null' => 0,
            'multiple' => 0,
            'return_format' => 'object',
            'ui' => 1,
        ),
        array(
            'key' => 'field_5cc01b89194a2',
            'label' => 'People',
            'name' => 'people',
            'type' => 'repeater',
            'instructions' => '',
            'required' => 1,
            'conditional_logic' => 0,
            'wrapper' => array(
                'width' => '',
                'class' => '',
                'id' => '',
            ),
            'collapsed' => '',
            'min' => 1,
            'max' => 0,
            'layout' => 'block',
            'button_label' => '',
            'sub_fields' => array(
                array(
                    'key' => 'field_5cc01bae194a3',
                    'label' => 'Age',
                    'name' => 'age',
                    'type' => 'text',
                    'instructions' => '',
                    'required' => 1,
                    'conditional_logic' => 0,
                    'wrapper' => array(
                        'width' => '80',
                        'class' => '',
                        'id' => '',
                    ),
                    'default_value' => '',
                    'placeholder' => '',
                    'prepend' => '',
                    'append' => '',
                    'maxlength' => '',
                ),
                array(
                    'key' => 'field_5cc01bc5194a4',
                    'label' => 'Price',
                    'name' => 'price',
                    'type' => 'text',
                    'instructions' => '',
                    'required' => 1,
                    'conditional_logic' => 0,
                    'wrapper' => array(
                        'width' => '20',
                        'class' => '',
                        'id' => '',
                    ),
                    'default_value' => '',
                    'placeholder' => '',
                    'prepend' => '',
                    'append' => '',
                    'maxlength' => '',
                ),
                array(
                    'key' => 'field_5cc01bf7194a5',
                    'label' => 'Options',
                    'name' => 'option_to_person',
                    'type' => 'repeater',
                    'instructions' => '',
                    'required' => 0,
                    'conditional_logic' => 0,
                    'wrapper' => array(
                        'width' => '',
                        'class' => '',
                        'id' => '',
                    ),
                    'collapsed' => '',
                    'min' => 0,
                    'max' => 0,
                    'layout' => 'table',
                    'button_label' => '',
                    'sub_fields' => array(
                        array(
                            'key' => 'field_5cc01e10eb226',
                            'label' => 'Date from',
                            'name' => 'date_from',
                            'type' => 'text',
                            'instructions' => '',
                            'required' => 1,
                            'conditional_logic' => 0,
                            'wrapper' => array(
                                'width' => '30',
                                'class' => '',
                                'id' => '',
                            ),
                            'default_value' => '',
                            'placeholder' => '',
                            'prepend' => '',
                            'append' => '',
                            'maxlength' => '',
                        ),
                        array(
                            'key' => 'field_5cc01e18eb227',
                            'label' => 'Date to',
                            'name' => 'date_to',
                            'type' => 'text',
                            'instructions' => '',
                            'required' => 1,
                            'conditional_logic' => 0,
                            'wrapper' => array(
                                'width' => '30',
                                'class' => '',
                                'id' => '',
                            ),
                            'default_value' => '',
                            'placeholder' => '',
                            'prepend' => '',
                            'append' => '',
                            'maxlength' => '',
                        ),
                        array(
                            'key' => 'field_5cc01c12194a6',
                            'label' => 'Option',
                            'name' => 'option_term',
                            'type' => 'taxonomy',
                            'instructions' => '',
                            'required' => 1,
                            'conditional_logic' => 0,
                            'wrapper' => array(
                                'width' => '30',
                                'class' => '',
                                'id' => '',
                            ),
                            'taxonomy' => 'yf_tif_option_cat',
                            'field_type' => 'select',
                            'allow_null' => 0,
                            'add_term' => 1,
                            'save_terms' => 0,
                            'load_terms' => 0,
                            'return_format' => 'object',
                            'multiple' => 0,
                        ),
                        array(
                            'key' => 'field_5cc01c88194a7',
                            'label' => 'Price',
                            'name' => 'price',
                            'type' => 'text',
                            'instructions' => '',
                            'required' => 1,
                            'conditional_logic' => 0,
                            'wrapper' => array(
                                'width' => '',
                                'class' => '',
                                'id' => '',
                            ),
                            'default_value' => '',
                            'placeholder' => '',
                            'prepend' => '',
                            'append' => '',
                            'maxlength' => '',
                        ),
                    ),
                ),
),
),
),
'location' => array(
    array(
        array(
            'param' => 'post_type',
            'operator' => '==',
            'value' => 'yf_tif_order',
        ),
    ),
),
'menu_order' => 0,
'position' => 'normal',
'style' => 'default',
'label_placement' => 'top',
'instruction_placement' => 'label',
'hide_on_screen' => array(
    0 => 'the_content',
),
'active' => 1,
'description' => '',
));

acf_add_local_field_group(array(
    'key' => 'group_5cbf17f2d7905',
    'title' => 'Travel insurance - Option Data',
    'fields' => array(
        array(
            'key' => 'field_5cd52abd18fd1',
            'label' => 'Hide date select',
            'name' => 'hide_date_select',
            'type' => 'true_false',
            'instructions' => '',
            'required' => 0,
            'conditional_logic' => 0,
            'wrapper' => array(
                'width' => '',
                'class' => '',
                'id' => '',
            ),
            'message' => '',
            'default_value' => 0,
            'ui' => 0,
            'ui_on_text' => '',
            'ui_off_text' => '',
        ),
        array(
            'key' => 'field_5cbf1805755cf',
            'label' => 'Tooltip',
            'name' => 'tooltip',
            'type' => 'wysiwyg',
            'instructions' => '',
            'required' => 0,
            'conditional_logic' => 0,
            'wrapper' => array(
                'width' => '',
                'class' => '',
                'id' => '',
            ),
            'default_value' => '',
            'tabs' => 'all',
            'toolbar' => 'full',
            'media_upload' => 1,
            'delay' => 0,
        ),
    ),
    'location' => array(
        array(
            array(
                'param' => 'taxonomy',
                'operator' => '==',
                'value' => 'yf_tif_option_cat',
            ),
        ),
    ),
    'menu_order' => 0,
    'position' => 'normal',
    'style' => 'default',
    'label_placement' => 'top',
    'instruction_placement' => 'label',
    'hide_on_screen' => '',
    'active' => 1,
    'description' => '',
));

acf_add_local_field_group(array(
    'key' => 'group_5cc02904085a2',
    'title' => 'Travel insurance - Settings page',
    'fields' => array(
        array(
            'key' => 'field_5cc02961ee782',
            'label' => 'Settings',
            'name' => 'yf_tif_settings',
            'type' => 'group',
            'instructions' => '',
            'required' => 0,
            'conditional_logic' => 0,
            'wrapper' => array(
                'width' => '',
                'class' => '',
                'id' => '',
            ),
            'layout' => 'block',
            'sub_fields' => array(
                array(
                    'key' => 'field_5cc02977ee783',
                    'label' => 'Form page',
                    'name' => 'form_page',
                    'type' => 'post_object',
                    'instructions' => '',
                    'required' => 1,
                    'conditional_logic' => 0,
                    'wrapper' => array(
                        'width' => '',
                        'class' => '',
                        'id' => '',
                    ),
                    'post_type' => array(
                        0 => 'page',
                    ),
                    'taxonomy' => '',
                    'allow_null' => 0,
                    'multiple' => 0,
                    'return_format' => 'object',
                    'ui' => 1,
                ),
                array(
                    'key' => 'field_5cc02996ee784',
                    'label' => 'Email',
                    'name' => 'email',
                    'type' => 'text',
                    'instructions' => '',
                    'required' => 1,
                    'conditional_logic' => 0,
                    'wrapper' => array(
                        'width' => '',
                        'class' => '',
                        'id' => '',
                    ),
                    'default_value' => '',
                    'placeholder' => '',
                    'prepend' => '',
                    'append' => '',
                    'maxlength' => '',
                ),
                array(
                    'key' => 'field_5cc029daee785',
                    'label' => 'Phone',
                    'name' => 'phone',
                    'type' => 'text',
                    'instructions' => '',
                    'required' => 1,
                    'conditional_logic' => 0,
                    'wrapper' => array(
                        'width' => '',
                        'class' => '',
                        'id' => '',
                    ),
                    'default_value' => '',
                    'placeholder' => '',
                    'prepend' => '',
                    'append' => '',
                    'maxlength' => '',
                ),
                array(
                    'key' => 'field_5cc033f551914',
                    'label' => 'Companies button text',
                    'name' => 'companies_button_text',
                    'type' => 'text',
                    'instructions' => '',
                    'required' => 1,
                    'conditional_logic' => 0,
                    'wrapper' => array(
                        'width' => '',
                        'class' => '',
                        'id' => '',
                    ),
                    'default_value' => '',
                    'placeholder' => '',
                    'prepend' => '',
                    'append' => '',
                    'maxlength' => '',
                ),
                array(
                    'key' => 'field_5cc0340251915',
                    'label' => 'Companies button image',
                    'name' => 'companies_button_image',
                    'type' => 'image',
                    'instructions' => '',
                    'required' => 1,
                    'conditional_logic' => 0,
                    'wrapper' => array(
                        'width' => '',
                        'class' => '',
                        'id' => '',
                    ),
                    'return_format' => 'array',
                    'preview_size' => 'thumbnail',
                    'library' => 'all',
                    'min_width' => '',
                    'min_height' => '',
                    'min_size' => '',
                    'max_width' => '',
                    'max_height' => '',
                    'max_size' => '',
                    'mime_types' => '',
                ),
                array(
                    'key' => 'field_5cc02d79dc0bc',
                    'label' => 'Buttons',
                    'name' => 'buttons',
                    'type' => 'repeater',
                    'instructions' => '',
                    'required' => 0,
                    'conditional_logic' => 0,
                    'wrapper' => array(
                        'width' => '',
                        'class' => '',
                        'id' => '',
                    ),
                    'collapsed' => '',
                    'min' => 0,
                    'max' => 0,
                    'layout' => 'table',
                    'button_label' => '',
                    'sub_fields' => array(
                        array(
                            'key' => 'field_5cc02d81dc0bd',
                            'label' => 'Text',
                            'name' => 'text',
                            'type' => 'text',
                            'instructions' => '',
                            'required' => 1,
                            'conditional_logic' => 0,
                            'wrapper' => array(
                                'width' => '',
                                'class' => '',
                                'id' => '',
                            ),
                            'default_value' => '',
                            'placeholder' => '',
                            'prepend' => '',
                            'append' => '',
                            'maxlength' => '',
                        ),
                        array(
                            'key' => 'field_5cc02d8adc0be',
                            'label' => 'Image',
                            'name' => 'image',
                            'type' => 'image',
                            'instructions' => '',
                            'required' => 1,
                            'conditional_logic' => 0,
                            'wrapper' => array(
                                'width' => '',
                                'class' => '',
                                'id' => '',
                            ),
                            'return_format' => 'array',
                            'preview_size' => 'thumbnail',
                            'library' => 'all',
                            'min_width' => '',
                            'min_height' => '',
                            'min_size' => '',
                            'max_width' => '',
                            'max_height' => '',
                            'max_size' => '',
                            'mime_types' => '',
                        ),
                    ),
                ),
            ),
),
),
'location' => array(
    array(
        array(
            'param' => 'options_page',
            'operator' => '==',
            'value' => 'acf-options-settings',
        ),
    ),
),
'menu_order' => 0,
'position' => 'normal',
'style' => 'default',
'label_placement' => 'top',
'instruction_placement' => 'label',
'hide_on_screen' => '',
'active' => 1,
'description' => '',
));

endif;


}

public function registerPostTypes(){


        /**
         * Post Type: Companies.
         */

        $labels = array(
            "name" => __( "Insurance companies", "twentynineteen" ),
            "singular_name" => __( "Insurance company", "twentynineteen" ),
        );

        $args = array(
            "label" => __( "Insurance companies", "twentynineteen" ),
            "labels" => $labels,
            "description" => "",
            "public" => false,
            "publicly_queryable" => false,
            "show_ui" => true,
            //"delete_with_user" => false,
            "show_in_rest" => true,
            "rest_base" => "",
            "rest_controller_class" => "WP_REST_Posts_Controller",
            "has_archive" => false,
            "show_in_menu" => true,
            "show_in_nav_menus" => true,
            "exclude_from_search" => false,
            "capability_type" => "post",
            "map_meta_cap" => true,
            "hierarchical" => false,
            "rewrite" => array( "slug" => "yf_tif_company", "with_front" => true ),
            "query_var" => true,
            "menu_icon" => "dashicons-editor-ul",
            "supports" => array( "title", "editor", "thumbnail" ),
        );

        register_post_type( "yf_tif_company", $args );

        /**
         * Post Type: Insurance orders.
         */

        $labels = array(
            "name" => __( "Insurance orders", "twentynineteen" ),
            "singular_name" => __( "Insurance order", "twentynineteen" ),
        );

        $args = array(
            "label" => __( "Insurance orders", "twentynineteen" ),
            "labels" => $labels,
            "description" => "",
            "public" => false,
            "publicly_queryable" => false,
            "show_ui" => true,
            "delete_with_user" => false,
            "show_in_rest" => true,
            "rest_base" => "",
            "rest_controller_class" => "WP_REST_Posts_Controller",
            "has_archive" => false,
            "show_in_menu" => true,
            "show_in_nav_menus" => true,
            "exclude_from_search" => false,
            "capability_type" => "post",
            "map_meta_cap" => true,
            "hierarchical" => false,
            "rewrite" => array( "slug" => "yf_tif_order", "with_front" => true ),
            "query_var" => true,
            "menu_icon" => "dashicons-feedback",
            "supports" => array( "title" ),
        );

        register_post_type( "yf_tif_order", $args );

    }

    public function registerTaxonomies(){

        /**
         * Taxonomy: Age.
         */

        $labels = array(
            "name" => __( "Age", "twentynineteen" ),
            "singular_name" => __( "Age", "twentynineteen" ),
        );

        $args = array(
            "label" => __( "Age", "twentynineteen" ),
            "labels" => $labels,
            "public" => false,
            "publicly_queryable" => false,
            "hierarchical" => false,
            "show_ui" => true,
            "show_in_menu" => true,
            "show_in_nav_menus" => true,
            "query_var" => true,
            "rewrite" => array( 'slug' => 'yf_tif_age_cat', 'with_front' => true,  'hierarchical' => true, ),
            "show_admin_column" => false,
            "show_in_rest" => true,
            "rest_base" => "yf_tif_age_cat",
            "rest_controller_class" => "WP_REST_Terms_Controller",
            "show_in_quick_edit" => false,
        );
        register_taxonomy( "yf_tif_age_cat", array( "yf_tif_company" ), $args );

        /**
         * Taxonomy: Options.
         */

        $labels = array(
            "name" => __( "Options", "twentynineteen" ),
            "singular_name" => __( "Option", "twentynineteen" ),
        );

        $args = array(
            "label" => __( "Options", "twentynineteen" ),
            "labels" => $labels,
            "public" => false,
            "publicly_queryable" => false,
            "hierarchical" => false,
            "show_ui" => true,
            "show_in_menu" => true,
            "show_in_nav_menus" => true,
            "query_var" => true,
            "rewrite" => array( 'slug' => 'yf_tif_option_cat', 'with_front' => true, ),
            "show_admin_column" => false,
            "show_in_rest" => true,
            "rest_base" => "yf_tif_option_cat",
            "rest_controller_class" => "WP_REST_Terms_Controller",
            "show_in_quick_edit" => false,
        );
        register_taxonomy( "yf_tif_option_cat", array( "yf_tif_company" ), $args );


    }

    public function getData(){

        if($this->data !== null)
            return $this->data;

        $data = [
            'age' => [],
            'options' => [],
            'exists_options' => [],
            'companies' => []
        ];

        // form age data
        $age = [];
        $age_query = get_terms([
            'taxonomy' => 'yf_tif_age_cat',
            'parent' => 0,
            'hide_empty' => false
        ]);
        foreach ($age_query AS $aq){
            $age[$aq->term_id] = [
                'term' => $aq,
                'period' => []
            ];
            $fields = get_fields($aq->taxonomy.'_'.$aq->term_id);
            $age[$aq->term_id]['period']['from'] = $fields['from'];
            $age[$aq->term_id]['period']['to'] = $fields['to'];
        }
        $data['age'] = $age;


        //form options data
        $options = [];
        $options_query = get_terms([
            'taxonomy' => 'yf_tif_option_cat',
            'parent' => 0,
            'hide_empty' => false
        ]);
        foreach ($options_query AS $oq){
            $options[$oq->term_id]  = [
                'term' => $oq,
                'hide_date_select' => get_field('hide_date_select', $oq->taxonomy.'_'.$oq->term_id),
                'tooltip' => get_field('tooltip', $oq->taxonomy.'_'.$oq->term_id)
            ];
        }
        $data['options'] = $options;


        // form companies data
        $companies = [];
        $companies_query = get_posts([
            'posts_per_page' => -1,
            'post_type' => 'yf_tif_company',
            'post_status' => 'publish'
        ]);
        foreach($companies_query AS $cq){
            $fields = get_fields($cq->ID);
            $companies[$cq->ID] = [
                'post' => $cq,
                'min_price' => (float)$fields['min_price'],
                'max_price' => (float)$fields['max_price'],
                'currency' => $fields['currency'],
                'age' => [],
                'options' => [],

                'hide_in_main_list' => isset($fields['hide_in_main_list']) && $fields['hide_in_main_list'] ? 1 : 0,

                'popup_image' => $fields['popup_image'],
                'right_text' => isset($fields['right_text']) && $fields['right_text'] ? explode(PHP_EOL, $fields['right_text']) : [],
                'middle_text' => isset($fields['middle_text']) && $fields['middle_text'] ? explode(PHP_EOL, $fields['middle_text']) : [],
                'left_text' => isset($fields['left_text']) && $fields['left_text'] ? explode(PHP_EOL, $fields['left_text']) : [],
            ];
            if(isset($fields['age']) && is_array($fields['age'])){
                foreach($fields['age'] AS $a){
                    if($a['age']) {
                        $companies[$cq->ID]['age'][$a['age']->term_id] = $a['price'];
                    }
                }
            }
            if(isset($fields['option_cats']) && is_array($fields['option_cats'])){
                foreach($fields['option_cats'] AS $o){
                    if($o['option_cat']) {
                        if(!in_array($o['option_cat']->term_id, $data['exists_options']))
                            $data['exists_options'][] = $o['option_cat']->term_id;
                        $companies[$cq->ID]['options'][$o['option_cat']->term_id] = $o['price'];
                    }
                }
            }
        }
        $data['companies'] = $companies;

        $this->data = $data;


        return $this->data;

    }


    public function initAdminPage(){
        $acf_main_data_page = acf_add_options_page(array(
            'page_title' 	=> 'Travel Insurance',
            'menu_title' 	=> 'Travel Insurance',
            'menu_slug' 	=> 'acf-opt-yf_tif_settings',
            'icon_url'      => 'dashicons-admin-generic',
            'redirect' 		=> true
        ));
        $acf_testimonials_page = acf_add_options_sub_page(array(
            'page_title' 	=> 'Settings',
            'menu_title' 	=> 'Settings',
            'parent_slug' 	=> $acf_main_data_page['menu_slug'],
        ));
    }


    public function initForm($template){

        if($this->page == null || !is_page($this->page->ID))
            return $template;


        add_action('wp_print_styles', function(){
            global $wp_styles;
            $wp_styles->queue = array();
        }, 100);

        wp_deregister_script('jquery');
        wp_enqueue_script('jquery', plugin_dir_url(__FILE__) . 'assets/js/jquery.min.js', array(), false, true);





        return __DIR__.'/template.php';
    }

}