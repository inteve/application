<?php

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


	public function actionDefault()
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

	Assert::contains('presenter=SecuredTest', $response->getUrl());
	Assert::contains('action=signIn', $response->getUrl());
	$applicationTester->terminate();
});


test(function () {
	$applicationTester = createApplicationTester();
	$applicationTester->loginUser(new \Nette\Security\Identity(1));

	$presenterTester = $applicationTester->createPresenterTester();
	$request = $presenterTester->createRequest('SecuredTest');

	$response = $presenterTester->run($request);
	Assert::type(\Nette\Application\Responses\JsonResponse::class, $response);

	Assert::same(['success' => TRUE], $response->getPayload());
	$applicationTester->terminate();
});


test(function () {
	if (!class_exists(\Nette\Security\SimpleIdentity::class)) {
		return;
	}

	$applicationTester = createApplicationTester();
	$applicationTester->loginUser(new \Nette\Security\SimpleIdentity(1));

	$presenterTester = $applicationTester->createPresenterTester();
	$request = $presenterTester->createRequest('SecuredTest');

	$response = $presenterTester->run($request);
	Assert::type(\Nette\Application\Responses\JsonResponse::class, $response);

	Assert::same(['success' => TRUE], $response->getPayload());
	$applicationTester->terminate();
});


test(function () {
	$applicationTester = createApplicationTester();
	$user = $applicationTester->loginUser(new \Nette\Security\Identity(1));
	$applicationTester->expireUser($user);

	$presenterTester = $applicationTester->createPresenterTester();

	$request = $presenterTester->createRequest('SecuredTest');

	$response = $presenterTester->run($request);
	Assert::type(\Nette\Application\Responses\RedirectResponse::class, $response);

	Assert::contains('presenter=SecuredTest', $response->getUrl());
	Assert::contains('action=signIn', $response->getUrl());
	$applicationTester->terminate();
});


test(function () {
	if (!class_exists(\Nette\Security\SimpleIdentity::class)) {
		return;
	}

	$applicationTester = createApplicationTester();
	$user = $applicationTester->loginUser(new \Nette\Security\SimpleIdentity(1));
	$applicationTester->expireUser($user);

	$presenterTester = $applicationTester->createPresenterTester();

	$request = $presenterTester->createRequest('SecuredTest');

	$response = $presenterTester->run($request);
	Assert::type(\Nette\Application\Responses\RedirectResponse::class, $response);

	Assert::contains('presenter=SecuredTest', $response->getUrl());
	Assert::contains('action=signIn', $response->getUrl());
	$applicationTester->terminate();
});
