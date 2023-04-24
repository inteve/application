<?php

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


$presenterTester = createPresenterTest([
	__DIR__ . '/PresenterMapping.neon',
]);


test(function () use ($presenterTester) {
	$presenterFactory = $presenterTester->getPresenterFactory();

	Assert::type(\Nette\Application\PresenterFactory::class, $presenterFactory);

	Assert::same('NotMappedPresenter', $presenterFactory->formatPresenterClass('NotMapped'));
	Assert::same('NotMappedModule\FooPresenter', $presenterFactory->formatPresenterClass('NotMapped:Foo'));

	Assert::same('Vendor\Project\Presenters\FooPresenter', $presenterFactory->formatPresenterClass('MappingTest:Foo'));
	Assert::same('Vendor\Project\FooBar\Presenters\BarPresenter', $presenterFactory->formatPresenterClass('MappingTest:FooBar:Bar'));
});
