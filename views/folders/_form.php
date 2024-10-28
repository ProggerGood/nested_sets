<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\Folders;

/** @var yii\web\View $this */
/** @var app\models\Folders $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="folders-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class='form-group field-attribute-parentId'>
        <?= Html::label('Parent', 'parent', ['class' => 'control-label']);?>
        <?= Html::dropdownList(
            'Folders[parent_id]',
            $model->parentId,
            Folders::getTree($model->id),
            ['prompt' => 'No Parent (saved as root)', 'class' => 'form-control']
        );?>
    </div>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
