<?php
/**
 * Plugin Name: Yahts API
 * Plugin URI:
 * Author: Dusko
 * Author URI:
 * Description: Plugin for querying external API
 * Version: 0.1.0
 * License: GPL2
 * License URL: http://www.gnu.org/licenses/gpl-2.0.txt
 * text-domain: yachting-wp-api
 */

defined( 'ABSPATH' ) or die;

add_action( 'admin_menu', 'yachts_add_menu_page' );
add_action( 'init', 'yachting_register_cpt' );
add_action( 'create_or_update_data', 'update_plugin_data' );


function my_plugin_activate() {

	run_all_the_code_functions();
	/* activation code here */
}


function update_plugin_data() {
	run_all_the_code_functions();
}

register_activation_hook( __FILE__, 'my_plugin_activate' );
register_deactivation_hook( __FILE__, 'my_deactivation' );

function my_deactivation() {
	wp_clear_scheduled_hook( 'my_hourly_event' );
}


function yachts_add_menu_page() {
	add_menu_page(
		'Yachting API Settings',
		'Yachting API Settings',
		'manage_options',
		'yachting-wp-api.php',
		'run_all_the_code_functions',
		'dashicons-book',
		16
	);
}

function get_year_dates($year) {

	$range = array();
	$start = strtotime($year.'-01-01');
	$end = strtotime($year.'-12-31');

	do {
		$range[] = date('Y-m-d',$start);
		$start = strtotime( '+ 1 day', $start);
	} while ( $start <= $end );

	return $range;
}

function yacht_search_form_att( $atts ) {

	//$a = shortcode_atts($default, $atts);

	$form = '';
	ob_start();
	?>
    <section class="booking-section">
        <div class="image-layer"
             style="background-image: url(<?php// echo esc_url($settings['bg_img']['url']);?>);"></div>
        <div class="auto-container">
            <div class="row clearfix">
                <!--Title Column-->
                <div class="title-col col-xl-4 col-lg-12 col-md-12 col-sm-12">
                    <div class="inner">
                        <div class="title-box">
                            <h2><?php //echo wp_kses( $settings['subtitle'], $allowed_tags );?></h2>
                            <div class="subtitle"><?php// echo wp_kses( $settings['title'], $allowed_tags );?></div>
                        </div>
                    </div>
                </div>

                <!--Form Column-->
                <div class="form-col col-xl-8 col-lg-12 col-md-12 col-sm-12">
                    <div class="inner">
                        <div class="default-form booking-form">
                            <form method="GET" action="<?php echo esc_url( home_url( '/' ) ); ?>" accept-charset="UTF-8"
                                  id="search-form" class="">
                                <div class="row clearfix">
                                    <div class="form-group col-lg-4 col-md-4 col-sm-12">
                                        <div class="field-inner">
                                            <div class="field-icon"><span class="flaticon-maps-and-flags"></span></div>
                                            <select class="custom-select-box" id="marina" style="display: none;">
                                                <option value="marina-split-aci">Marina Split - ACI</option>
                                            </select><span tabindex="0" id="marina-button" role="combobox"
                                                           aria-expanded="false" aria-autocomplete="list"
                                                           aria-owns="marina-menu" aria-haspopup="true"
                                                           class="ui-selectmenu-button ui-selectmenu-button-closed ui-corner-all ui-button ui-widget"
                                                           aria-activedescendant="ui-id-3" aria-labelledby="ui-id-3"
                                                           aria-disabled="false"><span
                                                        class="ui-selectmenu-icon ui-icon ui-icon-triangle-1-s"></span><span
                                                        class="ui-selectmenu-text">Marina Split - ACI </span></span>
                                        </div>
                                    </div>
                                    <div class="form-group col-lg-4 col-md-4 col-sm-12">
                                        <div class="field-inner">
                                            <div class="field-icon"><span class="flaticon-boat-2"></span></div>
                                            <select class="custom-select-box" id="boat_type" style="display: none;">
                                                <option value="all">Boat Type: all</option>
                                                <option value="sailing-yacht">Sailing yacht</option>
                                                <option value="catamaran">Catamaran</option>
                                            </select><span tabindex="0" id="boat_type-button" role="combobox"
                                                           aria-expanded="false" aria-autocomplete="list"
                                                           aria-owns="boat_type-menu" aria-haspopup="true"
                                                           class="ui-selectmenu-button ui-selectmenu-button-closed ui-corner-all ui-button ui-widget"><span
                                                        class="ui-selectmenu-icon ui-icon ui-icon-triangle-1-s"></span><span
                                                        class="ui-selectmenu-text">Boat Type: all</span></span>
                                        </div>
                                    </div>
                                    <div class="form-group col-lg-4 col-md-4 col-sm-12">
                                        <div class="field-inner">
                                            <div class="field-icon"><span class="flaticon-user-3"></span></div>
                                            <select class="custom-select-box" id="cabins" style="display: none;">
                                                <option value="all">Cabins: all</option>
                                                <option value="2">2</option>
                                                <option value="3">3</option>
                                                <option value="4">4</option>
                                                <option value="5">5</option>
                                            </select><span tabindex="0" id="cabins-button" role="combobox"
                                                           aria-expanded="false" aria-autocomplete="list"
                                                           aria-owns="cabins-menu" aria-haspopup="true"
                                                           class="ui-selectmenu-button ui-selectmenu-button-closed ui-corner-all ui-button ui-widget"><span
                                                        class="ui-selectmenu-icon ui-icon ui-icon-triangle-1-s"></span><span
                                                        class="ui-selectmenu-text">Cabins: all</span></span>
                                        </div>
                                    </div>
                                    <div class="form-group col-lg-4 col-md-4 col-sm-12">
                                        <div class="field-inner">
                                            <div class="field-icon"><span class="flaticon-calendar"></span></div>
                                            <input class="form-control"
                                                   placeholder="Change"
                                                   readonly="readonly" name="date_from"
                                                   type="text" value=""
                                                   id="datepicker">
                                        </div>
                                    </div>
                                    <div class="form-group col-lg-4 col-md-4 col-sm-12">
                                        <div class="field-inner">
                                            <div class="field-icon"><span class="flaticon-stopwatch"></span></div>
                                            <select class="custom-select-box" id="charter_duration"
                                                    style="display: none;">
                                                <option value="1" selected="">7 days</option>

                                                <option value="2">14 days</option>

                                                <option value="3">21 days</option>
                                            </select><span tabindex="0" id="charter_duration-button" role="combobox"
                                                           aria-expanded="false" aria-autocomplete="list"
                                                           aria-owns="charter_duration-menu" aria-haspopup="true"
                                                           class="ui-selectmenu-button ui-selectmenu-button-closed ui-corner-all ui-button ui-widget"><span
                                                        class="ui-selectmenu-icon ui-icon ui-icon-triangle-1-s"></span><span
                                                        class="ui-selectmenu-text">7 days</span></span>
                                        </div>
                                    </div>
                                    <div class="form-group col-lg-4 col-md-4 col-sm-12">
                                        <input type="text" class="search-field" name="s" placeholder="Search"
                                               value="<?php echo get_search_query(); ?>">
                                        <input type="submit" class="theme-btn btn-style-three" value="Search">
                                        <input type="hidden" name="post_type" value="yachts"/>
                                        <!-- <button type="submit" class="theme-btn btn-style-three"><span class="btn-title">Search</span></button>-->
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

	<?php
	$form = ob_get_contents();

	return $form;
}

