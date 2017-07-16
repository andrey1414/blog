<?php

namespace common\widgets;

use Yii;
use yii\base\Widget;
use yii\helpers\Url;



class PostWidget extends Widget
{
    public $id;
    public $title;
    public $message;
    public $date;

    public function init()
    {
        parent::init();
    }

    public function run()
    {
        ?>
        <div class="post">
            <a class="post_title" href="<?= Url::to(['post/view', 'id' => $this->id]);?>">
                <?= $this->title; ?>
            </a>
            <div><?= $this->message; ?></div>
            <div class="post_date"><?= $this->date; ?></div>
            <div class="post_delete"><a href="<?= Url::to(['post/delete', 'id' => $this->id]);?>">Удалить</a></div>
        </div>
        <?php
    }
}