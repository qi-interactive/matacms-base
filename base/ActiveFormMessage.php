<?php

namespace matacms\base;

use matacms\base\MessageEvent;

class ActiveFormMessage extends MessageEvent {

	public $form;
	public $fieldIndex;
	public $model;

	public function __construct($form, $model, $fieldIndex) {
		$this->form = $form;
		$this->fieldIndex = $fieldIndex;
		$this->model = $model;
		parent::__construct($form->action);
	}

	public function getForm() {
		return $this->form;
	}

	public function getFieldIndex() {
		return $this->fieldIndex;
	}
}