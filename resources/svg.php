<?php
// custom twig function to load collections from Cockpit
$svg = new Twig_SimpleFunction('svg', function ($n) {
	$svg = file_get_contents(__DIR__ . '/../../public_html/' . $n);
	return $svg;
});
$container->twig->addFunction($svg);