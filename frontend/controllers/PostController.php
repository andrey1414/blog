<?php
namespace frontend\controllers;

use Yii;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\Post;
use yii\web\NotFoundHttpException;
use \yii\web\Response;
use frontend\models\Comment;



/**
 * Site controller
 */
class PostController extends Controller
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
                    'delete' => ['post'],
                ],
            ],
        ];
    }


    //TODO права доступа
    public function actionCreate()
    {

        $post = new Post;

        if ($post->load(Yii::$app->request->post()) && $post->save()) {
            return $this->redirect(['/']);
        }
        return $this->render('create', [
            'model' => $post,
        ]);

    }

    //TODO права доступа, тип запрос в verbs
    public function actionDelete($id)
    {

        \Yii::$app->response->format = Response::FORMAT_JSON;

        $post = Post::findOne(intval($id));

        if($post && $post->delete())
            return ['status' => 200];
        return ['status' => 400];
    }


    public function actionView($id)
    {
        $post = Post::findOne($id);
        if($post === null)
            throw new NotFoundHttpException('Not found');

        $comment = new Comment;

        $comments = $post->getComments()->with('user')->orderBy('id DESC')->all();

        return $this->render('view', [
            'post' => $post,
            'comment' => $comment,
            'comments' => $comments
        ]);

    }
}
