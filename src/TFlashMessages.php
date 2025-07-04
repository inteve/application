<?php

	declare(strict_types=1);

	namespace Inteve\Application;


	trait TFlashMessages
	{
		/**
		 * @param  string $message
		 * @return \stdClass
		 */
		public function flashSuccess($message)
		{
			return $this->flashMessage($message, 'success');
		}


		/**
		 * @param  string $message
		 * @return \stdClass
		 */
		public function flashError($message)
		{
			return $this->flashMessage($message, 'error');
		}


		/**
		 * @param  string $message
		 * @return \stdClass
		 */
		public function flashWarning($message)
		{
			return $this->flashMessage($message, 'warning');
		}
	}
