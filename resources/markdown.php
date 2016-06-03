<?php
// custom Twig function for converting markdown to HTML
$markdown = new Twig_SimpleFunction('markdown', function ($n) {
	$parser = new MarkdownExtra;
	return $parser->transform($n);
});
$container->twig->addFunction($markdown);