<?php
/**
 * @v ar \HtmlElsMeta[] $metas
 * @v ar string $title
 * @v ar \HtmlElsMedia[] $media
 * @var \PageModel $Model
 */
?><!DOCTYPE html>
<html>
<head>

	<meta http-equiv="content-type" content="text/html; charset=UTF-8" />

	<?php foreach ($Model->meta as $EachMeta): ?>
	<meta name="<?= $EachMeta['name'] ?>" content="<?= $EachMeta['content'] ?>" />
	<?php endforeach; ?>

	<title><?= $Model->title ?></title>

	<?php foreach ($Model->css as $eachCss): ?>
	<?= empty($eachCss['onlyIf']) ? '' : "<!--[if {$eachCss['onlyIf']}]--><?php" ?>
	<link rel="stylesheet" tpye="text/css" <?php if(!empty($eachCss['media'])): ?>media=<?= $eachCss['media'] ?><?php endif; ?> href="<?= $eachCss['href'] ?>" />
	<?= empty($eachCss['onlyIf']) ? '' : '?><!--[endif]--><?php' ?>
	<?php endforeach; ?>

	<?php foreach ($Model->scripts as $eachScript): ?>
	<script src="<?= $eachScript['src'] ?>" ></script>
	<?php endforeach; ?>

	<?php if (!empty($Model->script)): ?>
	<script>
		<?= $Model->script ?>
	</script>
	<?php endif; ?>

</head>
<body <?= empty($Model->cssId) ? '' : '?> id="#<?= $Model->cssId ?>"<?php' ; ?> class="n-container <?= /*implode(' ', $Model->cssClasses)*/'' ?>" >

<?= /** this will convert */
	implode("\n", $Model->Contents)
?>

</body>
</html>
