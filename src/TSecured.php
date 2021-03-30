<?php

	namespace Inteve\Application;

	use Nette\Application\ForbiddenRequestException;


	/**
	 * Example:
	 * startup() {
	 *   $this->checkUserRelogin('signIn', 'Přihlašte se prosím', 'Byl jste odhlášen z důvodu neaktivity');
	 * }
	 */
	trait TSecured
	{
		/**
		 * @param  string
		 * @param  string|NULL
		 * @param  string|NULL
		 * @return void
		 */
		protected function checkUserRelogin($presenter, $message = NULL, $inactivityMessage = NULL)
		{
			if ($this->isNeedUserRelogin()) {
				if ($this->isLoggedOutByInactivity() && $inactivityMessage !== NULL) {
					$this->flashMessage($inactivityMessage);

				} elseif ($message !== NULL) {
					$this->flashMessage($message);
				}
				$this->redirect($presenter, ['backlink' => $this->storeRequest()]);
			}
		}


		/**
		 * @return bool
		 * @throws ForbiddenRequestException  in AJAX mode
		 */
		protected function isNeedUserRelogin()
		{
			if (!$this->user->isLoggedIn()) {
				if ($this->isAjax()) {
					throw new ForbiddenRequestException('User is not logged in.');
				}

				return TRUE;
			}
			return FALSE;
		}


		/**
		 * @return bool
		 */
		protected function isLoggedOutByInactivity()
		{
			return $this->getUser()->getLogoutReason() === \Nette\Http\UserStorage::INACTIVITY;
		}
	}
