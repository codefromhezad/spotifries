<?php

namespace App\Helpers;

class SpotifyHelper {
	public static $instance;

	public $spotify_session;
	public $api;

	public $is_connected = false;

	public function __construct() {
		$this->spotify_session = new \SpotifyWebAPI\Session(env('SPOTIFY_CLIENT_ID'), env('SPOTIFY_CLIENT_SECRET'), env('SPOTIFY_REDIRECT_URL'));
		$this->api = new \SpotifyWebAPI\SpotifyWebAPI();

		if( $this->refresh_token() ) {
			// Refresh Access Token from session
			$this->spotify_session->refreshAccessToken($this->refresh_token());
			
			// save new access token and setup API with this new token
			$this->access_token( $this->spotify_session->getAccessToken() );
			$this->api->setAccessToken($this->access_token());

			// Save new refresh token
			$this->refresh_token( $this->spotify_session->getRefreshToken() );

			// Connected !
			$this->is_connected = true;
		}
	}

	public function access_token($value = null) {
		if( $value !== null ) {
			\Session::set('spotify_access_token', $value);
		} else {
			return \Session::get('spotify_access_token');
		}
	}

	public function refresh_token($value = null) {
		if( $value !== null ) {
			\Session::set('spotify_refresh_token', $value);
		} else {
			return \Session::get('spotify_refresh_token');
		}
	}

	public function user($prop = null) {
		if( ! \Session::get('spotify_user') ) {
			\Session::set('spotify_user', $this->api->me());
		}

		return \Session::get('spotify_user');
	}

	public function connect() {
		if( isset($_GET['code']) ) {
			// Request new access token
			$this->spotify_session->requestAccessToken($_GET['code']);
			
			// save new access token and setup API with this new token
			$this->access_token( $this->spotify_session->getAccessToken() );
			$this->api->setAccessToken($this->access_token());

			// Save new refresh token
			$this->refresh_token( $this->spotify_session->getRefreshToken() );

			// Connected ! 
			$this->is_connected = true;
        } else {
            header('Location: ' . $this->spotify_session->getAuthorizeUrl(array(
	            'scope' => explode(' ', env('SPOTIFY_SCOPES'))
	        )));
	        die();
        }
	}

	public static function instance() {
		if( ! self::$instance ) {
			self::$instance = new SpotifyHelper();
		}

		return self::$instance;
	}
}
