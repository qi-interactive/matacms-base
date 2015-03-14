<?php 

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

	/**
	 * @param ActionEvent $event
	 */
	public function beforeAction($event) {
		
		$this->observe($event, Controller::EVENT_MODEL_CREATED, "%s has been <strong>created</strong>.");
		$this->observe($event, Controller::EVENT_MODEL_UPDATED, "%s has been <strong>updated</strong>.");
		$this->observe($event, Controller::EVENT_MODEL_DELETED, "%s has been <strong>deleted</strong>.");

		Event::on(View::className(), View::EVENT_BEGIN_BODY, function ($event) {
			foreach(\Yii::$app->session->getAllFlashes(true) as $key => $message) {
				echo $event->sender->context->render("@matacms/views/site/_notification", [
					"message" => $message,
					"level" => $key
					]);
			}
		});
	}

	private function observe($actionEvent, $eventToObserve, $messageFormat) {
		$actionEvent->action->controller->on($eventToObserve, function(\matacms\base\MessageEvent $event) {
			\Yii::$app->getSession()->setFlash($event->getLevel(), sprintf($event->data, $event->getMessage()->getLabel()));
		}, $messageFormat);
	}
}