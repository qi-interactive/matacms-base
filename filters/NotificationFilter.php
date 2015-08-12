<?php

/**
 * @link http://www.matacms.com/
 * @copyright Copyright (c) 2015 Qi Interactive Limited
 * @license http://www.matacms.com/license/
 */

namespace matacms\filters;

use yii\base\Behavior;
use matacms\controllers\module\Controller;
use frontend\widgets\Alert;
use yii\base\Event;
use yii\web\View;
use matacms\base\MessageEvent;

class NotificationFilter extends Behavior {

	public function events() {
		return [Controller::EVENT_BEFORE_ACTION => 'beforeAction'];
	}

	public function beforeAction($event) {

		$this->observe($event, Controller::EVENT_MODEL_CREATED, "%s <strong>%s</strong> has been <strong>created</strong>.");
		$this->observe($event, Controller::EVENT_MODEL_UPDATED, "%s <strong>%s</strong> has been <strong>updated</strong>.");
		$this->observe($event, Controller::EVENT_MODEL_DELETED, "%s <strong>%s</strong> has been <strong>deleted</strong>.");

		Event::on(View::className(), View::EVENT_BEGIN_BODY, function ($event) {
			foreach(\Yii::$app->session->getAllFlashes(true) as $key => $message) {
				if(is_array($message)) {
					foreach($message as $el) {
						echo $this->renderNotification($event, $key, $el);
					}
				}
				else {
					echo $this->renderNotification($event, $key, $message);
				}

			}
		});
	}

	private function renderNotification($event, $key, $message)
	{
		return $event->sender->context->renderPartial("@matacms/views/site/_notification", [
			"message" => $message,
			"level" => $key
			]);
	}

	private function observe($actionEvent, $eventToObserve, $messageFormat) {
		$actionEvent->action->controller->on($eventToObserve, function(MessageEvent $event) {

			$message = $event->getMessage();

			if (!is_string($message) && $event->name == Controller::EVENT_MODEL_DELETED &&
				$message->hasErrors()) {
				$event->setLevel(MessageEvent::LEVEL_WARNING);
				$event->data = $message->getTopError();
			}

			if ($message instanceof \matacms\interfaces\HumanInterface) {
				\Yii::$app->getSession()->addFlash($event->getLevel(), sprintf($event->data, $message->getModelLabel(), $message->getLabel()));
			} else if(is_string($message)) {
				\Yii::$app->getSession()->addFlash($event->getLevel(), $message);
			} else {
				throw new \yii\web\ServerErrorHttpException(sprintf("Cannot handle %s", get_class($message)));
			}

		}, $messageFormat);
	}
}
