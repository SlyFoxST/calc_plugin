<?php
// 
class Limit{

   function __construct(){

        $type = get_queried_object();

        $price = "";

        $limit = "";

        if($type->post_type == "yf_tif_company"): 

            $limit = get_post_meta($type->ID,'maximum_trip_limit',true);
            if(!empty($limit) && $limit < 6):

                $limit *= 1;

            elseif(!empty($limit) && $limit >= 6):

                //$limit *= 5; 
                $limit = 5;  

            endif;

        endif;

        return $limit;
    }
}
add_action( 'wp_footer', function() { new Limit; });

class LimimtDays{

    public $days = "";

    public $price = "";

    function limit_days($days = 1){

        $type = get_queried_object();

        $msg = [];

        if($type->post_type == "yf_tif_company"){

            $field = get_field_object('maximum_limit_for_the_entire_trip');

            $value = $field['value'];

            $label = $field['choices'][ $value ];

            $msg[] = $label;
        //echo $label;
            if($value == 'max'){
                $result = $days * 1;        
            }
            else $result = $days * 0.5;

            foreach ($msg as $key) {
                echo $key;
            }

        }
        //echo $result;
        return $result;
    }
}
add_action( 'wp_footer', function() { new LimimtDays; });

if(!function_exists('print_array')) {
    function print_array($arr = [])
    {
        echo '<pre>';
        print_r($arr);
        echo '</pre>';
    }
}

if(!function_exists('print_array_die')) {
    function print_array_die($arr = [])
    {
        print_array($arr);
        die();
    }
}


function YF_TIF()
{
    $TYC = TravelInsuranceClass::init();
    return $TYC;
}


add_action( 'wp_ajax_yf_tif_get_results', 'ajax_yf_tif_get_results' );
add_action( 'wp_ajax_nopriv_yf_tif_get_results', 'ajax_yf_tif_get_results' );

function ajax_yf_tif_get_results() {

    $json = array(
        'post' => $_POST
    );



    $form = $_POST['form'];
    $data = YF_TIF()->getData();

    $age_ids = [];
    foreach ($form['passenger_age'] AS $passenger_age){
        $age_id = 0;
        /*foreach($data['age'] AS $age){
            if($age['period']['from'] <= $passenger_age && $age['period']['to'] >= $passenger_age){
                $age_ids[] = $age['term']->term_id;
                break;
            }
        }*/
        $age_ids[] = $passenger_age;
    }





    $results = [];
    foreach($data['companies'] AS $company){
        if(!$company['hide_in_main_list']) {
            $price = 0;
            $results[$company['post']->ID] = [
                'id' => $company['post']->ID,
                'price' => $price,
                'currency' => $company['currency'],
            ];

            foreach ($age_ids AS $age_id) {
                if (isset($company['age'][$age_id])) {
                    $price += (float)$company['age'][$age_id];
                }
            }

            $price *= $form['days'];

            if (isset($form['option_to_person']) && is_array($form['option_to_person'])) {
                foreach ($form['option_to_person'] AS $option_to_person) {
                    if (isset($company['options'][$option_to_person['option_id']])) {
                        $price += (float)$company['options'][$option_to_person['option_id']] * count($option_to_person['age']) * $option_to_person['days'];
                    }
                }
            }


            if ($price < $company['min_price']) {
                $price = $company['min_price'];
            }
            if ($price > $company['max_price']) {
                $price = $company['max_price'];
            }
            $results[$company['post']->ID]['price'] = $price;
        }
    }

    $json['results'] = $results;


    die (json_encode($json));

}



add_action( 'wp_ajax_yf_tif_place_order', 'ajax_yf_tif_place_order' );
add_action( 'wp_ajax_nopriv_yf_tif_place_order', 'ajax_yf_tif_place_order' );

