<?php
/**
 * @var \HtmlElsMeta[] $metas
 * @var string $title
 * @var \HtmlElsMedia[] $media
 * @var
 */
?><!DOCTYPE html>
<html>
<head>

	<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
	{{#meta}}
	<meta name="{{name}}" content="{{content}}" />
	{{/meta}}

	<title>{{title}}</title>

	{{#css}}
	{{#onlyIf}}<!-- IF {{onlyIf}} -->{{/onlyIf}}
	<link rel="stylesheet" tpye="text/css" {{#media}}media={{media}}{{/media}} href="{{href}}" />
	{{#onlyIf}}<!-- IF {{onlyIf}} -->{{/onlyIf}}
	{{/css}}

	{{#scripts}}
	<script src="{{src}}" ></script>
	{{scripts}}

	{{#script}}
	<script>{{script}}</script>
	{{script}}

</head>
<body {{#cssId}}id="#{{cssId}}"{{/cssId}} class="{{#cssClasses}}.{{cssClass}} {{/cssClasses}}" >
	{{content}}
</body>
</html>