add_shortcode( 'yacht_search_form', 'yacht_search_form_att' );


function yachting_wp_api_scripts() {

	wp_enqueue_style( 'ion-rangeslider-css', "https://cdnjs.cloudflare.com/ajax/libs/ion-rangeslider/2.3.1/css/ion.rangeSlider.min.css", array( 'jquery' ), null, true );
	wp_enqueue_style( 'slick-css', plugins_url( '/assets/bower_components/slick-carousel/slick/slick.css', __FILE__ ), null, true );
	wp_enqueue_style( 'slick-theme-css', plugins_url( '/assets/bower_components/slick-carousel/slick/slick-theme.css', __FILE__ ), null, true );
	wp_enqueue_style( 'slick-lightbox-css', plugins_url( '/assets/bower_components/slick-lightbox/dist/slick-lightbox.css', __FILE__ ), null, true );
	wp_enqueue_style( 'custom-pugin-css', plugins_url( '/assets/css/custom.css', __FILE__ ), null, true );


	wp_enqueue_script( 'ajax_filter', plugins_url( '/assets/js/ajax_filter.js', __FILE__ ), array( 'jquery' ), null, true );
	wp_enqueue_script( 'ion-rangeslider-js', "https://cdnjs.cloudflare.com/ajax/libs/ion-rangeslider/2.3.1/js/ion.rangeSlider.min.js", array( 'jquery' ), null, true );
	wp_enqueue_script( 'slick-js', plugins_url( '/assets/bower_components/slick-carousel/slick/slick.js', __FILE__ ), array( 'jquery' ), null, true );
	wp_enqueue_script( 'slick-lightbox', plugins_url( '/assets/bower_components/slick-lightbox/dist/slick-lightbox.js', __FILE__ ), array( 'jquery' ), null, true );

	wp_localize_script( 'ajax_filter', 'bobz', array(
		//'nonce'    => wp_create_nonce( 'bobz' ),
		'ajax_url' => admin_url( 'admin-ajax.php' )
	) );
}

add_action( 'wp_enqueue_scripts', 'yachting_wp_api_scripts' );


function get_available_yachts( $yacht_ids, $date_from, $date_to ) {
	$free_yachts = yachting_get_api( "http://ws.nausys.com/CBMS-external/rest/yachtReservation/v6/freeYachts", $yacht_ids, $date_from, $date_to );
	if ( false === get_option( 'yachting_wp_api_available_yachts' ) ) {
		add_option( 'yachting_wp_api_available_yachts', $free_yachts );
	} else {
		update_option( 'yachting_wp_api_available_yachts', $free_yachts );
	}
	$free_yachts   = json_decode( get_option( 'yachting_wp_api_available_yachts' ) );
	$available_ids = [];
	$i             = 0;
	foreach ( $free_yachts->freeYachts as $key => $yacht ) {
		$key                                  = 'price_data';
		$available_ids['ids'][]               = $yacht->yachtId;
		$available_ids[ $key ][ $i ]['id']    = $yacht->yachtId;
		$available_ids[ $key ][ $i ]['price'] = $yacht->price->clientPrice;
		if ( isset( $yacht->price->discounts[0]->amount ) ) {
			$available_ids[ $key ][ $i ]['discount'] = $yacht->price->discounts[0]->amount;
		}
		$available_ids[ $key ][ $i ]['full_price'] = $yacht->price->priceListPrice;
		$i ++;
	}

	return $available_ids;

}

function get_available_yacht() {
	if ( isset( $_POST['yacht_id'] ) && isset( $_POST['date_from'] ) && isset( $_POST['charter_duration'] ) ) {
		$yacht_ids[]      = $_POST['yacht_id'];
		$charter_duration = isset( $_POST['charter_duration'] ) ? $_POST['charter_duration'] : 7;
		$date_from        = date( 'd.m.Y', strtotime( $_POST['date_from'] ) );
		$date_to          = date( 'd.m.Y', strtotime( $_POST['date_from'] . " +" . $charter_duration . " days" ) );

		$free_yachts = yachting_get_api( "http://ws.nausys.com/CBMS-external/rest/yachtReservation/v6/freeYachts", $yacht_ids, $date_from, $date_to );
		if ( false === get_option( 'yachting_wp_api_available_yacht' ) ) {
			add_option( 'yachting_wp_api_available_yacht', $free_yachts );
		} else {
			update_option( 'yachting_wp_api_available_yacht', $free_yachts );
		}
		$free_yachts   = json_decode( get_option( 'yachting_wp_api_available_yacht' ) );
		$ds            = [];
		$available_ids = [];
		$i             = 0;
		foreach ( $free_yachts->freeYachts as $key => $yacht ) {
			$key                                  = 'price_data';
			$available_ids['ids'][]               = $yacht->yachtId;
			$available_ids[ $key ][ $i ]['id']    = $yacht->yachtId;
			$available_ids[ $key ][ $i ]['price'] = $yacht->price->clientPrice;
			if ( isset( $yacht->price->discounts[0]->amount ) ) {
				$available_ids[ $key ][ $i ]['discount'] = $yacht->price->discounts[0]->amount;
			}
			$available_ids[ $key ][ $i ]['full_price'] = $yacht->price->priceListPrice;
			$i ++;
		}
		$available_ids['date_to'] = $date_to;
		echo $ds = json_encode( $available_ids );
	} else {
		echo 'bad request';
	}
	wp_die();

}

function get_ds() {

	echo 'tu';
	die();
}

add_action( 'wp_ajax_get_ds', 'get_available_yacht' );
add_action( 'wp_ajax_nopriv_get_ds', 'get_available_yacht' );

function get_yacht_price_info() {

}


