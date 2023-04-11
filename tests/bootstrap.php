<?php

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/libs/PresenterTester.php';

Tester\Environment::setup();


function test($cb)
{
	$cb();
}


function createPresenterTest(array $configFiles = [])
{
	$defaultFiles = [
		__DIR__ . '/bootstrap.neon',
	];

	return \Inteve\Application\Tests\Libs\PresenterTester::create(
		__DIR__ . '/temp/',
		array_merge($defaultFiles, $configFiles)
	);
}
