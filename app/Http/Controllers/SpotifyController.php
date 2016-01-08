<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Helpers\SpotifyHelper;

class SpotifyController extends Controller
{
    public function see() {
        $spotify_helper = SpotifyHelper::instance();

        dd( $spotify_helper );
        die;

        if( ! $spotify_helper->is_connected ) {
            return redirect()->action('SpotifyController@connect');
        }

        
    }

    public function connect() {
        $spotify_helper = SpotifyHelper::instance();
        
        $spotify_helper->connect();

        if( $spotify_helper->is_connected ) {
            return redirect()->action('SpotifyController@see');
        }
    }
}
