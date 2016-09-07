<?php

/**
 * @link http://www.matacms.com/
 * @copyright Copyright (c) 2015 Qi Interactive Limited
 * @license http://www.matacms.com/license/
 */

namespace matacms\console;

use mata\modulemenu\models\Module as ModuleModel;
use mata\helpers\MataModuleHelper;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use yii\base\BootstrapInterface;
use Yii;

class Application extends \yii\console\Application {

    public $disabledModulesBootstraps = [];

    protected function bootstrap()
    {
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

            if ($component instanceof BootstrapInterface) {
                Yii::trace("Bootstrap with " . get_class($component) . '::bootstrap()', __METHOD__);
                $component->bootstrap($this);
            } else {
                Yii::trace("Bootstrap with " . get_class($component), __METHOD__);
            }
        }
    }


}