function vb_filter_yachts() {
	if ( ! isset( $_GET['nonce'] ) || ! wp_verify_nonce( $_GET['nonce'], 'bobz' ) ) {
		die( 'Permission denied' );
	}
	global $wpdb;
	$yacht_ids = [];
	$query     = $wpdb->get_results( "SELECT ID FROM {$wpdb->base_prefix}posts WHERE post_type='yachts'", ARRAY_A );
	foreach ( $query as $yacht_id ) {
		$yacht_ids[] = $yacht_id['ID'];
	}

	$date_from = 0;
	$date_to   = 0;

	if ( isset( $_GET['date_from'] ) && $_GET['date_from'] != 'any' ) {
		$date_from = date( 'd.m.Y', strtotime( $_GET['date_from'] ) );
		$date_to   = date( 'd.m.Y', strtotime( $_GET['date_from'] . " +" . $_GET['charter_duration'] . " days" ) );
	}

	if ( isset( $_GET['cabins'] ) && $_GET['cabins'] != 'any' ) {

	}

	$available_yachts = get_available_yachts( $yacht_ids, $date_from, $date_to );

	//print_r($available_yachts);


	$args = array(
		//'orderby'   => 'date', // we will sort posts by date
		//'order'     => $_GET['date'],
		'post_type'      => 'yachts',
		'post__in'       => $available_yachts['ids'],
		'orderby'        => 'post__in',
		'posts_per_page' => - 1
	);

	if ( isset( $_GET['boat_type'] ) && $_GET['boat_type'] != 'any' ) {
		$args['meta_query'] = array(
			'relation' => 'AND',
			array(

				'key'     => 'yacht_type_eng',
				'value'   => $_GET['boat_type'],
				'compare' => '='

			),


		);
	}
	if ( isset( $_GET['cabins'] ) && $_GET['cabins'] != 'any' ) {
		$args['meta_query'] = array(
			'relation' => 'AND',
			array(

				'key'     => 'yacht_cabins',
				'value'   => $_GET['cabins'],
				'compare' => '='

			)
		);
	}


	$query = new WP_Query( $args );
	$i     = 0;
	if ( $query->have_posts() ) :
		while ( $query->have_posts() ): $query->the_post();
			$i ++;
			$yacht_prices = $available_yachts['price_data'];
			lanong_template_load( 'templates/yachts/yachts.php', compact( 'yacht_prices' ) );

		endwhile;
		wp_reset_postdata();
	else :
		echo '<div class="no_yachts"> No yacht(s) matched your search criteria </div>';
	endif;
	die();
}


add_action( 'wp_ajax_do_filter_yachts', 'vb_filter_yachts' );
add_action( 'wp_ajax_nopriv_do_filter_yachts', 'vb_filter_yachts' );


function run_all_the_code_functions() {
	$year   = date( 'Y' );
	$yachts = yachting_get_api( "http://ws.nausys.com/CBMS-external/rest/catalogue/v6/yachts/13022476" );
	//$single_yacht    = yachting_get_api( "http://ws.nausys.com/CBMS-external/rest/catalogue/v6/yacht/103152" );
	$models        = yachting_get_api( "http://ws.nausys.com/CBMS-external/rest/catalogue/v6/yachtModels" );
	$equipment     = yachting_get_api( "http://ws.nausys.com/CBMS-external/rest/catalogue/v6/equipment" );
	$sail_types    = yachting_get_api( "http://ws.nausys.com/CBMS-external/rest/catalogue/v6/sailTypes" );
	$categories    = yachting_get_api( "http://ws.nausys.com/CBMS-external/rest/catalogue/v6/yachtCategories" );
	$locations     = yachting_get_api( "http://ws.nausys.com/CBMS-external/rest/catalogue/v6/locations" );
	$region        = yachting_get_api( "http://ws.nausys.com/CBMS-external/rest/catalogue/v6/regions" );
	$countries     = yachting_get_api( "http://ws.nausys.com/CBMS-external/rest/catalogue/v6/countries" );
	$services      = yachting_get_api( "http://ws.nausys.com/CBMS-external/rest/catalogue/v6/services" );
	$eq_categories = yachting_get_api( "http://ws.nausys.com/CBMS-external/rest/catalogue/v6/equipmentCategories" );
	$builders      = yachting_get_api( "http://ws.nausys.com/CBMS-external/rest/catalogue/v6/yachtBuilders" );
	$sail_type     = yachting_get_api( "http://ws.nausys.com/CBMS-external/rest/catalogue/v6/sailTypes" );
	$steering_type = yachting_get_api( "http://ws.nausys.com/CBMS-external/rest/catalogue/v6/steeringTypes" );
	$measures      = yachting_get_api( "http://ws.nausys.com/CBMS-external/rest/catalogue/v6/priceMeasures" );
	$reservations  = yachting_get_api( "http://ws.nausys.com/CBMS-external/rest/yachtReservation/v6/occupancy/13022476/" . $year );


	//fetch yachts data
	if ( false === get_option( 'yachting_wp_api_yachts' ) ) {
		add_option( 'yachting_wp_api_yachts', $yachts );
	} else {
		update_option( 'yachting_wp_api_yachts', $yachts );
	}

	//fetch yaht models
	if ( false === get_option( 'yachting_wp_api_models' ) ) {
		add_option( 'yachting_wp_api_models', $models );
	} else {
		update_option( 'yachting_wp_api_models', $models );
	}

	//fetch yaht equipment
	if ( false === get_option( 'yachting_wp_api_equipment' ) ) {
		add_option( 'yachting_wp_api_equipment', $equipment );
	} else {
		update_option( 'yachting_wp_api_equipment', $equipment );
	}

	//fetch sail types
	if ( false === get_option( 'yachting_wp_api_sail_types' ) ) {
		add_option( 'yachting_wp_api_sail_types', $sail_types );
	} else {
		update_option( 'yachting_wp_api_sail_types', $equipment );
	}

	if ( false === get_option( 'yachting_wp_api_categories' ) ) {
		add_option( 'yachting_wp_api_categories', $categories );
	} else {
		update_option( 'yachting_wp_api_categories', $categories );
	}

	if ( false === get_option( 'yachting_wp_api_locations' ) ) {
		add_option( 'yachting_wp_api_locations', $locations );
	} else {
		update_option( 'yachting_wp_api_locations', $locations );
	}

	if ( false === get_option( 'yachting_wp_api_regions' ) ) {
		add_option( 'yachting_wp_api_regions', $region );
	} else {
		update_option( 'yachting_wp_api_regions', $region );
	}

	if ( false === get_option( 'yachting_wp_api_countries' ) ) {
		add_option( 'yachting_wp_api_countries', $countries );
	} else {
		update_option( 'yachting_wp_api_countries', $countries );
	}

	if ( false === get_option( 'yachting_wp_api_services' ) ) {
		add_option( 'yachting_wp_api_services', $services );
	} else {
		update_option( 'yachting_wp_api_services', $services );
	}

	if ( false === get_option( 'yachting_wp_api_eq_categories' ) ) {
		add_option( 'yachting_wp_api_eq_categories', $eq_categories );
	} else {
		update_option( 'yachting_wp_api_eq_categories', $eq_categories );
	}

	if ( false === get_option( 'yachting_wp_api_builders' ) ) {
		add_option( 'yachting_wp_api_builders', $builders );
	} else {
		update_option( 'yachting_wp_api_builders', $builders );
	}

	if ( false === get_option( 'yachting_wp_api_sail_types' ) ) {
		add_option( 'yachting_wp_api_sail_types', $sail_type );
	} else {
		update_option( 'yachting_wp_api_sail_types', $sail_type );
	}

	if ( false === get_option( 'yachting_wp_api_steering_types' ) ) {
		add_option( 'yachting_wp_api_steering_types', $steering_type );
	} else {
		update_option( 'yachting_wp_api_steering_types', $steering_type );
	}

	if ( false === get_option( 'yachting_wp_api_measures' ) ) {
		add_option( 'yachting_wp_api_measures', $measures );
	} else {
		update_option( 'yachting_wp_api_measures', $measures );
	}

	if ( false === get_option( 'yachting_wp_api_reservations' ) ) {
		add_option( 'yachting_wp_api_reservations', $reservations );
	} else {
		update_option( 'yachting_wp_api_reservations', $reservations );
	}

	save_database_table_info();

}

