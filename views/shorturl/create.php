<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'URL Shortener';
?>

<div class="site-index">
    <h1><?= Html::encode($this->title) ?></h1>
    <div class="body-content">
        <div class="row">
            <div class="col-lg-6">
                <h2>Enter your URL</h2>
                <?php $form = ActiveForm::begin(); ?>
                <?= $form->field($model, 'original_url')->textInput(['maxlength' => true]) ?>
                <div class="form-group">
                    <?= Html::submitButton('Shorten', ['class' => 'btn btn-primary']) ?>
                </div>
                <?php ActiveForm::end(); ?>
                <?php if (isset($shortUrl)): ?>
                    <h2>Your Short URL</h2>
                    <p><?= Html::a($shortUrl, $shortUrl, ['target' => '_blank']) ?></p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
