# CHANGELOG

## 0.3.2 November 9, 2021

- Fixed caching of channels in worker;
- Commands controller no longer extends [yii\queue\cli\Command] since its methods are no longer required and keeping it
  caused some errors;
- Fixed an errore that prevented the worker to set correctly the language of the notifications.

## 0.3.1 November 9, 2021

- Adds worker so that notifications can be sent at another time;
- The email channel now passes a parameter 'language' to the view of the mailer.

## 0.2 October 11, 2021

- Refactoring and improvements from the original repository

## 0.1 October 11, 2017

- First release
