<?php

	namespace Inteve\Application\DI;

	use Nette\Application\IPresenterFactory;
	use Nette\Application\PresenterFactory;
	use Nette\DI\ContainerBuilder;


	class PresenterMapping
	{
		public function __construct()
		{
			throw new \Inteve\Application\StaticClassException('This is static class.');
		}


		/**
		 * @param  array<string, string> $mappings
		 * @return void
		 */
		public static function register(ContainerBuilder $builder, array $mappings)
		{
			$presenterFactoryName = $builder->getByType(IPresenterFactory::class);

			if ($presenterFactoryName !== NULL) {
				$definition = $builder->getDefinition($presenterFactoryName);

				if (!($definition instanceof \Nette\DI\Definitions\ServiceDefinition) && !(method_exists($definition, 'getFactory') && method_exists($definition, 'addSetup'))) {
					return;
				}

				$definitionFactory = $definition->getFactory();

				if ($definitionFactory !== NULL && $definitionFactory->entity === PresenterFactory::class) {
					$definition->addSetup('setMapping', [$mappings]);
				}
			}
		}
	}
