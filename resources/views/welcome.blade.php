@extends('layout')

@section('content')
    @if( \App\Helpers\SpotifyHelper::instance()->is_connected )
        <a href="{{ action('SpotifyController@me') }}">Explore</a>
    @else
        <a href="{{ action('SpotifyController@connect') }}">Connect your spotify account first</a>
    @endif
@endsection