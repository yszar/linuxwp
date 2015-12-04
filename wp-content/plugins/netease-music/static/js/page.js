var mnPlayLrc = $('.nmplayer-lrc'),
	nmPlayTime = $(".nmplayer-time"),
	nmPlayProsess = $(".nmplayer-prosess"),
	nmPlayBar = $('.nmplaybar'),
	nmPlayButton = $("#nmplayer-button"),
	nmPlayTitle = $('.nmplayer-title'),
	formatTime = function(b) {
		if (!isFinite(b) || 0 > b) b = "--:--";
		else {
			var d = Math.floor(b / 60);
			b = Math.floor(b) % 60;
			b = (10 > d ? "0" + d : d) + ":" + (10 > b ? "0" + b : b)
		}
		return b
	},
	myPlaylist = new jPlayerPlaylist({
		jPlayer: "#jquery_jplayer_N",
		cssSelectorAncestor: "#jp_container_N"
	}, [], {
		playlistOptions: {
			autoPlay: false
		},
		timeupdate: function(c) {
			var b;
			b = c.jPlayer.status.currentTime;
			b = formatTime(b);
			var current = myPlaylist.current,
				playlist = myPlaylist.playlist,
				lrc = playlist[current].lrc;
			l = parseInt(c.jPlayer.status.currentTime);
			lrc[l] != undefined && (mnPlayLrc.html(lrc[l]));
			nmPlayTime.text(b);
			nmPlayProsess.width(c.jPlayer.status.currentPercentAbsolute + "%")
		},
		supplied: "mp3",
		swfPath: nm_ajax_url.swfPath,
		smoothPlayBar: true,
		keyEnabled: true,
		audioFullScreen: true
	});
jQuery(document).on("click", ".album--nice", function() {
	var a = $(this),
		nmPlayImage = a.data('thumbnail'),
		nmPLayCount = a.children().find('.play-count'),
		nmPlayListContainer = $(".audio-jplayer"),
		nmPLayListItem = $(".album--nice"),
		nmPlayTwoRowClass = nm_ajax_url.tworow ? ' tworow' : '',
		nmPlayListSongslist = '<div class="audio-jplayer album--wrapper"><div class="album--title">' + a.data('tooltip') + '</div><div class="content-with-thumb"><ul id="sbplaylist" class="nmplaylist' + nmPlayTwoRowClass + '"></ul><img class="play-thumb" src="' + nmPlayImage + '"></div></div>';
	itemId = a.data("id");
	nmPlayBar.addClass('appear');
	if (a.hasClass('is-active')) {
		if (a.hasClass('paused')) {
			a.removeClass('paused');
			myPlaylist.play();
			nmPlayListContainer.slideDown();
			nmPlayButton.removeClass('paused')
		} else {
			a.addClass('paused');
			myPlaylist.pause();
			nmPlayListContainer.slideUp();
			nmPlayButton.addClass('paused')
		}
		return false
	} else {
		nmPLayCount.html( parseInt(nmPLayCount.html()) + 1);
		nmPlayButton.removeClass('paused');
		nmPlayListContainer.remove();
		a.parent().after(nmPlayListSongslist);
		nmPLayListItem.removeClass('is-active');
		a.addClass('is-active');
		jQuery.ajax({
			type: "post",
			dataType: "json",
			jsonp: "callback",
			url: nm_ajax_url.ajax_url,
			data: {
				action: "nmjson",
				id: itemId
			},
			async: !1,
			success: function(b) {
				if (200 == b.msg) {
					var listT = '';
					b = b.song;
					songs = b.songs;
					jQuery.each(songs, function(i, item) {
						listT += '<li id="track' + item.id + '" class="sb-list">' + item.title + ' - ' + item.artist + '<span class="song-time">' + formatTime(item.duration / 1000) + '</span></li>'
					});
					jQuery('.nmplaylist').html(listT);
					myPlaylist.setPlaylist(songs);
					myPlaylist.play(0)
				}
			}
		})
	}
});
jQuery(document).on($.jPlayer.event.play, function() {
	var trackid = myPlaylist.playlist[myPlaylist.current].id,
		$track = $("#track" + trackid);
	$(".sb-list").data("status", "ready");
	$track.data("status", "play");
	jQuery(".sb-list").removeClass('nmplaylist-current');
	$track.addClass('nmplaylist-current');
	mnPlayLrc.empty();
	nmPlayTitle.html(myPlaylist.playlist[myPlaylist.current].title + ' - ' + myPlaylist.playlist[myPlaylist.current].artist)
});
jQuery(document).on("click", "#nmplayer-next", function() {
	myPlaylist.next()
});
jQuery(document).on("click", "#nmplayer-prev", function() {
	myPlaylist.previous()
});
jQuery(document).on("click", "#nmplayer-button", function() {
	var $this = $(this);
	if ($this.hasClass('paused')) {
		myPlaylist.play();
		$(this).removeClass('paused')
	} else {
		myPlaylist.pause();
		$(this).addClass('paused')
	}
});
jQuery(document).on("click", ".sb-list", function() {
	var a = $(this).index();
	"play" == $(this).data("status") ? ($(this).data("status", "pause"), myPlaylist.pause(), nmPlayButton.addClass('paused')) : myPlaylist.current == a ? ($(this).data("status", "play"), myPlaylist.play(), nmPlayButton.removeClass('paused')) : ($(this).data("status", "play"), myPlaylist.play(a), nmPlayButton.removeClass('paused'))
});
jQuery(document).on("click", ".nm-loadmore", function() {
	var $this = $(this),
		paged = $this.data("paged"),
		max = $this.data("max"),
		ajax_data = {
		action: "get_music",
		max: max,
		paged: paged
	};
	jQuery('.music-page-navi').remove();
	jQuery.ajax({
		url: nm_ajax_url.ajax_url,
		type: "POST",
		data: ajax_data,
		dataType: "json",
		success: function(data) {
			if (data.status == 200) {
				jQuery("#nm-wrapper").append(data.data);
				if (data.nav) {
					jQuery("#nm-wrapper").after(data.nav)
				}
			}
		}
	})
});