function yachting_register_cpt() {

	$args = array(
		'label'              => 'Yachts',
		'labels'             => array(
			'name'          => 'Yachts',
			'singular_name' => 'Yachts',
			'add_new_item'  => 'Add New Yacht',
			'edit_item'     => 'Edit Yacht',
			'new_item'      => 'New Yacht',
			'view_item'     => 'View Yacht',
			'search_items'  => 'Search Yachts',
			'not_found'     => 'No Yachts',
		),
		'description'        => 'Yachts',
		'supports'           => array( 'title', 'editor', 'excerpt', 'thumbnail' ),
		'show_ui'            => true,
		'show_in_menu'       => true,
		'menu_position'      => 32,
		'show_in_nav_menus'  => true,
		'publicly_queryable' => true,
		'query_var'          => true,
		'can_export'         => true,
		'public'             => true,
		'has_archive'        => true,
		'capability_type'    => 'post',
		'show_in_rest'       => true,
		'rewrite'            => array( "slug" => "yachts", "with_front" => false ),
	);

	register_post_type( 'yachts', $args );
}

function yachting_get_api( $api_url, $yacht_ids = 0, $date_from = 0, $date_to = 0, $param_arr = 0, $make_option = 0 ) {

	if ( $yacht_ids == 0 ) {
		$body = array(
			'username' => 'rest@WYC',
			'password' => 'restworld531',
		);
	} elseif ( ! empty( $param_arr ) ) {
		$body =
			array(
				"client"            => array(
					"name"      => $param_arr['first_name'],
					"surname"   => $param_arr['surname'],
					"company"   => "false",
					"vatNr"     => "false",
					"address"   => "false",
					"zip"       => "false",
					"city"      => "false",
					"countryId" => $param_arr['country_id'],
					"email"     => $param_arr['email'],
					"phone"     => $param_arr['phone'],
					"mobile"    => "",
					"skype"     => ""
				),
				'credentials'       => array(
					'username' => 'rest@WYC',
					'password' => 'restworld531',
				),
				"periodFrom"        => $param_arr['from'],
				"periodTo"          => $param_arr['to'],
				"yachtID"           => $param_arr['yacht_id'],
				"onlinePayment"     => "false",
				"numberOfPayments"  => "2",
				"useDepositPayment" => "false"
			);

	} elseif ( ! empty( $make_option ) ) {
		$body = array(
			'credentials'         => array(
				'username' => 'rest@WYC',
				'password' => 'restworld531',
			),
			"id"                  => $make_option['id'],
			"uuid"                => $make_option['uuid'],
			"createWaitingOption" => "true"
		);
	} else {
		$body =
			array(
				'credentials' => array(
					'username' => 'rest@WYC',
					'password' => 'restworld531',
				),
				"periodFrom"  => $date_from,
				"periodTo"    => $date_to,
				"yachts"      => $yacht_ids
			);

	}


	$headers = array(
		'Content-Type' => 'application/json',
	);

	$arg = array(
		'headers'   => $headers, // good
		'body'      => json_encode( $body ),
		'method'    => 'POST',
		'sslverify' => false,
	);

	$response      = wp_remote_post( $api_url, $arg );
	$response_code = wp_remote_retrieve_response_code( $response );
	$body          = wp_remote_retrieve_body( $response );

	if ( 401 === $response_code ) {
		return "Unauthorized access";
	}

	if ( 200 !== $response_code ) {
		return "Error in pinging API";
	}

	if ( 200 === $response_code ) {
		return $body;
	}

}


function get_yacht_model( $id ) {

	$models = json_decode( get_option( 'yachting_wp_api_models' ) );
	$item   = null;
	foreach ( $models->models as $model_key => $struct ) {
		if ( $id == $struct->id ) {
			$item = $struct;
			break;
		}
	}

	return $item;
}

function get_yacht_equipment_ids( $equipment_obj ) {

	$term_arg = array_map( function ( $item ) {
		return $item->equipmentId;
	}, $equipment_obj );


	return $term_arg;
}

function get_yacht_eq_ids( $equipment_obj ) {
	$term_arg = array_map( function ( $item ) {
		return $item->equipmentId;
	}, $equipment_obj );


	return $term_arg;
}

function get_yacht_service_ids( $equipment_obj ) {
	$term_arg = array_map( function ( $item ) {
		return $item->serviceId;
	}, $equipment_obj );

	return $term_arg;
}


function get_yacht_equipment_category_name( $id ) {
	$category = json_decode( get_option( 'yachting_wp_api_eq_categories' ) );
	$eq       = [];
	$oc       = 0;
	foreach ( $category->equipmentCategories as $key => $struct ) {

		if ( $id == $struct->id ) {
			$eq['textEN'] = $struct->name->textEN;
			$eq['textHR'] = $struct->name->textHR;
		}
		$oc ++;


	}

	return $eq;
}

function get_yacht_countries_name_en_hr() {
	$countries = json_decode( get_option( 'yachting_wp_api_countries' ) );
	$eq        = [];
	$oc        = 0;
	foreach ( $countries->countries as $key => $struct ) {

		$eq[ $oc ]['id']     = $struct->id;
		$eq[ $oc ]['code']   = $struct->id;
		$eq[ $oc ]['textEN'] = $struct->name->textEN;
		$eq[ $oc ]['textHR'] = $struct->name->textHR;

		$oc ++;
	}

	return (array) $eq;
}


function get_yacht_equipment_name_en_hr( $id, $with_price = 0 ) {
	$equipment = json_decode( get_option( 'yachting_wp_api_equipment' ) );
	$eq        = [];
	$oc        = 0;
	foreach ( $equipment->equipment as $key => $struct ) {
		if ( $id == $struct->id ) {
			$eq['textEN'] = $struct->name->textEN;
			$eq['textHR'] = $struct->name->textHR;
			if ( isset( $struct->categoryId ) ) {
				$eq['category_name'] = get_yacht_equipment_category_name( $struct->categoryId );
			}
		}

		$oc ++;

	}

	return (array) $eq;
}

