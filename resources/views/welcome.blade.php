@extends('layout')

@section('content')
    <div>
        <a href="{{ action('SpotifyController@connect') }}">Connect spotify account</a>
    </div>
@endsection