<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf8">
        
        <title>Spotifries</title>

    </head>
    <body class="{{ Route::currentRouteName() }}">
        
        <div class="top-menu">
			@if( \App\Helpers\SpotifyHelper::instance()->is_connected )
				<div class="user">
					<span class="username">{{ \App\Helpers\SpotifyHelper::instance()->user('name') }}</span>
				</div>

		        <nav>
		        	<a href="{{ action('SpotifyController@me') }}">Explore</a>
		        </nav>
		    @else
		    	<div class="user">
					Guest (Not connected)
				</div>
				
		    	<nav>
		        	<a href="{{ action('SpotifyController@connect') }}">Connect your spotify account</a>
		        </nav>
		    @endif
        </div>

        <div class="app-content">
        	@yield('content')
        </div>
    </body>
</html>
