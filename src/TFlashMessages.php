<?php

	namespace Inteve\Application;


	trait TFlashMessages
	{
		/**
		 * @param  string
		 * @return \stdClass
		 */
		public function flashSuccess($message)
		{
			return $this->flashMessage($message, 'success');
		}


		/**
		 * @param  string
		 * @return \stdClass
		 */
		public function flashError($message)
		{
			return $this->flashMessage($message, 'error');
		}


		/**
		 * @param  string
		 * @return \stdClass
		 */
		public function flashWarning($message)
		{
			return $this->flashMessage($message, 'warning');
		}
	}
