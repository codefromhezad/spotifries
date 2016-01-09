<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Helpers\SpotifyHelper;

class SpotifyController extends Controller
{
    public function me() {
        $spotify_helper = SpotifyHelper::instance();

        if( ! $spotify_helper->is_connected ) {
            return redirect()->action('SpotifyController@connect');
        }

        $playlists = $spotify_helper->api->getMyPlaylists();

        return view('me', [
            'my_playlists' => $playlists->items
        ]);
    }

    public function connect() {
        $spotify_helper = SpotifyHelper::instance();
        
        $spotify_helper->connect();

        if( $spotify_helper->is_connected ) {
            return redirect()->action('SpotifyController@me');
        }
    }
}
