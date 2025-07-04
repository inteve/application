<?php

declare(strict_types=1);

use Tester\Assert;

require __DIR__ . '/../../bootstrap.php';


class PresenterMappingExtension extends \Nette\DI\CompilerExtension
{
	public function beforeCompile()
	{
		$builder = $this->getContainerBuilder();
		\Inteve\Application\DI\PresenterMapping::register($builder, [
			'MappingTest' => 'Vendor\Project\*\Presenters\*Presenter',
		]);
	}
}


test(function () {
	$presenterTester = createPresenterTester([
		__DIR__ . '/PresenterMapping.neon',
	]);
	$presenterFactory = $presenterTester->getPresenterFactory();

	Assert::type(\Nette\Application\PresenterFactory::class, $presenterFactory);
	assert($presenterFactory instanceof \Nette\Application\PresenterFactory);

	Assert::same('NotMappedPresenter', $presenterFactory->formatPresenterClass('NotMapped'));
	Assert::same('NotMappedModule\FooPresenter', $presenterFactory->formatPresenterClass('NotMapped:Foo'));

	Assert::same('Vendor\Project\Presenters\FooPresenter', $presenterFactory->formatPresenterClass('MappingTest:Foo'));
	Assert::same('Vendor\Project\FooBar\Presenters\BarPresenter', $presenterFactory->formatPresenterClass('MappingTest:FooBar:Bar'));
});
