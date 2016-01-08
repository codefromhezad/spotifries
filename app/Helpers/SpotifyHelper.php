<?php

namespace App\Helpers;

class SpotifyHelper {
	public static $instance;

	public $spotify_session;
	public $api;

	public $access_token;
	public $refresh_token;

	public $is_connected = false;

	public function __construct() {
		$this->spotify_session = new \SpotifyWebAPI\Session(env('SPOTIFY_CLIENT_ID'), env('SPOTIFY_CLIENT_SECRET'), env('SPOTIFY_REDIRECT_URL'));
		$this->api = new \SpotifyWebAPI\SpotifyWebAPI();

		if( session('spotify_access_token') ) {
			$this->access_token = session('spotify_access_token');
            $this->refresh_token = session('spotify_refresh_token');

            $this->api->setAccessToken($this->access_token);

			$this->is_connected = true;
		}
	}

	public function redirect_to_spotify_auth() {
		header('Location: ' . $this->spotify_session->getAuthorizeUrl(array(
            'scope' => explode(' ', env('SPOTIFY_SCOPES'))
        )));
        die();
	}

	public function refresh_access_token() {
		$this->spotify_session->refreshAccessToken($this->refresh_token);
		
		$this->access_token = $this->spotify_session->getAccessToken();
		$this->refresh_token = $this->spotify_session->getRefreshToken();
		$this->api->setAccessToken($this->access_token);
	}

	public function request_access_token($code) {
		$this->spotify_session->requestAccessToken($code);

		$this->access_token = $this->spotify_session->getAccessToken();
        $this->refresh_token = $this->spotify_session->getRefreshToken();

        $this->api->setAccessToken($this->access_token);

        session('spotify_access_token', $this->access_token);
        session('spotify_refresh_token', $this->refresh_token);
	}

	public function connect() {
		if (isset($_GET['code'])) {
            $this->request_access_token($_GET['code']);

            $this->is_connected = true;

            return true;
        } else {
            $this->redirect_to_spotify_auth();
        }
	}

	public static function instance() {
		if( ! self::$instance ) {
			self::$instance = new SpotifyHelper();
		}

		return self::$instance;
	}
}


function get_spotify_local_token() {
	return session('spotify_access_token');
}