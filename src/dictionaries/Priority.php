<?php

namespace  webzop\notifications\dictionaries;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * Priority implementato con i dizionari
 * http://en.rmcreative.ru/blog/moving-constants-into-dictionaries/
 */
abstract class Priority
{
    const LOW = 0;
    const MEDIUM = 1;
    const HIGH = 2;

    public static function all()
    {
        return [
            self::LOW => Yii::t('modules/notifications', 'Low'),
            self::MEDIUM => Yii::t('modules/notifications', 'Medium'),
            self::HIGH => Yii::t('modules/notifications', 'High'),
        ];
    }

    public static function get($var)
    {
        $all = self::all();
        return ArrayHelper::getValue($all, $var);
    }
}
