<?php

namespace frontend\controllers;

use Yii;
use common\models\ShopItem;
use common\models\ShopItemSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\web\ForbiddenHttpException;

/**
 * ShopItemController implements the CRUD actions for ShopItem model.
 */
class ShopItemController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['index', 'update', 'delete', 'open', 'close'],
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    /**
     * Lists all ShopItem models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ShopItemSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single ShopItem model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new ShopItem model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new ShopItem();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing ShopItem model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing ShopItem model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    public function actionClose($id)
    {
        $model = $this->findModel($id);
        $model->status = 0;

        if($model->save()){
            Yii::$app->getSession()->setFlash('success', Yii::t('app', 'Success!, Your item has been stopped selling at the moment.'));
        }else{
            Yii::$app->getSession()->setFlash('danger', Yii::t('app', 'Fail!, Your item cannot be sell at the moment. Please try again.'));
        }

        return $this->redirect(Yii::$app->request->referrer);
    }

    public function actionOpen($id)
    {
        $model = $this->findModel($id);
        $model->status = 10;

        if($model->save()){
            Yii::$app->getSession()->setFlash('success', Yii::t('app', 'Success!, Your item has been selling at the moment.'));
        }else{
            Yii::$app->getSession()->setFlash('danger', Yii::t('app', 'Fail!, Your item cannot be stop to sell at the moment. Please try again.'));
        }

        return $this->redirect(Yii::$app->request->referrer);
    }
    /**
     * Finds the ShopItem model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ShopItem the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ShopItem::findOne($id)) !== null) {
            $this->authen($model);
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    protected function authen($model)
    {
        if($model->shop->created_by != Yii::$app->user->identity->id){
            throw new ForbiddenHttpException('You are not allowed to access this page');
        }
    }    
}
