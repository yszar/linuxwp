/*global Qiniu */
/*global plupload */
/*global FileProgress */
/*global hljs */

(function($) {
               uptokenobj = $.ajax({url:uptokenurl,async:false});
               uptoken = eval('(' + uptokenobj.responseText + ')').uptoken;
               console.log(uptoken);
           var uploader = Qiniu.uploader({
               runtimes: 'html5,flash,html4',
               browse_button: 'pickfiles',
               container: 'qiniu_tuchuang_post',     
               drop_element: 'qiniu_tuchuang_post',
               max_file_size: '100mb',
               flash_swf_url: 'js/Moxie.swf',
               dragdrop: true,
               chunk_size: '4mb',
               uptoken: uptoken,
               // uptoken_url: 'http://localhost/qiniu/uptoken/',
               domain: host,
               // downtoken_url: '/downtoken',
               // unique_names: true,
               save_key: savekey,
               // x_vars: {
               //     'id': '1234',
               //     'time': function(up, file) {
               //         var time = (new Date()).getTime();
               //         // do something with 'time'
               //         return time;
               //     },
               // },
               auto_start: true,
               init: {

                   'FilesAdded': function(up, files) {
                       // $('table').show();
                       // $('#success').hide();
                       // plupload.each(files, function(file) {
                       //     var progress = new FileProgress(file, 'fsUploadProgress');
                       //     progress.setStatus("等待...");
                       //     console.log('上传中');
       
                       // });
                   },
                   'BeforeUpload': function(up, file) {
                       // var progress = new FileProgress(file, 'fsUploadProgress');
                       // var chunk_size = plupload.parseSize(this.getOption('chunk_size'));
                       // if (up.runtime === 'html5' && chunk_size) {
                       //     progress.setChunkProgess(chunk_size);
                       // }
                   },
                   'UploadProgress': function(up, file) {
                       // var progress = new FileProgress(file, 'fsUploadProgress');
                       // var chunk_size = plupload.parseSize(this.getOption('chunk_size'));
                       // progress.setProgress(file.percent + "%", up.total.bytesPerSec, chunk_size);
                       span = $('#spantxt').text(file.percent + "%");
       
                   },
                   'UploadComplete': function() {
                       console.log('上传完成');
                       span = $('#spantxt').text('上传完成');       
                   },
                   'FileUploaded': function(up, file, info) {
                       // var progress = new FileProgress(file, 'fsUploadProgress');
                       // progress.setComplete(up, info);
                       var title = $('#title').val();
                       console.log(title);
                       var obj = eval('(' + info + ')');
                       var key = obj.key;
                       qiniuurl = host + '/' + key;
                       if (imgurl == true) {
                            var img = '<a href="' + qiniuurl + '"><img src="' + qiniuurl
                                    + '" alt="' + title
                                    + '" title="' + title
                                    + '">';    
                       } else {
                             var img = '<img src="' + qiniuurl
                                    + '" alt="' 
                                    + title
                                    + '" title="'
                                    + title
                                    + '">';   
                       }
                       console.log(img);
                       tinyMCE.activeEditor.execCommand('mceInsertContent', 0, img);
                       console.log(key+'设置完成');
       
                   },
                   'Error': function(up, err, errTip) {
                       console.log(err.file.name + '上传错误:'+ errTip);
                       console.log('错误代码：' + err.status);
                       var status = err.status;
                       if (status == 614) {
                            var title = $('#title').val();
                            console.log(title);
                            var key = err.file.name;
                            qiniuurl = host + '/' + key;
                            if (imgurl == true) {
                                 var img = '<a href="' + qiniuurl + '"><img src="' + qiniuurl
                                         + '" alt="' + title
                                         + '" title="' + title
                                         + '">';    
                            } else {
                                  var img = '<img src="' + qiniuurl
                                         + '" alt="' 
                                         + title
                                         + '" title="'
                                         + title
                                         + '">';   
                            }
                            console.log(img);
                            tinyMCE.activeEditor.execCommand('mceInsertContent', 0, img);
                       };
                       span = $('#spantxt').text('上传完成'); 
                   }
       
               }
           });
})(jQuery);
