CKEDITOR.editorConfig = function( config ) {
	config.language = 'zh-cn';
	config.width = '99%';
  config.height = 400;
	config.toolbar = 'FeiFeiCms';
	config.toolbar_FeiFeiCms =[
		['Source', 'CodeSnippet', 'Preview', '-', 'Cut', 'Copy', 'Paste', 'PasteText', '-', 'Table', 'Image', 'dcvideo', 'PageBreak', '-', 'PasteText', 'SpecialChar', 'Bold', 'Italic', 'TextColor', 'BGColor', '-', 'NumberedList', 'BulletedList', '-', 'Link', 'Unlink', '-', 'Blockquote', 'ShowBlocks'],
	];
	config.extraPlugins = 'dcvideo';
	config.image_previewText = ' '; 
	config.removeDialogTabs = 'image:Link;image:advanced;link:advanced;link:upload';//image:info;
	config.disallowedContent = 'img{width,height};img[width,height]';
	config.extraAllowedContent = 'a[data-dc]';
	//config.removeButtons = 'Underline,Subscript,Superscript';
	//config.format_tags = 'p;h1;h2;h3;pre';
	//config.enterMode = CKEDITOR.ENTER_BR;
	//config.shiftEnterMode = CKEDITOR.ENTER_P;
};