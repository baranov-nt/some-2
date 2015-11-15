<?php
/**
 * Created by PhpStorm.
 * User: phpNT
 * Date: 05.10.2015
 * Time: 18:16
 */

/* @var $this yii\web\View */
/* @var $model common\models\Profile */
/* @var $form ActiveForm */

/* @var $route string */
/* @var $id string */
/* @var $attribute string */

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;
use yii\helpers\Url;
use yii\bootstrap\Modal;
use yii\widgets\MaskedInput;
?>
<?php
Pjax::begin([
    'id' => $attribute,
    'enablePushState' => false,
]);
?>
<div class="form-group">

    <b><?= $model->getAttributeLabel($attribute); ?> : </b>
    <?php
    if($model->$attribute):
        echo Html::encode($model->$attribute);
    else:
        echo '<span style="color: #c40000;"> (нет данных) </span>';
    endif;
    ?>
    <?= Html::a(
        $model->$attribute ? 'Изменить' : 'Добавить',
        ['#'],
        [
            'class' => $model->$attribute ? 'btn btn-xs btn-primary pull-right' : 'btn btn-xs btn-success pull-right',
            'data-toggle' => 'modal',
            'data-target' => '#modal_'.$attribute
        ]);
    ?>
</div>
<?php
$js = <<< JS
    $('#form_$attribute').on('beforeSubmit', function(e) {
        var modal_form = $("#modal_$attribute");
        modal_form.modal("hide");
        $(".modal-backdrop").remove();
});
JS;
$this->registerJs($js);

Modal::begin([
    'size' => 'modal-sm',
    'header' => '<h2>'.$model->getAttributeLabel($attribute).'</h2>',
    'options' => [
        'id' => 'modal_'.$attribute
    ],
]);
?>
<?php $form = ActiveForm::begin(
    [
        'action' => Url::to([$route]),
        'options' => [
            'id' => 'form_'.$attribute,
            'data-pjax' => true
        ]
    ]); ?>

<?php
if($attribute == 'phone'):
    ?>
    <?= $form->field($model, $attribute)->label(false)->widget(MaskedInput::className(),[
    'name' => 'phone',
    'mask' => '7 (999) 999-9999',
    'options' => [
        'placeholder' => '7 (___) ___-____',
        'class' => 'form-control'
    ]]) ?>
    <?php
else:
    ?>
    <?= $form->field($model, $attribute)->label(false) ?>
    <?php
endif;
?>
<div class="form-group">
    <?php
    echo Html::submitButton($model->$attribute ? 'Изменить' : 'Добавить',
        [
            'class' => $model->$attribute ? 'btn btn-primary' : 'btn btn-success',

        ]) ?>
</div>
<?php ActiveForm::end(); ?>
<?php
Modal::end();
?>
<?php
Pjax::end();
?>

