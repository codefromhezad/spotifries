@extends('layout')

@section('content')
	<section class="section my-playlists">
		<h2>My playlists <small>({{ $my_playlists->total }} playlists)</small></h2>

	    @foreach($my_playlists->items as $playlist)
			<div class="one-playlist {{ $playlist->public ? 'is-public' : '' }}" data-playlist-id="{{ $playlist->id }}">
				<h4 class="title">{{ $playlist->name }} <small>({{ $playlist->tracks->total }} tracks.)</small></h4>

				<div class="playlist-dump" style="display: none">
					<pre>{{ print_r($playlist, true) }}</pre>
				</div>
			</div>
	    @endforeach
	</section>
@endsection