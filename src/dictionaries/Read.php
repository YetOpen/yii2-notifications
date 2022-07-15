<?php

namespace  webzop\notifications\dictionaries;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * Read implementato con i dizionari
 * http://en.rmcreative.ru/blog/moving-constants-into-dictionaries/
 */
abstract class Read
{
    const READ = 1;
    const UNREAD = 0;

    public static function all()
    {
        return [
            self::READ => Yii::t('modules/notifications', 'Read'),
            self::UNREAD => Yii::t('modules/notifications', 'Unread'),
        ];
    }

    public static function get($var)
    {
        $all = self::all();
        return ArrayHelper::getValue($all, $var);
    }
}
