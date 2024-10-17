<?php

/** @var yii\web\View $this */
/** @var yii\bootstrap5\ActiveForm $form */
/** @var SignupForm $signupForm */

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\forms\SignupForm;

$this->title = 'Signup';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-signup">
    <h1><?= Html::encode($this->title) ?></h1>

    <p>Пожалуйста, заполните следующие поля для регистрации:</p>

    <div class="signup-form">
        <?php $form = ActiveForm::begin([
            'id' => 'signup-form',
            'fieldConfig' => [
                'template' => "{label}\n{input}\n{error}",
                'labelOptions' => ['class' => 'col-lg-1 col-form-label mr-lg-3'],
                'inputOptions' => ['class' => 'col-lg-3 form-control'],
                'errorOptions' => ['class' => 'col-lg-7 invalid-feedback'],
            ],
        ]); ?>

        <?= $form->field($signupForm, 'username')->textInput(['autofocus' => true]) ?>

        <?= $form->field($signupForm, 'email')->textInput() ?>

        <?= $form->field($signupForm, 'password')->passwordInput() ?>

        <?= $form->field($signupForm, 'confirm_password')->passwordInput() ?>

        <div class="form-group">
            <?= Html::submitButton('Submit', ['class' => 'btn btn-primary', 'name' => 'signup-button']) ?>
        </div>

        <?php ActiveForm::end(); ?>
    </div>
</div>