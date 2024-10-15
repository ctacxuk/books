<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "author_subscribers".
 *
 * @property int $id
 * @property string|null $phone
 * @property int|null $author_id

 */
class AuthorSubscriber extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'author_subscribers';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['author_id'], 'integer'],
            ['phone', 'match', 'pattern' => '/^(\+7|7|8)*[0-9]{3}[0-9]{3}[0-9]{2}[0-9]{2}$/', 'message' => 'Номер введен неверно'],
            [['author_id'], 'exist', 'skipOnError' => true, 'targetClass' => Author::class, 'targetAttribute' => ['author_id' => 'id']],
            [['phone'], 'unique', 'targetAttribute' => ['phone', 'author_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'phone' => 'Phone',
            'author_id' => 'Author ID',
        ];
    }
}
