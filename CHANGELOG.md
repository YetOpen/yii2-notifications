# CHANGELOG

## 0.3.6 Jul 18, 2024

- CS fixes
- Fixed notification not sent even if the user was confirmed
- Improved attachments handling to email notifications.

## 0.3.5 Jul 21, 2023

- Added parameter to prevent sending of notifications to non-active users (blocked or not confirmed). 

## 0.3.4 May 17, 2023

- Fixed default alias in `Module::$attachmentsPath`
- Fixed name of method after library update in `WebChannel`

## 0.3.3 April 3, 2023

- Fixed receiver on the channel when multiple notifications are sent;
- Fixed `NotificationsAsset::$sourcePath`.
- Fixed typos.
 
## 0.3.2 May 19, 2022

- Fixed caching of channels in worker;
- Commands controller no longer extends [yii\queue\cli\Command] since its methods are no longer required and keeping it
  caused some errors;
- Fixed an error that prevented the worker to set correctly the language of the notifications.

## 0.3.1 November 9, 2021

- Adds worker so that notifications can be sent at another time;
- The email channel now passes a parameter 'language' to the view of the mailer.

## 0.2 October 11, 2021

- Refactoring and improvements from the original repository

## 0.1 October 11, 2017

- First release
