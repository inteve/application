<?php

	namespace Inteve\Application\Tests\Libs;

	use Nette;


	class ApplicationTester
	{
		/** @var Nette\DI\Container */
		private $container;


		public function __construct(Nette\DI\Container $container)
		{
			$this->container = $container;
		}


		/**
		 * @return PresenterTester
		 */
		public function createPresenterTester()
		{
			return new PresenterTester($this->container);
		}


		/**
		 * @return Nette\Security\User
		 */
		public function loginUser(Nette\Security\IIdentity $identity)
		{
			$user = $this->container->getByType(Nette\Security\User::class);
			$user->login($identity);
			return $user;
		}


		/**
		 * @return void
		 */
		public function expireUser(Nette\Security\User $user)
		{
			$userStorage = $user->getStorage();

			if ($userStorage instanceof Nette\Bridges\SecurityHttp\SessionStorage) {
				$currentTime = new \DateTimeImmutable('now', new \DateTimeZone('UTC'));
				$namespace = $userStorage->getNamespace();

				// change authTime & expireTime to past
				$session = $this->container->getByType(Nette\Http\Session::class);
				$sessionSection = $session->getSection('Nette.Http.UserStorage/' . $namespace);

				$sessionSection->authTime = (int) $currentTime->sub(new \DateInterval('P7D'))->format('U');
				$sessionSection->expireTime = $sessionSection->authTime + 5;
				$sessionSection->expireDelta = 5;

				// refresh session & user
				$userStorage->setNamespace($namespace . '-' . Nette\Utils\Random::generate(10));
				$userStorage->setNamespace($namespace);
				$user->refreshStorage();

			} elseif ($userStorage instanceof Nette\Http\UserStorage) { // DEPRECATED: for Nette ^2.4
				$currentTime = new \DateTimeImmutable('now', new \DateTimeZone('UTC'));
				$namespace = $userStorage->getNamespace();

				// change authTime & expireTime to past
				$session = $this->container->getByType(Nette\Http\Session::class);
				$sessionSection = $session->getSection('Nette.Http.UserStorage/' . $namespace);

				$sessionSection->authTime = (int) $currentTime->sub(new \DateInterval('P7D'))->format('U');
				$sessionSection->expireTime = $sessionSection->authTime + 5;
				$sessionSection->expireDelta = 5;

				// refresh session
				$userStorage->setNamespace($namespace . '-' . Nette\Utils\Random::generate(10));
				$userStorage->setNamespace($namespace);

			} else {
				throw new \RuntimeException('Not implemented for storage ' . get_class($userStorage));
			}
		}


		/**
		 * @param  string $tempDirectory
		 * @param  string[] $configFiles
		 * @return self
		 */
		public static function create(
			$tempDirectory,
			array $configFiles
		)
		{
			$configurator = new Nette\Configurator;

			$configurator->setTempDirectory($tempDirectory);

			foreach ($configFiles as $configFile) {
				$configurator->addConfig($configFile);
			}

			$container = $configurator->createContainer();
			return new self($container);
		}
	}
