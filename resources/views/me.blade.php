@extends('layout')

@section('content')
	<section class="section my-playlists">
		<h2>My playlists</h2>

	    @foreach($my_playlists as $playlist)
			<div class="one-playlist" data-playlist-id="{{ $playlist->id }}">
				<h4 class="title">{{ $playlist->name }}</h4>

				<div class="playlist-dump" style="display: none">
					<pre>{{ print_r($playlist, true) }}</pre>
				</div>
			</div>
	    @endforeach
	</section>
@endsection