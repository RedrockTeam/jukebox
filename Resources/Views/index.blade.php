<!DOCTYPE html>
<html lang="ch-zn">
<head>
	<meta charset="UTF-8">
	<title>重邮点歌台</title>
	<script>
	!function(a,b){function c(){var b=f.getBoundingClientRect().width;b/i>540&&(b=540*i);var c=b/10;f.style.fontSize=c+"px",k.rem=a.rem=c}var d,e=a.document,f=e.documentElement,g=e.querySelector('meta[name="viewport"]'),h=e.querySelector('meta[name="flexible"]'),i=0,j=0,k=b.flexible||(b.flexible={});if(g){console.warn("将根据已有的meta标签来设置缩放比例");var l=g.getAttribute("content").match(/initial\-scale=([\d\.]+)/);l&&(j=parseFloat(l[1]),i=parseInt(1/j))}else if(h){var m=h.getAttribute("content");if(m){var n=m.match(/initial\-dpr=([\d\.]+)/),o=m.match(/maximum\-dpr=([\d\.]+)/);n&&(i=parseFloat(n[1]),j=parseFloat((1/i).toFixed(2))),o&&(i=parseFloat(o[1]),j=parseFloat((1/i).toFixed(2)))}}if(!i&&!j){var p=(a.navigator.appVersion.match(/android/gi),a.navigator.appVersion.match(/iphone/gi)),q=a.devicePixelRatio;i=p?q>=3&&(!i||i>=3)?3:q>=2&&(!i||i>=2)?2:1:1,j=1/i}if(f.setAttribute("data-dpr",i),!g)if(g=e.createElement("meta"),g.setAttribute("name","viewport"),g.setAttribute("content","initial-scale="+j+", maximum-scale="+j+", minimum-scale="+j+", user-scalable=no"),f.firstElementChild)f.firstElementChild.appendChild(g);else{var r=e.createElement("div");r.appendChild(g),e.write(r.innerHTML)}a.addEventListener("resize",function(){clearTimeout(d),d=setTimeout(c,300)},!1),a.addEventListener("pageshow",function(a){a.persisted&&(clearTimeout(d),d=setTimeout(c,300))},!1),"complete"===e.readyState?e.body.style.fontSize=12*i+"px":e.addEventListener("DOMContentLoaded",function(){e.body.style.fontSize=12*i+"px"},!1),c(),k.dpr=a.dpr=i,k.refreshRem=c,k.rem2px=function(a){var b=parseFloat(a)*this.rem;return"string"==typeof a&&a.match(/rem$/)&&(b+="px"),b},k.px2rem=function(a){var b=parseFloat(a)/this.rem;return"string"==typeof a&&a.match(/px$/)&&(b+="rem"),b}}(window,window.lib||(window.lib={}));
	</script>
	<link rel="stylesheet" type="text/css" href="{{URL::asset('jukebox/css/index.css')}}">
</head>
<body>
	<header>
		<div id="moveLine"></div>
		<nav>
			<div class='header-nav choose-song-nav' id='action'>
				在线点歌
			</div>
			<div class='header-nav song-list-nav'>
				节目歌单
			</div>
			<div class='header-nav user-nav'>
				个人中心
			</div>
		</nav>
	</header>
	<section id='container'>
		<section class="content" id='chooseSong'>
			<div class="top-line"></div>
			<article class='notice'>
				<h2 class='notice-page'>
					{{ $announcement['represent_title'] }}
				</h2>
				<p class='article-time'>
					@datetime($announcement['updated_at'])
				</p>
				<!-- *
				*
				*
					公告填充
				*
				*
				* -->
				<p class='notice-content'>
					{{ $announcement['announcement'] }}
				</p>
				<!-- *
				*
				*
					公告填充
				*
				*
				* -->
			</article>
			@if(!empty($songs))
				@foreach($songs as $song)
				<article>
					<section class='user-outer'>
						<div class="user-inf">
							<p class='song-name'>
								<!-- 歌曲名 -->
								{{ $song['song_name'] }}—{{ $song['song_singer'] }}
							</p>
							<img class='user-img' src="{{ $song['avatar'] }}">
										<!-- 用户名 -->
							<span class='user-name'>by {{ $song['name'] }}</span>
						</div>
						<p class='time'>
							@datetime($song['created_at'])
							<!-- 时间 -->
						</p>
					</section>
					<section class='detail-outer'>
						<p class='to-who'>
							To: {{ $song['receiver'] }}
							<!-- 歌曲点给谁 -->
						</p>
						<p class='detail-content'>
							{{ $song['message'] }}
							<!-- 回复内容 -->
						</p>

						<!-- data-id 代表该条评论ID -->
						<!-- data-listen 1代表 能点赞class加上have-add-listen     0 代表不能点赞 class保持原样 -->
						<div class="want-listen" data-id='{{ $song['sheet_id'] }}' data-listen='{{ $song['wts-lst'] or 0 }}'>
							想听
							<span>
								{{ $song['likes'] }}
							</span>
						</div>
					</section>
				</article>
				@endforeach
			@else
				<div class="no-content">
					<p class='title-one'>暂无内容</p>
					<p class='title-two'>快去点歌吧</p>
				</div>
			@endif
		</section>
		<section class="content" id='songList'>
			@foreach($albums as $album)
			<a href="{{ URL::route('albums', ['album_id' => $album['album_id']]) }}">
				<div class="song-lister">
					<img class="song-img" src='{{ $album['album_cover'] }}' />
					<div class="song-inf">
						<h2 class="song-title">
							{{ $album['album_name'] }}
						</h2>
						<p class='by-user'>
							by {{ $album['album_author'] }}
						</p>
						<p class='song-time'>
							@datetime($album['broadcast_at'])
						</p>
					</div>			
				</div>
			</a>
			@endforeach
		</section>
		<section class="content" id='user'>
			<div class="user-trd">
				<img class='filter-user-img' src="{{ $person['info']['user_avatar'] }}">
				<img src="{{ $person['info']['user_avatar'] }}" class='normal-user-img'>
				<p class='inf-user-name'>
					{{ $person['info']['user_nickname'] }}
				</p>
			</div>
			<div class="top-line"></div>
			@foreach($person['list'] as $song)
			<article>
				<section class='user-outer'>
					{{--<img class='user-img' src="{{ $song['song_cover'] }}">--}}
					<div class="user-inf">
						<p class='song-name'>
							{{ $song['song_name'] }}—{{ $song['song_singer'] }}
						</p>				
						<p class='time'>
							@datetime($song['created_at'])
						</p>
					</div>
				</section>
				<section class='detail-outer'>
					<p class='to-who'>
						To: {{ $song['receiver'] }}
					</p>
					<p class='detail-content'>
						{{ $song['message'] }}
					</p>
				</section>
			</article>
			@endforeach
		</section>
	</section>
	<a href="/jukebox/dedication" class='request-music'>
		点歌
	</a>
	<script src='{{ URL::asset('jukebox/js/tools/sea.js') }}'></script>
	<script>
		seajs.use('/jukebox/js/index/main.js');
	</script>
</body>
</html>