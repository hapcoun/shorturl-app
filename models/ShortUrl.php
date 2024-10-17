<?php
namespace app\models;

use yii\db\ActiveRecord;
use yii\db\ActiveQuery;
use Yii;

/**
 * @property int $id
 * @property int $user_id
 * @property string $original_url
 * @property string $short_code
 * @property string $created_at
 *
 * @property ShortUrlStatistics[] $shortUrlStatistics
 */
class ShortUrl extends ActiveRecord
{
    public static function tableName(): string {
        return '{{%short_urls}}';
    }

    public function rules(): array {
        return [
            [['original_url'], 'required'],
            [['original_url'], 'url'],
        ];
    }

    /**
     * Gets query for [[ShortUrlStatistics]].
     *
     * @return ActiveQuery
     */
    public function getShortUrlStatistics(): ActiveQuery {
        return $this->hasMany(ShortUrlStatistics::class, ['id' => 'short_url_id']);
    }

    public function getTotalClicks()
    {
        return ShortUrlStatistics::find()
            ->where(['short_url_id' => $this->id])
            ->sum('clicks') ?? 0;
    }

    public function beforeSave($insert): bool {
        if (parent::beforeSave($insert)) {
            if ($insert) {
                $this->user_id = Yii::$app->user->id;
            }

            return true;
        }

        return false;
    }
}