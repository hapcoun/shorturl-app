<?php
namespace app\models;

use yii\db\ActiveRecord;

/**
 * @property int $id
 * @property int $short_url_id
 * @property int $clicks
 * @property string $clicked_at
 */
class ShortUrlStatistics extends ActiveRecord
{
    public $date;
    public $date_clicks;

    public static function tableName(): string {
        return '{{%short_url_statistics}}';
    }

    public function rules(): array {
        return [
            [['short_url_id', 'clicked_at'], 'required'],
            [['short_url_id', 'clicks'], 'integer'],
            [['clicked_at'], 'string'],
        ];
    }

    public static function getStatistics($shortUrlId): array {
        return self::find()
            ->select(['SUM(clicks) as total_clicks', 'created_at'])
            ->where(['short_url_id' => $shortUrlId])
            ->groupBy('date')
            ->orderBy('date ASC')
            ->all();
    }

    public static function getTotalClicks($shortUrlId)
    {
        return self::find()
            ->where(['short_url_id' => $shortUrlId])
            ->sum('clicks');
    }
}