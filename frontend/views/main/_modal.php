<?php
/**
 * Created by PhpStorm.
 * User: phpNT
 * Date: 05.10.2015
 * Time: 11:52
 */

/* @var $this yii\web\View */
/* @var $model common\models\Profile */
/* @var $form ActiveForm */
/* @var $attribute string */

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;
use yii\helpers\Url;
use yii\bootstrap\Modal;
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
        echo $model->$attribute;
    else:
        echo '<span style="color: #c40000;"> (нет данных) </span>';
    endif;
    ?>
    <?= Html::a(
        $model->$attribute ? 'Изменить' : 'Добавить',
        Url::to(['main/update-profile', 'attribute' => $attribute]),
        ['class' => $model->$attribute ? 'btn btn-xs btn-primary pull-right' : 'btn btn-xs btn-success pull-right']); ?>
</div>
<?php
$js = <<< JS
    var modal = $("#modal_$attribute");
    modal.modal("show");
JS;
$this->registerJs($js);
$js = <<< JS
    $('#form_$attribute').on('beforeSubmit', function(e) {
        var modal_form = $("#modal_$attribute");
        modal_form.modal("hide");
        $(".modal-backdrop").remove();
});
JS;
$this->registerJs($js);

Modal::begin([
    'header' => '<h2>'.$model->getAttributeLabel($attribute).'</h2>',
    'options' => [
        'id' => 'modal_'.$attribute
    ]
]);
?>
<?php $form = ActiveForm::begin(
    [
        'action' => ['/main/profile'],
        'options' => [
            'id' => 'form_'.$attribute,
            'data-pjax' => true
        ]
    ]); ?>
<?= $form->field($model, $attribute) ?>
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
