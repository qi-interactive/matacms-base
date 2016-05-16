MATA CMS Base
==========================================

![MATA CMS Module](https://s3-eu-west-1.amazonaws.com/qi-interactive/assets/mata-cms/gear-mata-logo%402x.png)


MATA CMS Base application is the core of MATA CMS built on top of MATA Framework.


Installation
------------

- Add the application using composer:

```json
"matacms/matacms-base": "~1.0.0"
```

Changelog
---------

## 1.1.5.4-alpha, May 16, 2016

- Bug fix

## 1.1.5.3-alpha, May 13, 2016

- Added DateHelper for setting datetimes into user local time
- Added magic getter for setting user local time  and setter for setting local time to UTC

## 1.1.5.2-alpha, April 28, 2016

- Updated composer.json for matacms/matacms-simple-theme version

## 1.1.5.1-alpha, April 27, 2016

- Added trigger of ActiveQuery::EVENT_BEFORE_PREPARE_STATEMENT_FOR_SEARCH in actionIndex (matacms\controllers\module\Controller) (event consumed by matacms-language module)

## 1.1.5-alpha, November 30, 2015

- Bug fix for \matacms\console\Application

## 1.1.4.9-alpha, November 8, 2015

- Changed usage of deprecated Vimeo API to oEmbed

## 1.1.4.8-alpha, October 13, 2015

- Updated multi lingual rearranging

## 1.1.4.7-alpha, October 13, 2015

- Added support for multi lingual rearranging

## 1.1.4.6-alpha, October 12, 2015

- Updated selector for history behavior
- Added language selector modal for module list view page (enabled only if model has LanguageBehavior)

## 1.1.4.5-alpha, October 7, 2015

- Added support to overwrite views rendered via renderAjax

## 1.1.4.4-alpha, September 22, 2015

- Updated multiselect field type in ActiveField

## 1.1.4.3-alpha, September 16, 2015

- Added \matacms\console\Application with option to disable specific modules from bootstrapping

## 1.1.4.2-alpha, September 16, 2015

- Removed non-essential modules (matacms-content-block, matacms-post, matacms-carousel, matacms-person)
- Removed XML declarations

## 1.1.4.1-alpha, August 23, 2015

- Updated module acccess control

## 1.1.4-alpha, August 21, 2015

- Added error page
- Added module acccess control
- Added support for multiple selection in dropDownList
- Added support for multiple notifications with same key
- Small updates

## 1.1.3.3-alpha, July 20, 2015
- Composer.json updates

## 1.1.3.2-alpha, July 20, 2015
- Composer.json updates

## 1.1.3.1-alpha, July 20, 2015
- Composer.json updates

## 1.1.3-alpha, July 20, 2015
- Added dependency on matacms/matacms-user

## 1.1.2.2-alpha, July 6, 2015
- Added customized ActiveField hint with tooltip

## 1.1.2.1-alpha, June 11, 2015
- Bugfix for NotificationFilter

## 1.1.2-alpha
- Security fixes -- matacms\controllers\module\Controller was overwritting behaviors
- Added dependency on mata/mata-framework : ~1.1.0-alpha

## 1.1.1-alpha, May 28, 2015
- Bug fixes

## 1.1.0-alpha, May 28, 2015
- Moved a lot of logic from [[matacms\db\ActiveQuery]] to [[mata\db\ActiveQuery]]
- Updated query for visual representation


## 1.0.9-alpha, May 27, 2015

- EVENT_BEFORE_PREPARE_STATEMENT is fired only once (Bug fix)

## 1.0.8-alpha, May 26, 2015

- matacms\controllers\module\Controller::EVENT_MODEL_DELETED now fires after delete() on a model
- NotificationFilter handled errors when removing entities
- Updated Rearrangeable view with environment statuses


## 1.0.7-alpha, May 26, 2015

- Added EVENT_BEFORE_PREPARE_STATEMENT, allowing condition injection
- ActiveQuery extends \mata\db\ActiveQuery
- Added dependency on mata/mata-framework : ~1.0.3-alpha which introduced \mata\db\ActiveQuery

## 1.0.6-alpha, May 22, 2015

- Updated Rearrangeable view with environment status.

## 1.0.5-alpha, May 21, 2015

- Updated View.php

## 1.0.4-alpha, May 19, 2015

- Added JS trigger for 'mediaChanged' event for FineUploader on upload complete.

## 1.0.3-alpha, May 18, 2015

- Updated pointer to mata-tag module.

## 1.0.2-alpha, May 18, 2015

- Updated pointer to mata-rbac module.

## 1.0.1-alpha, May 18, 2015

- Moved CalendarInterface from /base into /interfaces

## 1.0.0-alpha, May 18, 2015

- Initial release.
