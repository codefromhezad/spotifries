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

        // $tracks = $spotify_helper->api->getUserPlaylistTracks('hezad', '2xqWecx25al1glOfnHEZ0s');
        // dd($tracks);

        // $track = $spotify_helper->api->getTrack('5vLukeFJG2VmHt73EwpsBP');
        // dd($track);
        
        return view('me', [
            'my_playlists' => $spotify_helper->user_playlists()
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
