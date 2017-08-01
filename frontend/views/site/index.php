<?php

use common\widgets\PostWidget;
use common\widgets\TagsWidget;

/* @var $this yii\web\View */

$this->title = 'My Yii Application';


foreach($posts as $post) {
    echo PostWidget::widget([
        'id' => $post->id,
        'message' => $post->message,
        'date' => $post->date,
        'title' => $post->title,
        'tags' => $post->tags
    ]);


    //echo TagsWidget::widget([
    //    'tags' => $post->tags,
    //]);

} ?>
