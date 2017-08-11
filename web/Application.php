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

    /**
     * Initializes extensions and executes bootstrap components.
     * This method is called by [[init()]] after the application has been fully configured.
     * If you override this method, make sure you also call the parent implementation.
     */
    protected function bootstrap()
    {
        $request = $this->getRequest();

	    \Yii::setAlias('@webroot', dirname($request->getScriptFile())  . DIRECTORY_SEPARATOR . "web");
	    \Yii::setAlias('@web', $request->getBaseUrl() . "/web");

        $file = Yii::getAlias('@vendor/yiisoft/extensions.php');
        $vendorExtensions = is_file($file) ? include($file) : [];

        $this->extensions = $this->extensions === null ? $vendorExtensions : array_merge($this->extensions, $vendorExtensions);

        foreach ($this->extensions as $extension) {
            if (!empty($extension['alias'])) {
                foreach ($extension['alias'] as $name => $path) {
                    Yii::setAlias($name, $path);
                }
            }
            if (isset($extension['bootstrap']) && !in_array($extension['bootstrap'], $this->disabledModulesBootstraps)) {
                $component = Yii::createObject($extension['bootstrap']);
                if ($component instanceof \yii\base\BootstrapInterface) {
                    Yii::trace("Bootstrap with " . get_class($component) . '::bootstrap()', __METHOD__);
                    $component->bootstrap($this);
                } else {
                    Yii::trace("Bootstrap with " . get_class($component), __METHOD__);
                }
            }
        }

        foreach ($this->bootstrap as $class) {
            $component = null;
            if (is_string($class)) {
                if ($this->has($class)) {
                    $component = $this->get($class);
                } elseif ($this->hasModule($class)) {
                    $component = $this->getModule($class);
                } elseif (strpos($class, '\\') === false) {
                    throw new InvalidConfigException("Unknown bootstrapping component ID: $class");
                }
            }
            if (!isset($component)) {
                $component = Yii::createObject($class);
            }

            if ($component instanceof \yii\base\BootstrapInterface) {
                Yii::trace("Bootstrap with " . get_class($component) . '::bootstrap()', __METHOD__);
                $component->bootstrap($this);
            } else {
                Yii::trace("Bootstrap with " . get_class($component), __METHOD__);
            }
        }

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
