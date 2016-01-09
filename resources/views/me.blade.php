@extends('layout')

@section('content')
	<section class="section my-playlists">
	    @foreach($my_playlists as $playlist)
			<div class="one-playlist {{ $playlist->public ? 'is-public' : '' }}" data-playlist-id="{{ $playlist->id }}">
				<h3 class="title">{{ $playlist->name }}</h3>

				<div class="playlist-tracks">
					{{ $playlist->tracks->total }} tracks.
				</div>

				<div class="playlist-dump" style="display: none">
					<pre>{{ print_r($playlist, true) }}</pre>
				</div>
			</div>
	    @endforeach
	</section>
@endsection