function ajax_yf_tif_place_order()
{

    $json = array(
        'post' => $_POST,
        'check2' => [],
        'check' => []
    );


    $post_id = wp_insert_post([
        'post_type' => 'yf_tif_order',
        'post_title'    => wp_strip_all_tags( 'New order' ),
        'post_status'   => 'publish',
        'post_author'   => 1,
    ]);

    wp_update_post([
        'ID' => $post_id,
        'post_title' => 'Order #'.$post_id,
        'post_name' => sanitize_title('YF TIF Order #'.$post_id)
    ]);



    $form = $_POST['form'];

    update_field('status', 'new', $post_id);
    update_field('date_from', $form['date_from'], $post_id);
    update_field('date_to', $form['date_to'], $post_id);
    update_field('company', $_POST['company_id'], $post_id);

    $data = YF_TIF()->getData();

    $age_ids = [];
    $age_to_id = [];
    foreach ($form['passenger_age'] AS $passenger_age){
        $age_id = 0;
        /*foreach($data['age'] AS $age){
            if($age['period']['from'] <= $passenger_age && $age['period']['to'] >= $passenger_age){
                $age_ids[] = $age['term']->term_id;
                $age_to_id[$passenger_age] = $age['term']->term_id;
                break;
            }
        }*/
        $age_ids[] = $passenger_age;
        $age_to_id[$passenger_age] = $passenger_age;
    }

    $company = $data['companies'][$_POST['company_id']];
    $total = 0;

    //foreach($age_to_id AS $age_num=>$age_id){
    foreach($form['passenger_age'] AS $age_num){
        $age_id = $age_to_id[$age_num];
        if(isset($company['age'][$age_id])){
            $total += (float)$company['age'][$age_id];

            $options = [];
            if(isset($form['option_to_person']) && is_array($form['option_to_person'])) {
                foreach ($form['option_to_person'] AS $o2p_id=>$option_to_person) {
                    $isset = array_search($age_num, $option_to_person['age']);
                    if($isset !== false && isset($company['options'][$option_to_person['option_id']])){

                        $total += (float)$company['options'][$option_to_person['option_id']] * $option_to_person['days'];
                        $options[] = [
                            'date_from' => $option_to_person['date_from'],
                            'date_to' => $option_to_person['date_to'],
                            'option_term' => $option_to_person['option_id'],
                            'price' => $company['options'][$option_to_person['option_id']].$company['currency']
                        ];
                        unset($form['option_to_person'][$o2p_id]['age'][$isset]);
                        $json['check2'][] = $isset;
                    }
                }
            }

            $json['check'][] = $options;

            add_row('people', [
                //'age' => $age_num,
                'age' => $data['age'][$age_id]['term']->name,
                'price' => (float)$company['age'][$age_id].$company['currency'],
                'option_to_person' => $options
            ], $post_id);

        }
    }

    update_field('total', ($total.$company['currency']), $post_id);


    $message = 'You have new order on your site';
    $message .= "\r\n";
    $message .= 'Order #'.$post_id.' - '.get_site_url().'/wp-admin/post.php?post='.$post_id.'&action=edit">';

    wp_mail(YF_TIF()->email, 'New order', $message);



    die (json_encode($json));

}




add_action( 'wp_ajax_yf_tif_feedback', 'ajax_yf_tif_feedback' );
add_action( 'wp_ajax_nopriv_yf_tif_feedback', 'ajax_yf_tif_feedback' );

function ajax_yf_tif_feedback()
{

    $json = array(
        'post' => $_POST
    );

    $message = 'Name: '.$_POST['data']['user_name']."\r\n".'Phone: '.$_POST['data']['user_phone']."\r\n";
    if(isset($_POST['data']['company']) && is_array($_POST['data']['company']) && count($_POST['data']['company']) > 0){
        $companies = [];
        foreach($_POST['data']['company'] AS $c){
            $companies[] = get_post($c)->post_title;
        }
        $message .= 'Companies: '.(implode(', ', $companies))."\r\n";
    }

    wp_mail(YF_TIF()->email, 'Feedback', $message);



    die (json_encode($json));

}


/**
 * Add custom admin columns to posts page
 */
add_filter( 'manage_yf_tif_order_posts_columns', 'set_custom_edit_yf_tif_order_columns' );
function set_custom_edit_yf_tif_order_columns($columns) {

    $new_columns = [];
    foreach ($columns AS $k=>$v){
        $new_columns[$k] = $v;
        if($k == 'title'){
            $new_columns['status'] = 'Status';
            $new_columns['company'] = 'Company';
            $new_columns['total'] = 'Total';
        }
    }


    return $new_columns;
}
add_action( 'manage_yf_tif_order_posts_custom_column' , 'custom_yf_tif_order_column', 10, 2 );
function custom_yf_tif_order_column( $column, $post_id ) {
    $f = get_fields($post_id);
    switch ( $column ) {
        case 'status' :
            echo $f['status']['label'];
            break;
        case 'company' :
            echo $f['company']->post_title;
            break;
        case 'total' :
            echo $f['total'];
            break;
    }
}
