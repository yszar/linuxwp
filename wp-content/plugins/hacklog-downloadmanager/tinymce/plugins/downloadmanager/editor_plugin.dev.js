(function() {
	tinymce.PluginManager.requireLangPack('downloadmanager');
	tinymce.create('tinymce.plugins.DownloadManagerPlugin', {
		init : function(ed, url) {
			ed.addCommand('mceDownloadInsert', function() {
				ed.execCommand('mceInsertContent', 0, insertDownload('visual', ''));
			});
			ed.addButton('downloadmanager', {
				title : 'downloadmanager.insert_download',
				cmd : 'mceDownloadInsert',
				image : url + '/img/download.gif'
			});
			ed.onNodeChange.add(function(ed, cm, n) {
				cm.setActive('downloadmanager', n.nodeName == 'IMG');
			});
		},

		createControl : function(n, cm) {
			return null;
		},
		getInfo : function() {
			return {
				longname : 'Hacklog-DownloadManager',
				author : '荒野无灯and Lester Chan',
				authorurl : 'http://www.ihacklog.com',
				infourl : 'http://www.ihacklog.com/wordpress/plugins/wp-downloadmanager-hacklog-modifed-chinese-version.html',
				version : "1.00"
			};
		}
	});
	tinymce.PluginManager.add('downloadmanager', tinymce.plugins.DownloadManagerPlugin);
})();