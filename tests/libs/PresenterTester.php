<?php

	declare(strict_types=1);

	namespace Inteve\Application\Tests\Libs;

	use Nette;


	class PresenterTester
	{
		/** @var Nette\DI\Container */
		private $container;


		public function __construct(Nette\DI\Container $container)
		{
			$this->container = $container;
		}


		/**
		 * @return Nette\Application\Request
		 */
		public function createRequest($presenterName, $action = 'default')
		{
			$request = new Nette\Application\Request($presenterName);
			$request->setMethod('GET');
			$request->setParameters([
				Nette\Application\UI\Presenter::ACTION_KEY => $action,
			]);
			return $request;
		}


		/**
		 * @return Nette\Application\IResponse
		 */
		public function run(Nette\Application\Request $request)
		{
			$presenterFactory = $this->container->getByType(Nette\Application\IPresenterFactory::class);
			$presenter = $presenterFactory->createPresenter($request->getPresenterName());
			return $presenter->run($request);
		}


		/**
		 * @return Nette\Application\IPresenterFactory
		 */
		public function getPresenterFactory()
		{
			return $this->container->getByType(Nette\Application\IPresenterFactory::class);
		}
	}
