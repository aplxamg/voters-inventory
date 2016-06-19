<?php
    use yii\helpers\Html;
    use yii\bootstrap\ActiveForm;
    $this->title = 'Login';
?>

<div id="login-wrapper">
    <div id="logo">
        <img src='/resources/common/logo2.PNG'>
    </div>
    <div id="content">
        <?php $form = ActiveForm::begin([
                    'id' => 'login-form',
                    'method'    => 'post',
                    'fieldConfig' => [
                            'template' => "<div class=\"control-group\">{input}</div>\n<div>{error}</div>"
                    ]]);
        ?>

        <?= $form->field($model, 'username')->textInput(array('placeholder'=>'Username')) ?>

        <?= $form->field($model, 'password')->passwordInput(array('placeholder'=>'Password')) ?>

        <?= Html::submitButton(Yii::t('app', 'Login'), ['class' => 'btn btn-primary pull-right', 'name' => 'login-button']) ?>

        <?php ActiveForm::end(); ?>
    </div>
</div>
