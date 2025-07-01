<?php

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/libs/ApplicationTester.php';
require __DIR__ . '/libs/PresenterTester.php';

Tester\Environment::setup();


function test($cb)
{
	$cb();
}


function createApplicationTester(array $configFiles = [])
{
	$defaultFiles = [
		__DIR__ . '/bootstrap.neon',
	];

	return \Inteve\Application\Tests\Libs\ApplicationTester::create(
		__DIR__ . '/temp/' . \Nette\Utils\Random::generate(10),
		array_merge($defaultFiles, $configFiles)
	);
}


function createPresenterTester(array $configFiles = [])
{
	$applicationTester = createApplicationTester($configFiles);
	return $applicationTester->createPresenterTester();
}
