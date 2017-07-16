<?php

use common\widgets\PostWidget;

/* @var $this yii\web\View */

$this->title = 'My Yii Application';


foreach($posts as $post) {
    echo PostWidget::widget([
        'id' => $post->id,
        'message' => $post->message,
        'date' => $post->date,
        'title' => $post->title,
    ]);
} ?>
