<?php

namespace  webzop\notifications\dictionaries;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * Managed implementato con i dizionari
 * http://en.rmcreative.ru/blog/moving-constants-into-dictionaries/
 */
abstract class Managed
{
    const MANAGED = 1;
    const UNMANAGED = 0;

    public static function all()
    {
        return [
            self::MANAGED => Yii::t('modules/notifications', 'Managed'),
            self::UNMANAGED => Yii::t('modules/notifications', 'Unmanaged'),
        ];
    }

    public static function get($var)
    {
        $all = self::all();
        return ArrayHelper::getValue($all, $var);
    }
}
