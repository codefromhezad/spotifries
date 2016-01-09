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
		if( ! \Session::get('spotify_user_id') ) {
			$user = $this->sync_user();
		} else {
			$user = \App\User::find(\Session::get('spotify_user_id'));
		}

		if( $prop === null ) {
			return $user;
		} else {
			return $user[$prop];
		}
	}

	public function user_playlists() {
		$user_id = $this->user('id');

		$db_playlists = \App\Playlist::where('user_id', $user_id)
									 ->get();

		if( ! $db_playlists || ! count( $db_playlists ) ) {
			$db_playlists = $this->sync_user_playlists();
		}
		
		return $db_playlists;
	}

	public function sync_user() {
		$api_user = $this->api->me();
		$db_user = \App\User::find($api_user->id);

		if( ! $db_user ) {
			$db_user = new \App\User();
		}

		$db_user->id = $api_user->id;
		$db_user->name = $api_user->display_name;
		$db_user->email = $api_user->email;

		\Session::set('spotify_user_id', $db_user->id);

		$db_user->save();

		return $db_user;
	}

	public function sync_user_playlists() {
		$api_playlists = $this->api->getMyPlaylists();
		$db_playlists = [];
		$user_id = $this->user('id');

		// This will only sync the 10 first playlists.
		// TODO: Handle API's pagination to sync everything
		foreach($api_playlists->items as $api_playlist) {
			$db_playlist = \App\Playlist::where('id', $api_playlist->id)
										->where('user_id', $user_id)
										->first();

			if( ! $db_playlist ) {
				$db_playlist = new \App\Playlist();
			}

			$db_playlist->id = $api_playlist->id;
			$db_playlist->name = $api_playlist->name;
			$db_playlist->user_id = $user_id;

			$db_playlist->save();

			$db_playlists[] = $db_playlist;
		}

		return $db_playlists;
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
