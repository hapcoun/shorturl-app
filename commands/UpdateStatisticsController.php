<?php

namespace commands;

use yii\console\Controller;
use app\components\ClickStatistics;

class UpdateStatisticsController extends Controller {
    public function actionSaveClicks() {
        while (true) {
            $clickStatistics = new ClickStatistics();
            $clickStatistics->saveClicks();
            sleep(10);
        }
    }
}