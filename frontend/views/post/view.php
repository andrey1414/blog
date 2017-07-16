<?php

/* @var $this yii\web\View */

use common\widgets\PostWidget;
use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'Post';

echo PostWidget::widget([
    'id' => $post->id,
    'message' => $post->message,
    'date' => $post->date,
    'title' => $post->title,
]);

?>

<div class="comments">
    <?php
    if ($comments === null) {
        echo '<div class="comments_title">' . Yii::t('app', 'Not found') . '</div>';
    } else {
        echo '<div class="comments_title">' . Yii::t('app', 'Comments') . ': </div>';
        foreach ($comments as $oneComment) {
            ?>
            <div class="comment">
                <div class="comment_username">Author: <?= $oneComment->user->username; ?></div>
                <div class="comment_date">Date: <?= $oneComment->date; ?></div>
                <div class="comment_message">Comment: <?= $oneComment->message; ?></div>
                <div class="comment_delete"><a href="<?= Url::to(['comment/delete', 'id' => $oneComment->id]); ?>">Удалить</a></div>
            </div>
            <?php
        }
    }
    ?>
</div>


<?php if (!Yii::$app->user->isGuest) { ?>
<div class="comment_form_title"><?= Yii::t('app', 'Your comment') ?>:</div>
<div class="comment_form">
    <?= $form = Html::beginForm('/comment/create', 'POST', ['class' => 'comment_form']); ?>
    <div><?= $comment->getAttributeLabel('message') ?>:</div>
    <?= Html::activeTextarea($comment, 'message', ['class' => 'input_textarea input_message']); ?>
    <?= Html::activeHiddenInput($comment, 'postId', ['value' => $post->id]); ?>
    <div>
        <?= Html::submitButton(Yii::t('app', 'Submit'), ['class' => 'input_btn']) ?>
    </div>
    <?= Html::endForm(); ?>
</div>
<?php } else { ?>
    <div class="login_for_comment"><a href="<?= Url::to(['site/login']);?>"><?= Yii::t('app', 'Please login to leave a comment.') ?></a></div>

<?php } ?>


<script>
    //TODO исправить т.к. Yii сам должен добавлять Csrf в куки.
 var phpVars = {
     _csrf: '<?= Yii::$app->request->getCsrfToken() ?>'
 };
</script>
