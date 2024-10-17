<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\LinkPager;

$this->title = 'My short urls';
?>

<div class="short-url-index">
    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create New Short URL', ['app/create'], [
            'class' => 'btn btn-success'
        ]) ?>
    </p>

    <table class="table">
        <thead>
        <tr>
            <th>Short URL</th>
            <th>Total count clicks</th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($shortUrls as $shortUrl): ?>
            <tr>
                <td>
                    <a href="<?= Url::to(['shorturl/redirect', 'code' => $shortUrl->short_code]) ?>">
                        <?= Html::encode($shortUrl->short_code) ?>
                    </a>
                </td>
                <td><?= Html::encode($shortUrl->getTotalClicks()) ?></td>
                <td>
                    <?= Html::a('Show stats', ['shorturl/statistics', 'id' => $shortUrl->id], ['class' => 'btn btn-info']) ?>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>

    <div class="pagination-container">
        <?= LinkPager::widget([
            'pagination' => $pagination,
        ]) ?>
    </div>
</div>