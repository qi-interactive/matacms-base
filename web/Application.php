<?php

/**
 * @link http://www.matacms.com/
 * @copyright Copyright (c) 2015 Qi Interactive Limited
 * @license http://www.matacms.com/license/
 */

namespace matacms\web;

use mata\modulemenu\models\Module as ModuleModel;
use mata\helpers\MataModuleHelper;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use Yii;

class Application extends \mata\web\Application {

    public $disabledModulesBootstraps = [];

	public function preInit(&$config) {
		$this->addMataModules($config);
		parent::preInit($config);
	}

    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->state = self::STATE_INIT;
        $this->bootstrap();
    }

    protected function bootstrap()
    {

        $file = Yii::getAlias('@vendor/yiisoft/extensions.php');
        $vendorExtensions = is_file($file) ? include($file) : [];
        $this->extensions = $this->extensions === null ? $vendorExtensions : array_merge($this->extensions, $vendorExtensions);

        parent::bootstrap();

    }

    private function addMataModules(&$config) {

		$db = $config["components"]["db"];

		$db = new \yii\db\Connection([
	      	'dsn' => $db["dsn"],
	      	'username' => $db["username"],
	      	'password' => $db["password"],
  		]);

		$mataModules = $db->createCommand('SELECT * FROM matamodulemenu_module where Enabled = 1')->queryAll();

		$modulesDefinition = &$config["modules"];

		foreach ($mataModules as $moduleRecord) {
			$moduleClass = $moduleRecord["Location"] . "Module";
			$module = new $moduleClass(null);

			if ($module != null)
				$modulesDefinition[$moduleRecord["Id"]] = ArrayHelper::merge($module->getConfig(), json_decode($moduleRecord["Config"]), isset($modulesDefinition[$moduleRecord["Id"]]) ? $modulesDefinition[$moduleRecord["Id"]] : array());
		}
	}

}
