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

## 1.1.2-alpha
- Security fixes -- matacms\controllers\module\Controller was overwritting behaviors

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
