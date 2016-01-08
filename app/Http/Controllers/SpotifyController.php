<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Helpers\SpotifyHelper;

class SpotifyController extends Controller
{
    public function see() {
        if( ! SpotifyHelper::instance()->is_connected ) {
            return redirect()->action('SpotifyController@connect');
        }

        
    }


    public function connect() {
        $spotify_helper = SpotifyHelper::instance();

        if( isset($_GET['code']) ) {
            $spotify_helper->request_access_token($_GET['code']);
        } else {
            $spotify_helper->redirect_to_spotify_auth();
        }

        if( $spotify_helper->connect() ) {
            return redirect()->action('SpotifyController@see');
        }
    }
}
