<?php

namespace webzop\notifications\model;

/**
 * This is the ActiveQuery class for [[UserChannels]].
 *
 * @see UserChannels
 */
class UserChannelsQuery extends \yii\db\ActiveQuery
{
    public function active()
    {
        return $this->andWhere(['active' => true]);
    }

    /**
     * {@inheritdoc}
     * @return UserChannels[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return UserChannels|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
