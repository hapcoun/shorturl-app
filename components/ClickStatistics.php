<?php
namespace app\components;

use app\models\ShortUrlStatistics;
use yii\db\Exception;
use Yii;

class ClickStatistics
{
    private $redis;

    public function __construct()
    {
        $this->redis = Yii::$app->redis;
    }

    public function registerClick($shortUrlId)
    {
        $currentTimestamp = time();
        $key = AppConstants::SHORT_URL_CLICK_STATISTICS_KEY. ":{$shortUrlId}:{$currentTimestamp}";
        $this->redis->incr($key);
    }

    /**
     * @throws Exception
     */
    public function saveClicks()
    {
        $keys = $this->redis->keys(AppConstants::SHORT_URL_CLICK_STATISTICS_KEY. ':*');

        foreach ($keys as $key) {
            preg_match('/'. AppConstants::SHORT_URL_CLICK_STATISTICS_KEY . ':(\d+):(\d+)/', $key, $matches);
            $shortUrlId = $matches[1];
            $timestamp = $matches[2];
            $currentTimestamp = time();

            if ($currentTimestamp > $timestamp) {
                $clicks = $this->redis->get($key);
                $this->saveToDatabase($shortUrlId, $clicks, $timestamp);
                $this->redis->del($key);
            }
        }
    }

    /**
     * @throws Exception
     */
    private function saveToDatabase($shortUrlId, $clicks, $timestamp)
    {
        $clickedAt = date('Y-m-d H:i:s', $timestamp);
        $transaction = Yii::$app->db->beginTransaction();

        try {
            $existingStat = ShortUrlStatistics::find()
                ->where([
                    'short_url_id' => $shortUrlId,
                    'clicked_at' => $clickedAt,
                ])
                ->one();

            if ($existingStat) {
                $existingStat->clicks += 1;
                $existingStat->save(false);
            } else {
                $newStat = new ShortUrlStatistics();
                $newStat->short_url_id = $shortUrlId;
                $newStat->clicked_at = $clickedAt;
                $newStat->clicks = 1;
                $newStat->save();
            }

            $transaction->commit();
        } catch (\Exception $e) {
            $transaction->rollBack();
            Yii::error("Error saving click statistics: " . $e->getMessage());
        }
    }
}