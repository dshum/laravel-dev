{{ $title }}:<br>
@if ($readonly)
<span class="richtext">{{ $value }}</span>
@else
{{ Form::textarea($name, null, array('id' => $name, 'class' => 'richtext', 'tinymce' => 'true')) }}
<script type="text/javascript">
tinyMCE.init({
	mode: 'none',
	language: 'ru',
	theme: 'advanced',
	plugins: 'inlinepopups,style,table,advhr,advimage,advlink,media,searchreplace,print,paste,fullscreen,visualchars,xhtmlxtras',

	theme_advanced_buttons1: 'newdocument,search,replace,print,|,cut,copy,paste,pastetext,pasteword,|,undo,redo,|,link,unlink,anchor,image,media,|,advhr,charmap,|,tablecontrols',
	theme_advanced_buttons2: 'styleprops,attribs,removeformat,|,styleselect,formatselect,|,bold,italic,underline,strikethrough,sub,sup,|,justifyleft,justifycenter,justifyright,justifyfull,|,bullist,numlist,|,outdent,indent,|,visualchars,code',
	theme_advanced_buttons3: '',
	theme_advanced_toolbar_location: 'top',
	theme_advanced_toolbar_align: 'left',
	theme_advanced_path_location: 'bottom',

	content_css: '/LT/css/richtext.css',
	file_browser_callback: 'fileManagerCallback',
	theme_advanced_resize_horizontal: false,
	theme_advanced_resizing: false,
	forced_root_block: false,
	apply_source_formatting: true,
	nonbreaking_force_tab: true,
	button_tile_map: true,
	entity_encoding: 'raw',
	verify_html: false,
	convert_urls: false
});
tinyMCE.execCommand('mceAddControl', false, '{{ $name }}');
</script>
@endif