<?php

declare(strict_types=1);

use Inteve\Application\Tests\Libs\PresenterTester;

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/libs/ApplicationTester.php';
require __DIR__ . '/libs/PresenterTester.php';

Tester\Environment::setup();


function test(callable $cb): void
{
	$cb();
}


/**
 * @param  list<string> $configFiles
 */
function createApplicationTester(array $configFiles = []): \Inteve\Application\Tests\Libs\ApplicationTester
{
	$defaultFiles = [
		__DIR__ . '/bootstrap.neon',
	];

	return \Inteve\Application\Tests\Libs\ApplicationTester::create(
		__DIR__ . '/temp/' . \Nette\Utils\Random::generate(10),
		array_merge($defaultFiles, $configFiles)
	);
}


/**
 * @param  list<string> $configFiles
 */
function createPresenterTester(array $configFiles = []): PresenterTester
{
	$applicationTester = createApplicationTester($configFiles);
	return $applicationTester->createPresenterTester();
}