function get_yacht_service_name_en_hr( $id, $with_price = 0 ) {
	$services = json_decode( get_option( 'yachting_wp_api_services' ) );
	$eq       = [];
	$oc       = 0;
	foreach ( $services->services as $key => $struct ) {
		if ( $id == $struct->id ) {
			$eq['textEN'] = $struct->name->textEN;
			$eq['textHR'] = $struct->name->textHR;
		}
		$oc ++;
	}

	return (array) $eq;
}

function get_yacht_equipment_measure_en_hr( $id, $with_price = 0 ) {
	$measures = json_decode( get_option( 'yachting_wp_api_measures' ) );
	$eq       = [];
	$oc       = 0;
	foreach ( $measures->priceMeasures as $key => $struct ) {
		if ( $id == $struct->id ) {
			$eq['textEN'] = $struct->name->textEN;
			$eq['textHR'] = $struct->name->textHR;
		}
		$oc ++;

	}

	return (array) $eq;
}

function get_yacht_equipment_data_en_hr( $ids, $with_price = 0 ) {
	$equipment = json_decode( get_option( 'yachting_wp_api_equipment' ) );
	$eq        = [];
	$oc        = 0;
	foreach ( $equipment->equipment as $key => $struct ) {
		foreach ( $ids as $id ) {
			if ( $id == $struct->id ) {
				$eq[ $oc ]['textEN'] = $struct->name->textEN;
				$eq[ $oc ]['textHR'] = $struct->name->textHR;
				if ( isset( $struct->categoryId ) ) {
					$eq[ $oc ]['category_name'] = get_yacht_equipment_category_name( $struct->categoryId );
				}
			}
			$oc ++;
		}

	}

	return $eq;
}


function get_yacht_services_data_en_hr( $ids, $with_price = 0 ) {
	$service = json_decode( get_option( 'yachting_wp_api_services' ) );
	$eq      = [];
	$oc      = 0;
	foreach ( $service->services as $key => $struct ) {
		foreach ( $ids as $id ) {
			if ( $id == $struct->id ) {
				$eq[ $oc ]['textEN'] = $struct->name->textEN;
				$eq[ $oc ]['textHR'] = $struct->name->textHR;
			}
			$oc ++;
		}

	}

	return $eq;
}

function get_yacht_categories( $id ) {

	$categories = json_decode( get_option( 'yachting_wp_api_categories' ) );
	$item       = null;
	$cat        = [];
	foreach ( $categories->categories as $key => $struct ) {
		if ( $id == $struct->id ) {
			$cat[] = $struct->name->textEN;
			$cat[] = $struct->name->textHR;
		}

	}

	return $cat;
}

function get_yacht_location( $id ) {

	$locations = json_decode( get_option( 'yachting_wp_api_locations' ) );
	$item      = null;
	$cat       = [];
	foreach ( $locations->locations as $key => $struct ) {
		if ( $id == $struct->id ) {
			$cat[] = $struct->name->textEN;
			$cat[] = $struct->name->textHR;
		}

	}

	return $cat;
}

function get_yacht_sail_type( $id ) {

	$sail_types = json_decode( get_option( 'yachting_wp_api_sail_types' ) );
	$item       = null;
	$cat        = [];
	foreach ( $sail_types->sailTypes as $key => $struct ) {
		if ( $id == $struct->id ) {
			$cat[] = $struct->name->textEN;
			$cat[] = $struct->name->textHR;
		}

	}

	return $cat;
}

function get_yacht_steering_type( $id ) {

	$steering_types = json_decode( get_option( 'yachting_wp_api_steering_types' ) );
	$item           = null;
	$cat            = [];
	foreach ( $steering_types->steeringTypes as $key => $struct ) {
		if ( $id == $struct->id ) {
			$cat[] = $struct->name->textEN;
			$cat[] = $struct->name->textHR;
		}

	}

	return $cat;
}


function get_yacht_region_id( $id ) {
	$locations = json_decode( get_option( 'yachting_wp_api_locations' ) );
	$item      = null;
	foreach ( $locations->locations as $key => $struct ) {
		if ( $id == $struct->id ) {
			$item = $struct;
			break;
		}
	}

	return $item->regionId;
}

function get_yacht_country_id( $id ) {
	$locations = json_decode( get_option( 'yachting_wp_api_regions' ) );
	$item      = null;
	foreach ( $locations->regions as $key => $struct ) {
		if ( $id == $struct->id ) {
			$item = $struct;
			break;
		}
	}

	return $item->countryId;
}


function get_yacht_builder_name( $id ) {
	$builders = json_decode( get_option( 'yachting_wp_api_builders' ) );
	$item     = null;
	foreach ( $builders->builders as $key => $struct ) {
		if ( $id == $struct->id ) {
			$item = $struct->name;
			break;
		}
	}

	return $item;
}

function get_yacht_country_code( $id ) {
	$countries = json_decode( get_option( 'yachting_wp_api_countries' ) );
	$item      = null;
	foreach ( $countries->countries as $key => $struct ) {
		if ( $id == $struct->id ) {
			$item = $struct->code;
			break;
		}
	}

	return $item;
}

function get_yacht_category( $id ) {
	$countries = json_decode( get_option( 'yachting_wp_api_countries' ) );
	$item      = null;
	foreach ( $countries->countries as $key => $struct ) {
		if ( $id == $struct->id ) {
			$item = $struct->code;
			break;
		}
	}

	return $item;
}

function get_yacht_aditional_equipement_ids( $equipment_obj ) {
	$term_arg = array_map( function ( $item ) {
		return $item->equipmentId;
	}, $equipment_obj );


	return $term_arg;
}

function get_yacht_aditional_service_ids( $services_obj ) {
	$term_arg = array_map( function ( $item ) {
		return $item->serviceId;
	}, $services_obj );


	return $term_arg;
}

function get_yacht_reservations( $yacht_id ) {
	$reservations    = json_decode( get_option( 'yachting_wp_api_reservations' ) );
	$reservation_arr = [];
	foreach ( $reservations->reservations as $key => $struct ) {
		if ( $yacht_id == $struct->yachtId && $struct->reservationType == 'RESERVATION' ) {
//			$from = DateTime::createFromFormat( "Y-m-d", $yacht_reservation_period[ $x ]['from'] );
//			$to = DateTime::createFromFormat( "Y-m-d", $yacht_reservation_period[ $x ]['to'] );
			$reservation_arr[ $key ]['from'] = date( "Y-m-d", strtotime( $struct->periodFrom ) );
			$reservation_arr[ $key ]['to']   = date( "Y-m-d", strtotime( $struct->periodTo ) );
			$reservation_arr[ $key ]['year'] = $year = date( 'Y', strtotime( $struct->periodFrom ) );
		}
	}

	return $reservation_arr;
}

function yacht_has_reservation( $yacht_id ) {
	$reservations    = json_decode( get_option( 'yachting_wp_api_reservations' ) );
	$reservation_arr = [];
	$status          = false;
	foreach ( $reservations->reservations as $key => $struct ) {
		if ( $yacht_id == $struct->yachtId && $struct->reservationType == 'RESERVATION' ) {
			$status = true;
		}

	}

	return $status;
}

