@extends('layout')

@section('content')
	<section class="section my-playlists">
		<h2>My playlists</h2>

	    @foreach($my_playlists as $playlist)
			<div class="one-playlist" data-playlist-id="{{ $playlist->id }}">
				<h4 class="title">{{ $playlist->name }}</h4>
				
				<a href="#" class="load-playlist-tracks">See tracks</a>
				<!-- TODO: onclick('.load-playlist-tracks') => AJAX Request to load tracks -->
				<div class="playlist-tracks">
					
				</div>
			</div>
	    @endforeach
	</section>
@endsection