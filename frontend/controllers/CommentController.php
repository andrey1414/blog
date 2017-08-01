<?php

namespace frontend\controllers;

use Yii;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use frontend\models\Post;
use yii\web\NotFoundHttpException;
use \yii\web\Response;
use frontend\models\Comment;


/**
 * Site controller
 */
class CommentController extends Controller
{

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['create', 'delete'],
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['create', 'delete'],
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => \yii\filters\VerbFilter::className(),
                'actions' => [
                    'create' => ['post'],
                    'delete' => ['post'],
                ],
            ],
        ];
    }



    //TODO права доступа
    public function actionCreate()
    {
        \Yii::$app->response->format = Response::FORMAT_JSON;

        $comment = new Comment;
        if ($comment->load(Yii::$app->request->post())) {
            $comment->userId = Yii::$app->user->id;

            if ($comment->save())
                return ['status' => 200];
        }
        return ['status' => 400];
    }


    //TODO права доступа, тип запрос в verbs
    public function actionDelete($id)
    {

        \Yii::$app->response->format = Response::FORMAT_JSON;

        $comment = Comment::findOne(intval($id));

        if ($comment && $comment->delete())
            return ['status' => 200];
        return ['status' => 400];
    }


    //TODO права доступа
    public function actionUpdate($id)
    {
        $comment = Comment::findOne(intval($id));

        if ($comment->load(Yii::$app->request->post()) && $comment->save()) {
            return $this->redirect(['/']);
        } else {
            return $this->render('create', [
                'model' => $comment,
            ]);
        }
    }


}
