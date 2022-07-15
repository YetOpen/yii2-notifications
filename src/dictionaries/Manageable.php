<?php

namespace  webzop\notifications\dictionaries;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * Manageable implementato con i dizionari
 * http://en.rmcreative.ru/blog/moving-constants-into-dictionaries/
 */
abstract class Manageable
{
    const MANGEABLE = 1;
    const UNMANGEABLE = 0;

    public static function all()
    {
        return [
            self::MANGEABLE => Yii::t('modules/notifications', 'Manageable'),
            self::UNMANGEABLE => Yii::t('modules/notifications', 'Unmanageable'),
        ];
    }

    public static function get($var)
    {
        $all = self::all();
        return ArrayHelper::getValue($all, $var);
    }
}
