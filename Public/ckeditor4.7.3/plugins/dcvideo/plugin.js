CKEDITOR.plugins.add('dcvideo', {
	init: function (editor) {
		var pluginName = 'dcvideo';
		editor.addCommand(pluginName, new CKEDITOR.dialogCommand(pluginName));
		editor.ui.addButton(pluginName,{
			label: '视频链接',
			command: pluginName,
			icon: this.path + 'images/video.png'
		});
		//CKEDITOR.dialog.add(pluginName, this.path + 'dialogs/dcvideo.js');
		CKEDITOR.dialog.add(pluginName, function(editor) {
			return {
				title: "网页视频链接",
				resizable: CKEDITOR.DIALOG_RESIZE_BOTH,
				minWidth: 400,
				minHeight: 100,
				contents: [{
					id: "video",
					title: "video",
					elements: [{
						id: "url",
						type: "text",
						label: "视频网址URL (Youku/Iqiyi/Letv/QQ)",
						validate: CKEDITOR.dialog.validate.notEmpty("视频播放地址不能为空。"),
						setup: function(editor) {
						},
						commit: function(editor) {
						}
					}]
				}],
				onLoad: function(){
					//this.setupContent(editor);
				},
				onOk: function() {
					//this.commitContent(editor);
					var imgsrc = CKEDITOR.plugins.getPath("dcvideo") + "images/play.png";
					editor.insertHtml('<a href="'+this.getValueOf("video","url")+'" target="_blank" data-dc="video"><img src="' + imgsrc + '"/></a>');
				}
			};
		});
	}
});