<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model frontend\models\Post */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="post-form">

    <?php $form = ActiveForm::begin(); ?>
    <?= $form->field($post, 'title')->textInput() ?>
    <?= $form->field($post, 'message')->textarea(['rows' => 6]) ?>
    <?= $form->field($post,'tagsList')->textInput() ?>
    <div class="form-group">
        <?= Html::submitButton($post->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $post->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>


    <?php ActiveForm::end(); ?>

</div>
