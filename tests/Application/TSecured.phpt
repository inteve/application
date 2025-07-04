<?php

declare(strict_types=1);

use Tester\Assert;

require __DIR__ . '/../bootstrap.php';

class SecuredTestPresenter extends \Nette\Application\UI\Presenter
{
	use \Inteve\Application\TSecured;


	protected function startup()
	{
		parent::startup();
		$this->checkUserRelogin('signIn', 'Log in please', 'Logout by inactivity.');
	}


	public function actionDefault(): void
	{
		$this->sendJson(['success' => TRUE]);
	}
}


test(function () {
	$applicationTester = createApplicationTester();
	$presenterTester = $applicationTester->createPresenterTester();
	$request = $presenterTester->createRequest('SecuredTest');

	$response = $presenterTester->run($request);
	Assert::type(\Nette\Application\Responses\RedirectResponse::class, $response);
	assert($response instanceof \Nette\Application\Responses\RedirectResponse);

	Assert::contains('presenter=SecuredTest', $response->getUrl());
	Assert::contains('action=signIn', $response->getUrl());
	$applicationTester->terminate();
});


test(function () {
	$applicationTester = createApplicationTester();
	$applicationTester->loginUser(new \Nette\Security\SimpleIdentity(1));

	$presenterTester = $applicationTester->createPresenterTester();
	$request = $presenterTester->createRequest('SecuredTest');

	$response = $presenterTester->run($request);
	Assert::type(\Nette\Application\Responses\JsonResponse::class, $response);
	assert($response instanceof \Nette\Application\Responses\JsonResponse);

	Assert::same(['success' => TRUE], $response->getPayload());
	$applicationTester->terminate();
});


test(function () {
	$applicationTester = createApplicationTester();
	$user = $applicationTester->loginUser(new \Nette\Security\SimpleIdentity(1));
	$applicationTester->expireUser($user);

	$presenterTester = $applicationTester->createPresenterTester();

	$request = $presenterTester->createRequest('SecuredTest');

	$response = $presenterTester->run($request);
	Assert::type(\Nette\Application\Responses\RedirectResponse::class, $response);
	assert($response instanceof \Nette\Application\Responses\RedirectResponse);

	Assert::contains('presenter=SecuredTest', $response->getUrl());
	Assert::contains('action=signIn', $response->getUrl());
	$applicationTester->terminate();
});
