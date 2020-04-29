<?php
namespace Jet_Dashboard;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Define Jet_Dashboard_License_Manager class
 */
class License_Manager {

	/**
	 * [$slug description]
	 * @var boolean
	 */
	public $license_data_key = 'jet-license-data';

	/**
	 * [$sys_messages description]
	 * @var array
	 */
	public $sys_messages = [];

	/**
	 * Init page
	 */
	public function __construct() {

		$this->sys_messages = apply_filters( 'jet_dashboard_license_sys_messages', array(
			'internal'     => 'Internal error. Please, try again later',
			'server_error' => 'Server error. Please, try again later',
		) );

		add_action( 'wp_ajax_jet_license_action', array( $this, 'jet_license_action' ) );

		$this->license_expire_check();

		$this->maybe_theme_core_license_exist();
	}

	/**
	 * [maybe_theme_core_license_exist description]
	 * @return [type] [description]
	 */

	public function maybe_theme_core_license_exist() {

		$jet_theme_core_key = get_option( 'jet_theme_core_license', false );

		if ( ! $jet_theme_core_key ) {
			return false;
		}

		$jet_theme_core_license_sync = get_option( 'jet_theme_core_sync', 'false' );

		if ( filter_var( $jet_theme_core_license_sync, FILTER_VALIDATE_BOOLEAN ) ) {
			return false;
		}

		$license_list = Utils::get_license_data( 'license-list', [] );

		if ( array_key_exists( $jet_theme_core_key, $license_list ) ) {
			return false;
		}

		$responce = $this->license_action_query( 'activate_license', $jet_theme_core_key );

		$responce_data = isset( $responce['data'] ) ? $responce['data'] : [];

		$license_list[ $jet_theme_core_key ] = array(
			'licenseStatus'  => 'active',
			'licenseKey'     => $jet_theme_core_key,
			'licenseDetails' => $responce_data,
		);

		update_option( 'jet_theme_core_sync', 'true' );

		if ( 'error' === $responce['status'] ) {

			Utils::set_license_data( 'license-list', $license_list );

			return false;
		}

		Utils::set_license_data( 'license-list', $license_list );
	}

	/**
	 * [license_expire_check description]
	 * @return [type] [description]
	 */
	public function license_expire_check() {
		$jet_dashboard_license_expire_check = get_site_transient( 'jet_dashboard_license_expire_check' );

		if ( $jet_dashboard_license_expire_check ) {
			return false;
		}

		Utils::license_data_expire_sync();

		set_site_transient( 'jet_dashboard_license_expire_check', 'true', HOUR_IN_SECONDS * 12 );
	}

	/**
	 * Proccesing subscribe form ajax
	 *
	 * @return void
	 */
	public function jet_license_action() {

		$data = ( ! empty( $_POST['data'] ) ) ? $_POST['data'] : false;

		if ( ! $data ) {
			wp_send_json(
				array(
					'status'  => 'error',
					'message' => $this->sys_messages['server_error'],
					'data'    => [],
				)
			);
		}

		$license_action = $data['action'];

		$license_key = $data['license'];

		if ( empty( $license_key ) && isset( $data['plugin'] ) ) {
			$license_key = Utils::get_plugin_license_key( $data['plugin'] );
		}

		$responce = $this->license_action_query( $license_action . '_license', $license_key );

		$responce_data = [];

		if ( 'error' === $responce['status'] ) {

			wp_send_json(
				array(
					'status'  => 'error',
					'message' => $responce['message'],
					'data'    => isset( $responce['data'] ) ? $responce['data'] : [],
				)
			);
		}

		if ( isset( $responce['data'] ) ) {
			$responce_data = $responce['data'];
		}

		switch ( $license_action ) {
			case 'activate':
				$this->update_license_list( $license_key, $responce_data );
			break;

			case 'deactivate':
				$license_list = Utils::get_license_data( 'license-list', [] );
				unset( $license_list[ $license_key ] );
				Utils::set_license_data( 'license-list', $license_list );
			break;
		}

		$responce_data['license_key'] = $license_key;

		set_site_transient( 'update_plugins', null );

		wp_send_json(
			array(
				'status'  => 'success',
				'message' => $responce['message'],
				'data'    => $responce_data,
			)
		);
	}

	/**
	 * [update_license_list description]
	 * @param  boolean $responce [description]
	 * @return [type]            [description]
	 */
	public function update_license_list( $license_key = '', $responce = false ) {

		$license_list = Utils::get_license_data( 'license-list', [] );

		$license_list[ $license_key ] = array(
			'licenseStatus'  => 'active',
			'licenseKey'     => $license_key,
			'licenseDetails' => $responce,
		);

		Utils::set_license_data( 'license-list', $license_list );
	}

	/**
	 * Remote request to updater API.
	 *
	 * @since  1.0.0
	 * @return array|bool
	 */
	public function license_action_query( $action = '', $license = '' ) {

		$query_url = add_query_arg(
			array(
				'action'   => $action,
				'license'  => $license,
				'site_url' => urlencode( Utils::get_site_url() ),
			),
			Utils::get_api_url()
		);

		$response = wp_remote_get( $query_url );

		if ( is_wp_error( $response ) || wp_remote_retrieve_response_code( $response ) != '200' ) {
			return false;
		}

		return json_decode( $response['body'], true );
	}
}