function save_database_table_info() {

	$yachts       = json_decode( get_option( 'yachting_wp_api_yachts' ) );
	$reservations = json_decode( get_option( 'yachting_wp_api_reservations' ) );

	$equipment_data = null;
	global $wpdb;
	$user_id = get_current_user_id();
	$k       = 0;
	foreach ( $yachts->yachts as $yacht_key => $result ) {

		$yacht_id         = $yachts->yachts[ $yacht_key ]->id;
		$aditional_eq_ids = [];
		//if ( get_post_meta( $yacht_id, 'yacht_id', true ) == $yacht_id ) {

		$yacht_model                = get_yacht_model( $yachts->yachts[ $yacht_key ]->yachtModelId );
		$result->model_data['info'] = $yacht_model;
		$yacht_category_id          = $yacht_model->yachtCategoryId;
		$yacht_location_id          = $result->locationId;
		$equipment_ids              = get_yacht_equipment_ids( $result->standardYachtEquipment );
		$region_id                  = get_yacht_region_id( $result->locationId ); //returns region_id
		$country_id                 = get_yacht_country_id( $region_id );
		$country_code               = get_yacht_country_code( $country_id ); //returns country code per yacht
		$yacht_category             = get_yacht_categories( $yacht_category_id );
		$location_info              = get_yacht_location( $yacht_location_id ); // marina
		$equipment_info             = get_yacht_equipment_data_en_hr( $equipment_ids ); //standard equipment
		$eq_prices                  = [];
		$addit_eq_names             = [];
		$adt_ids                    = get_yacht_eq_ids( $result->seasonSpecificData[0]->additionalYachtEquipment );
		$service_ids                = get_yacht_service_ids( $result->seasonSpecificData[0]->services );
		//get builder name
		$builder_name = get_yacht_builder_name( $yacht_model->yachtBuilderId );

		//get sail_type
		$sail_type = null;
		if ( isset( $yachts->yachts[ $yacht_key ]->sailTypeId ) ) {
			$sail_type = get_yacht_sail_type( $yachts->yachts[ $yacht_key ]->sailTypeId );
		}

		//get steering type
		$steering_type = null;
		if ( isset( $yachts->yachts[ $yacht_key ]->steeringTypeId ) ) {
			$steering_type = get_yacht_steering_type( $yachts->yachts[ $yacht_key ]->steeringTypeId );
		}


		foreach ( $yachts->yachts[ $yacht_key ]->seasonSpecificData as $season_key => $data ) {

			foreach ( $data->additionalYachtEquipment as $ky => $index ) {
				foreach ( $adt_ids as $id ) {
					if ( $id == $index->equipmentId ) {
						$aditional_eq_ids[ $ky ]              = get_yacht_equipment_name_en_hr( $id );
						$aditional_eq_ids[ $ky ]['measure']   = get_yacht_equipment_measure_en_hr( $index->priceMeasureId );
						$aditional_eq_ids[ $ky ]['price']     = $index->price;
						$aditional_eq_ids[ $ky ]['condition'] = (array) $index->condition;

					}
				}
			}
		}

		$aditional_eq_with_prices = $aditional_eq_ids; //use in additional equipment loop
		$services = [];
		//get services
		foreach ( $yachts->yachts[ $yacht_key ]->seasonSpecificData as $season_key => $data ) {
			foreach ( $data->services as $ky => $index ) {
				foreach ( $service_ids as $id ) {
					if ( $id == $index->serviceId ) {
						$services[ $ky ]                = get_yacht_service_name_en_hr( $id );
						$services[ $ky ]['measure']     = get_yacht_equipment_measure_en_hr( $index->priceMeasureId );
						$services[ $ky ]['price']       = $index->price;
						$services[ $ky ]['description'] = (array) $index->description;

					}
				}
			}
		}

		$has_reservation          = yacht_has_reservation( $yacht_id );
		$yacht_reservation_period = false;
		if ( $has_reservation != false ) {
			$yacht_reservation_period = get_yacht_reservations( $yacht_id );
		}


		//handle data saving

		$count = $wpdb->get_var( "SELECT COUNT(*) FROM $wpdb->prefix" . "posts WHERE ID = '$yacht_id'" ); // check if entry already exists
		//insert yacht entry
		if ( $count == 0 ) {

			$yacht = array(
				'import_id'   => $yacht_id,
				'post_title'  => $yacht_model->name . ' ' . $yachts->yachts[ $yacht_key ]->name,
				'post_status' => 'publish',
				'post_author' => $user_id,
				'post_type'   => 'yachts',
			);

			$id = wp_insert_post( $yacht );

			update_post_meta( $id, 'yacht_beam', $yacht_model->beam );
			if ( isset( $yachts->yachts[ $yacht_key ]->sailTypeId ) ) {
				update_post_meta( $id, 'yacht_sail_type_en', $sail_type[0] );
				update_post_meta( $id, 'yacht_sail_type_hr', $sail_type[1] );
			}

			if ( isset( $yachts->yachts[ $yacht_key ]->steeringTypeId ) ) {
				update_post_meta( $id, 'yacht_steering_type_en', $steering_type[0] );
				update_post_meta( $id, 'yacht_steering_type_hr', $steering_type[1] );
			}

			update_post_meta( $id, 'yacht_builder', $builder_name );
			update_post_meta( $id, 'yacht_security_deposit', $yachts->yachts[ $yacht_key ]->deposit );
			update_post_meta( $id, 'yacht_main_image_url', $yachts->yachts[ $yacht_key ]->mainPictureUrl );
			update_post_meta( $id, 'yacht_buildyear', $yachts->yachts[ $yacht_key ]->buildYear );
			update_post_meta( $id, 'yacht_cabins', $yacht_model->cabins );
			update_post_meta( $id, 'yacht_displacement', $yacht_model->displacement );
			update_post_meta( $id, 'yacht_draft', $yacht_model->draft );
			update_post_meta( $id, 'yacht_fuel_tank', $yachts->yachts[ $yacht_key ]->fuelTank );
			update_post_meta( $id, 'yacht_id', $yacht_id );
			update_post_meta( $id, 'yacht_loa', $yacht_model->loa );
			update_post_meta( $id, 'yacht_water_tank', $yachts->yachts[ $yacht_key ]->waterTank );
			update_post_meta( $id, 'yacht_wc', $yacht_model->wc );
			update_post_meta( $id, 'yacht_berths', $yachts->yachts[ $yacht_key ]->berthsCabin );
			update_post_meta( $id, 'yacht_berths_additional', $yachts->yachts[ $yacht_key ]->berthsSalon );
			update_post_meta( $id, 'yacht_type_eng', $yacht_category[0] );
			update_post_meta( $id, 'yacht_type_hr', $yacht_category[1] );
			update_post_meta( $id, 'yacht_country_code', $country_code );
			update_post_meta( $id, 'yacht_marina_eng', $location_info[0] );
			update_post_meta( $id, 'yacht_marina_hr', $location_info[1] );

			if ( isset( $yachts->yachts[ $yacht_key ]->enginePower ) || ! empty( $yachts->yachts[ $yacht_key ]->enginePower ) ) {
				update_post_meta( $id, 'yacht_engine', $yachts->yachts[ $yacht_key ]->enginePower );
			}
			$x = 0;
			foreach ( $equipment_info as $equipment ) {
				$length = count( $equipment_info );

				update_post_meta( $id, 'yacht_equipment_en_' . str_replace( " ", "_", trim( strtolower( $equipment['category_name']['textEN'] ) ) ) . '_' . $x, $equipment['textEN'] );
				update_post_meta( $id, 'yacht_equipment_hr_' . str_replace( " ", "_", trim( strtolower( $equipment['category_name']['textHR'] ) ) ) . '_' . $x, $equipment['textHR'] );

				$x ++;
			}
			update_post_meta( $id, 'yacht_equipment_count', $x );
          
			$x = 0;
			foreach ( $yachts->yachts[ $yacht_key ]->seasonSpecificData[0]->prices as $price ) {
				$length = count($yachts->yachts[ $yacht_key ]->seasonSpecificData[0]->prices) - 1;
				update_post_meta( $id, 'yacht_date_from_' . $x, $price->dateFrom );
				update_post_meta( $id, 'yacht_date_to_' . $x, $price->dateTo );
				update_post_meta( $id, 'yacht_date_price_' . $x, $price->price );
				$x++;
				update_post_meta( $id, 'yacht_date_count_' . $yacht_id, $length );
			}

			$oc = 0;
			foreach ( $services as $service ) {
				update_post_meta( $id, 'yacht_service_en_' . $oc, $service['textEN'] );
				update_post_meta( $id, 'yacht_service_hr_' . $oc, $service['textHR'] );
				if ( isset( $service['description']->textEN ) ) {
					update_post_meta( $id, 'yacht_service_desc_en_' . $oc, $service['description']->textEN );
				}
				if ( isset( $service['description']->textHR ) ) {
					update_post_meta( $id, 'yacht_service_desc_en_' . $oc, $service['description']->textHR );
				}
				update_post_meta( $id, 'yacht_service_price_' . $oc, $service['price'] );
				$oc ++;
			}

			$oc = 0;
			foreach ( $yachts->yachts[ $yacht_key ]->picturesURL as $item ) {
				update_post_meta( $id, 'yacht_pictures_' . $oc, $item );
				$oc ++;
			}
			update_post_meta( $id, 'yacht_pictures_count_' . $yacht_id, $oc );

			if ( $yacht_reservation_period ) {
				$length                   = count( $yacht_reservation_period );
				$yacht_reservation_period = array_values( $yacht_reservation_period );
				for ( $x = 0; $x <= $length; $x ++ ) {
					if ( isset( $yacht_reservation_period[ $x ]['year'] ) && isset( $yacht_reservation_period[ $x ]['from'] ) ) {

						update_post_meta( $id, 'yacht_reservation_date_from_' . $yacht_reservation_period[ $x ]['year'] . '_' . $x, $yacht_reservation_period[ $x ]['from'] );
						update_post_meta( $id, 'yacht_reservation_date_to_' . $yacht_reservation_period[ $x ]['year'] . '_' . $x, $yacht_reservation_period[ $x ]['to'] );
					}
				}
				update_post_meta( $id, 'yacht_reservation_count_' . date( 'Y' ), $x );
			}

		} else {
			$yacht = array(
				'ID'          => $yacht_id,
				'post_title'  => $yacht_model->name . ' ' . $yachts->yachts[ $yacht_key ]->name,
				'post_status' => 'publish',
				'post_author' => $user_id,
				'post_type'   => 'yachts',
			);

			$id = wp_update_post( $yacht );
			$i  = 0;

			if ( $yacht_reservation_period ) {
				$length                   = count( $yacht_reservation_period );
				$yacht_reservation_period = array_values( $yacht_reservation_period );

				for ( $x = 0; $x <= $length; $x ++ ) {
					if ( isset( $yacht_reservation_period[ $x ]['year'] ) && isset( $yacht_reservation_period[ $x ]['from'] ) ) {

						update_post_meta( $id, 'yacht_reservation_date_from_' . $yacht_reservation_period[ $x ]['year'] . '_' . $x, $yacht_reservation_period[ $x ]['from'] );
						update_post_meta( $id, 'yacht_reservation_date_to_' . $yacht_reservation_period[ $x ]['year'] . '_' . $x, $yacht_reservation_period[ $x ]['to'] );
					}
				}
				update_post_meta( $id, 'yacht_reservation_count_' . date( 'Y' ), $x );
			}

			update_post_meta( $id, 'yacht_beam', $yacht_model->beam );
			if ( isset( $yachts->yachts[ $yacht_key ]->sailTypeId ) ) {
				update_post_meta( $id, 'yacht_sail_type_en', $sail_type[0] );
				update_post_meta( $id, 'yacht_sail_type_hr', $sail_type[1] );
			}
			if ( isset( $yachts->yachts[ $yacht_key ]->steeringTypeId ) ) {
				update_post_meta( $id, 'yacht_steering_type_en', $steering_type[0] );
				update_post_meta( $id, 'yacht_steering_type_hr', $steering_type[1] );
			}
			update_post_meta( $id, 'yacht_builder', $builder_name );
			update_post_meta( $id, 'yacht_security_deposit', $yachts->yachts[ $yacht_key ]->deposit );
			update_post_meta( $id, 'yacht_main_image_url', $yachts->yachts[ $yacht_key ]->mainPictureUrl );
			update_post_meta( $id, 'yacht_buildyear', $yachts->yachts[ $yacht_key ]->buildYear );
			update_post_meta( $id, 'yacht_cabins', $yacht_model->cabins );
			update_post_meta( $id, 'yacht_displacement', $yacht_model->displacement );
			update_post_meta( $id, 'yacht_draft', $yacht_model->draft );
			update_post_meta( $id, 'yacht_fuel_tank', $yachts->yachts[ $yacht_key ]->fuelTank );
			update_post_meta( $id, 'yacht_id', $yacht_id );
			update_post_meta( $id, 'yacht_loa', $yacht_model->loa );
			update_post_meta( $id, 'yacht_water_tank', $yachts->yachts[ $yacht_key ]->waterTank );
			update_post_meta( $id, 'yacht_wc', $yachts->yachts[ $yacht_key ]->wc );
			update_post_meta( $id, 'yacht_berths', $yachts->yachts[ $yacht_key ]->berthsCabin );
			update_post_meta( $id, 'yacht_berths_additional', $yachts->yachts[ $yacht_key ]->berthsSalon );

			update_post_meta( $id, 'yacht_type_eng', str_replace( ' ', '-', strtolower( $yacht_category[0] ) ) );
			update_post_meta( $id, 'yacht_type_hr', str_replace( ' ', '-', strtolower( $yacht_category[1] ) ) );
			update_post_meta( $id, 'yacht_country_code', $country_code );
			update_post_meta( $id, 'yacht_marina_eng', $location_info[0] );
			update_post_meta( $id, 'yacht_marina_hr', $location_info[1] );

			if ( isset( $yachts->yachts[ $yacht_key ]->numberOfRudderBlades ) || ! empty( $yachts->yachts[ $yacht_key ]->numberOfRudderBlades ) ) {
				update_post_meta( $id, 'yacht_rudder_num', $yachts->yachts[ $yacht_key ]->numberOfRudderBlades );
			}
			if ( isset( $yachts->yachts[ $yacht_key ]->enginePower ) || ! empty( $yachts->yachts[ $yacht_key ]->enginePower ) ) {
				update_post_meta( $id, 'yacht_engine', $yachts->yachts[ $yacht_key ]->enginePower );
				update_post_meta( $id, 'yacht_engine_quantity', $yachts->yachts[ $yacht_key ]->engines );
			}

			$x = 0;
			foreach ( $equipment_info as $equipment ) {
				$length = count( $equipment_info );

				update_post_meta( $id, 'yacht_equipment_en_' . str_replace( " ", "_", trim( strtolower( $equipment['category_name']['textEN'] ) ) ) . '_' . $x, $equipment['textEN'] );
				update_post_meta( $id, 'yacht_equipment_hr_' . str_replace( " ", "_", trim( strtolower( $equipment['category_name']['textHR'] ) ) ) . '_' . $x, $equipment['textHR'] );

				$x ++;
			}
			update_post_meta( $id, 'yacht_equipment_count', $x );
			$x = 0;
			foreach ( $yachts->yachts[ $yacht_key ]->seasonSpecificData[0]->prices as $price ) {
                $length = count($yachts->yachts[ $yacht_key ]->seasonSpecificData[0]->prices) - 1;
						update_post_meta( $id, 'yacht_date_from_' . $x, $price->dateFrom );
						update_post_meta( $id, 'yacht_date_to_' . $x, $price->dateTo );
						update_post_meta( $id, 'yacht_date_price_' . $x, $price->price );
				$x++;
				update_post_meta( $id, 'yacht_date_count_' . $yacht_id, $length );
			}


			$oc = 0;
			foreach ( $services as $service ) {
				if ( $service['textEN'] == 'Base fee' ) {
					$service_name_en = str_replace( " ", "_", trim( strtolower( $service['textEN'] ) ) );
					$service_name_hr = str_replace( " ", "_", trim( strtolower( $service['textEN'] ) ) );
					update_post_meta( $id, 'yacht_service_en_' . $service_name_en, $service['textEN'] );
					update_post_meta( $id, 'yacht_service_hr_' . $service_name_hr, $service['textHR'] );
				} else {
					update_post_meta( $id, 'yacht_service_en_' . $oc, $service['textEN'] );
					update_post_meta( $id, 'yacht_service_hr_' . $oc, $service['textHR'] );
				}

				if ( isset( $service['description']['textEN'] ) ) {
					update_post_meta( $id, 'yacht_service_desc_en_' . $oc, $service['description']['textEN'] );
				}
				if ( isset( $service['description']['textHR'] ) ) {
					update_post_meta( $id, 'yacht_service_desc_hr_' . $oc, $service['description']['textHR'] );
				}
				if ( isset( $service['measure']['textEN'] ) ) {
					update_post_meta( $id, 'yacht_service_measure_en_' . $oc, $service['measure']['textEN'] );
					update_post_meta( $id, 'yacht_service_measure_hr_' . $oc, $service['measure']['textHR'] );
				}
				if ( $service['textEN'] == 'Base fee' ) {
					$service_name_en = str_replace( " ", "_", trim( strtolower( $service['textEN'] ) ) );
					$service_name_hr = str_replace( " ", "_", trim( strtolower( $service['textEN'] ) ) );
					update_post_meta( $id, 'yacht_service_price_' . $service_name_en, $service['price'] );
				} else {
					update_post_meta( $id, 'yacht_service_price_' . $oc, $service['price'] );
				}
				$oc ++;
			}
			update_post_meta( $id, 'yacht_service_count', $oc - 1 );

			$oc = 0;
			foreach ( $yachts->yachts[ $yacht_key ]->picturesURL as $item ) {
				update_post_meta( $id, 'yacht_pictures_' . $oc, $item );
				$oc ++;
			}
			update_post_meta( $id, 'yacht_pictures_count_' . $yacht_id, $oc );

			$x = 0;
			foreach ( $aditional_eq_with_prices as $season_key => $data ) {

				if ( isset( $data['category_name'] ) && ! empty( $data['category_name'] ) ) {
					$category_name_en = str_replace( " ", "_", trim( strtolower( $data['category_name']['textEN'] ) ) );
				} else {
					$category_name_en = 'other';
					$category_name_hr = 'drugo';
				}
				if ( isset( $data['category_name'] ) && count( $data['category_name'] ) > 1 ) {
					update_post_meta( $id, 'yacht_adit_eq_' . $category_name_en . '_en_' . $x, $data['category_name']['textEN'] );
				} else {
					update_post_meta( $id, 'yacht_adit_eq_' . $category_name_en . '_en_' . $x, $category_name_en );
				}

				if ( isset( $data['category_name'] ) && count( $data['category_name'] ) > 1 ) {
					update_post_meta( $id, 'yacht_adit_eq_name_' . $category_name_en . '_en_' . $x, $data['textEN'] );
				} else {
					update_post_meta( $id, 'yacht_adit_eq_name_' . $category_name_en . '_en_' . $x, $data['textEN'] );
				}

				update_post_meta( $id, 'yacht_adit_eq_measure_' . $category_name_en . '_en_' . $x, $data['measure']['textEN'] );
				update_post_meta( $id, 'yacht_adit_eq_price_' . $category_name_en . '_' . $x, $data['price'] );
				if ( isset( $data['condition'] ) && ! empty( $data['condition'] ) ) {

					if ( count( $data['condition'] ) > 1 ) {
						update_post_meta( $id, 'yacht_adit_eq_condition_' . $category_name_en . '_en_' . $x, $data['condition']['textEN'] );
					} else {
						update_post_meta( $id, 'yacht_adit_eq_condition_' . $category_name_en . '_en_' . $x, $category_name_en );
					}
				}
				$x ++;
			}
		}
	}
	$date = date( 'Y-m-d H:i:s' );
	if ( false === get_option( 'yachting_wp_api_yachts_update_time' ) ) {
		add_option( 'yachting_wp_api_yachts_update_time', $date );
	} else {
		update_option( 'yachting_wp_api_yachts_update_time', $date );
	}

}