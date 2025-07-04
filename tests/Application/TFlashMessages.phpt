<?php

declare(strict_types=1);

use Tester\Assert;

require __DIR__ . '/../bootstrap.php';

class FlashMessagesTestPresenter extends \Nette\Application\UI\Presenter
{
	use \Inteve\Application\TFlashMessages;


	public function actionDefault(): void
	{
		$messages = [];
		$messages[] = $this->flashMessage('message standard');
		$messages[] = $this->flashMessage('message standard error', 'error');

		$messages[] = $this->flashSuccess('message success');
		$messages[] = $this->flashError('message error');
		$messages[] = $this->flashWarning('message warning');

		$this->sendJson($messages);
	}
}


test(function () {
	$presenterTester = createPresenterTester();
	$request = $presenterTester->createRequest('FlashMessagesTest');

	$response = $presenterTester->run($request);
	Assert::type(\Nette\Application\Responses\JsonResponse::class, $response);
	assert($response instanceof \Nette\Application\Responses\JsonResponse);

	Assert::equal([
		(object) [
			'message' => 'message standard',
			'type' => 'info',
		],
		(object) [
			'message' => 'message standard error',
			'type' => 'error',
		],
		(object) [
			'message' => 'message success',
			'type' => 'success',
		],
		(object) [
			'message' => 'message error',
			'type' => 'error',
		],
		(object) [
			'message' => 'message warning',
			'type' => 'warning',
		],
	], $response->getPayload());
});
