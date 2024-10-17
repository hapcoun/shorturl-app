<?php
use yii\helpers\Html;
use yii\widgets\LinkPager;

$this->title = 'Stats by ' . Html::encode($shortUrl->short_code);
?>

<div class="short-url-statistics">
    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Back', ['index'], ['class' => 'btn btn-primary']) ?>
    </p>

    <table class="table">
        <thead>
        <tr>
            <th>Clicked At</th>
            <th>Count of clicks</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($statistics as $stat): ?>
            <tr>
                <td><?= Html::encode($stat->date) ?></td>
                <td><?= Html::encode($stat->date_clicks) ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>

    <div class="pagination">
        <?= LinkPager::widget([
            'pagination' => $pagination,
        ]); ?>
    </div>
</div>