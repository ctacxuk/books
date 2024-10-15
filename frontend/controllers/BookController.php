<?php

namespace frontend\controllers;

use frontend\dtos\BookDto;
use frontend\dtos\SubscribeDto;
use frontend\models\AuthorSubscriber;
use frontend\models\Book;
use frontend\models\SubscribeForm;
use frontend\services\BookService;
use Yii;
use yii\base\Event;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * BookController implements the CRUD actions for Book model.
 */
class BookController extends Controller
{
    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                'access' => [
                    'class' => AccessControl::class,
                    'rules' => [
                        [
                            'actions' => ['index', 'view', 'subscribe'],
                            'allow' => true,
                        ],
                    ],
                ],
                'verbs' => [
                    'class' => VerbFilter::className(),
                    'actions' => [
                        'delete' => ['POST'],
                    ],
                ],
            ]
        );
    }

    /**
     * Lists all Book models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Book::find(),
            /*
            'pagination' => [
                'pageSize' => 50
            ],
            'sort' => [
                'defaultOrder' => [
                    'id' => SORT_DESC,
                ]
            ],
            */
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Book model.
     * @param int $id ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Book model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $service = Yii::createObject(BookService::class);
        $model = new Book();

        if ($this->request->isPost) {
            $model->load($this->request->post());
            $model->newAuthors = explode(',', $model->newAuthors);
            if (!$model->validate()) {
                return $this->render('create', [
                    'model' => $model,
                ]);
            }
            $transaction = Yii::$app->db->beginTransaction();
            try {
                $id = $service->createBook(BookDto::fromActiveRecord($model), $model->newAuthors);
                $transaction->commit();
                return $this->redirect(['view', 'id' => $id]);
            } catch (\Throwable $e) {
                $transaction->rollBack();
                throw $e;
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Book model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $service = Yii::createObject(BookService::class);
        $model = $this->findModel($id);
        $model->newAuthors = implode(',', ArrayHelper::getColumn($model->authors, 'name'));


        if ($this->request->isPost) {

            $model->load($this->request->post());
            $model->newAuthors = explode(',', $model->newAuthors);
            if (!$model->validate()) {
                return $this->render('update', [
                    'model' => $model,
                ]);
            }

            $transaction = Yii::$app->db->beginTransaction();
            try {
                $service->updateBook(BookDto::fromActiveRecord($model), $model->newAuthors);
                $transaction->commit();
                return $this->redirect(['view', 'id' => $model->id]);
            } catch (\Throwable $e) {
                $transaction->rollBack();
                throw $e;
            }

        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    public function actionSubscribe($authorId)
    {
        $service = Yii::createObject(BookService::class);
        $model = new AuthorSubscriber();
        $model->author_id = $authorId;

        if ($this->request->isPost) {
            $model->load($this->request->post());
            if (!$model->validate()) {
                return $this->render('subscribe', [
                    'model' => $model,
                ]);
            }


            $dto = new SubscribeDto(authorId: $authorId, phone: $model->phone);
            $service->subscribeBook($dto);

            return $this->redirect(['index']);
        }

        return $this->render('subscribe', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Book model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Book model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Book the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Book::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
