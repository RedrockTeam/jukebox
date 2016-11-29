<!DOCTYPE html>
<html lang="ch-zn">
<head>
	<meta charset="UTF-8">
	<meta name="csrf-token" content="{{ csrf_token() }}">
	<title>重邮点歌台</title>
	<script>
	!function(a,b){function c(){var b=f.getBoundingClientRect().width;b/i>540&&(b=540*i);var c=b/10;f.style.fontSize=c+"px",k.rem=a.rem=c}var d,e=a.document,f=e.documentElement,g=e.querySelector('meta[name="viewport"]'),h=e.querySelector('meta[name="flexible"]'),i=0,j=0,k=b.flexible||(b.flexible={});if(g){console.warn("将根据已有的meta标签来设置缩放比例");var l=g.getAttribute("content").match(/initial\-scale=([\d\.]+)/);l&&(j=parseFloat(l[1]),i=parseInt(1/j))}else if(h){var m=h.getAttribute("content");if(m){var n=m.match(/initial\-dpr=([\d\.]+)/),o=m.match(/maximum\-dpr=([\d\.]+)/);n&&(i=parseFloat(n[1]),j=parseFloat((1/i).toFixed(2))),o&&(i=parseFloat(o[1]),j=parseFloat((1/i).toFixed(2)))}}if(!i&&!j){var p=(a.navigator.appVersion.match(/android/gi),a.navigator.appVersion.match(/iphone/gi)),q=a.devicePixelRatio;i=p?q>=3&&(!i||i>=3)?3:q>=2&&(!i||i>=2)?2:1:1,j=1/i}if(f.setAttribute("data-dpr",i),!g)if(g=e.createElement("meta"),g.setAttribute("name","viewport"),g.setAttribute("content","initial-scale="+j+", maximum-scale="+j+", minimum-scale="+j+", user-scalable=no"),f.firstElementChild)f.firstElementChild.appendChild(g);else{var r=e.createElement("div");r.appendChild(g),e.write(r.innerHTML)}a.addEventListener("resize",function(){clearTimeout(d),d=setTimeout(c,300)},!1),a.addEventListener("pageshow",function(a){a.persisted&&(clearTimeout(d),d=setTimeout(c,300))},!1),"complete"===e.readyState?e.body.style.fontSize=12*i+"px":e.addEventListener("DOMContentLoaded",function(){e.body.style.fontSize=12*i+"px"},!1),c(),k.dpr=a.dpr=i,k.refreshRem=c,k.rem2px=function(a){var b=parseFloat(a)*this.rem;return"string"==typeof a&&a.match(/rem$/)&&(b+="px"),b},k.px2rem=function(a){var b=parseFloat(a)/this.rem;return"string"==typeof a&&a.match(/px$/)&&(b+="rem"),b}}(window,window.lib||(window.lib={}));
	</script>
	<link rel="stylesheet" type="text/css" href="{{URL::asset('jukebox/css/detail_song.css')}}">
</head>
<body>
	<audio id="audio" style="width:350px;" controls="controls" autoplay="autoplay" src="http://enroll.lot.cat{{ $describe['song_reference'] }}" type="audio/mpeg"></audio>
	<header>
		<section class='user-outer'>
			<div class="user-inf">
				<p class='song-name'>
					{{ $describe['song_name'] }}—{{ $describe['song_singer'] }}
					<span class="song-size">@filesize($describe['song_size'])</span>
				</p>
				<img class='user-img' src="{{ $describe['sheets']['avatar'] }}">
				<span class='user-name'>by {{ $describe['sheets']['receiver'] }}</span>
			</div>
			<img id='player-controler' src="{{ URL::asset('jukebox/images/play.png') }}">
		</section>
	</header>
	<section class='request-music'>
		<div class='request-button'>
			点这首歌
			<img class='icon-z' src="{{ URL::asset('jukebox/images/icon-z.png') }}">
		</div>
		<div class="request-detail">
			<form role="form">
				<div class="to-who">
					<div class="to-who-trd">
						想送的人
					</div>
					<input maxlength='15' class='to-who-input' type='text'>
				</div>
				<div class="want-say">
					<div class="to-who-say">
						想说的话
					</div>
					<textarea maxlength='150' class='say-input'></textarea>
				</div>
				<div class="send">
					<div class="no-user">
						<div class="no-user-border"></div>
						匿名发送
					</div>
					<p class='error'>
						信息未完成
					</p>
					<div class="submit-outer">			
						<input type='submit' value='发送' class="submit"/>
					</div>
				</div>
			</form>
		</div>
	</section>
	<section class='talk'>
		@foreach($comments as $comment)
		<article>
			<section class='user-outer'>
				<div class="user-inf">
					<img class='user-img' src="{{ $comment['sheets']['user_avatar'] }}">
					<!-- 用户名 -->
					<span class='user-name'>by {{ $comment['name'] }}</span>
				</div>
				<p class='time'>
					<!-- 时间 -->
                    @datetime($comment['created_at'])
				</p>
			</section>
			<section class='detail-outer'>
				<p class='to-who'>
					To: {{ $comment['receiver'] }}
					<!-- 歌曲点给谁 -->
				</p>
				<p class='detail-content'>
					{{ $comment['message'] }}
					<!-- 回复内容 -->
				</p>

				<!-- data-id 代表该条评论ID -->
				<!-- data-listen 1代表 能点赞class加上have-add-listen     0 代表不能点赞 class保持原样 -->
				<div class="want-listen" data-id='{{ $comment['song_id'] }}' data-listen='1'>
					想听
					<span>
						{{ $comment['likes'] }}
					</span>
				</div>
			</section>
		</article>
		@endforeach
	</section>
	<script src='{{ URL::asset('jukebox/js/tools/sea.js') }}'></script>
	<script>
		seajs.use('/jukebox/js/detail_song/main.js');
	</script>
</body>
</body>
</html>