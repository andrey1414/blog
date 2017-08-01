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
use frontend\models\Tag;
use frontend\models\PostTag;


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
        $tag = null;
        $tagsIds = [];

        if ($post->load(Yii::$app->request->post()) && $post->validate()) {

            $transaction = Yii::$app->getDb()->beginTransaction();
            if ($post->save(false)) {
                //all tags or one tag
                $postTagsStr = $post['tagsList'];
                $tags = explode(',', $postTagsStr);
                if ($tags === null && $postTagsStr) {
                    $tags[] = $postTagsStr;
                }

                //TODO можно вынести отдельно.
                foreach ($tags as $tagName) {
                    $tagName = trim($tagName);

                    if ($tagName !== '') {
                        $tag = Tag::findOne(['name' => $tagName]);

                        if ($tag !== null) {
                            $tagsIds[] = $tag->id;
                        } else {
                            $tag = new Tag;
                            $tag->name = $tagName;
                            if ($tag->save()) {
                                $tagsIds[] = $tag->id;
                            } else {
                                $transaction->rollBack();
                                \Yii::$app->session->addFlash('error', 'Error when save tag');

                                return $this->renderCreateView(['post' => $post]);
                            }
                        }
                    }
                }

                foreach ($tagsIds as $tagId) {
                    if(!$this->addPostTag($post->id, $tagId)) {
                        $transaction->rollBack();
                        \Yii::$app->session->addFlash('error', 'Error when save id of post and tag.');
                    }
                }


                $transaction->commit();
                $this->redirect(['/']);


                //TODO translate.
                \Yii::$app->session->addFlash('success', 'Your message has been added.');
            } else {

                \Yii::$app->session->addFlash('error', 'Error when save post');
            }
        }
        return $this->renderCreateView(['post' => $post]);

    }

    //TODO права доступа, тип запрос в verbs
    public function actionDelete($id)
    {

        \Yii::$app->response->format = Response::FORMAT_JSON;

        $post = Post::findOne(intval($id));

        if ($post && $post->delete())
            return ['status' => 200];
        return ['status' => 400];
    }


    public function actionView($id)
    {
        $post = Post::findOne($id);
        if ($post === null)
            throw new NotFoundHttpException('Not found');

        $comment = new Comment;

        $comments = $post->getComments()
            ->with('user')
            ->orderBy('id DESC')
            ->all();

        return $this->render('view', [
            'post' => $post,
            'comment' => $comment,
            'comments' => $comments
        ]);

    }
    private function renderCreateView($renderArray)
    {
        return $this->render('create', $renderArray);

    }
    private function addPostTag($postId, $tagId)
    {
        $postTag = new PostTag;
        $postTag->postId = $postId;
        $postTag->tagId = $tagId;
        return $postTag->save();
    }
}
