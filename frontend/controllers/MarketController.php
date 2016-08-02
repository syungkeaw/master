<?php

namespace frontend\controllers;

use Yii;
use common\models\ShopItem;
use common\models\ShopItemSearch;
use common\models\Item;
use common\models\Shop;
use common\models\Feedback;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;

/**
 * ShopItemController implements the CRUD actions for ShopItem model.
 */
class MarketController extends Controller
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
                'only' => ['create', 'update', 'delete', 'feedback'],
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['create', 'update', 'delete', 'feedback'],
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        return $this->redirect(['eden']);
    }

    public function actionEden()
    {
        $server = 1;
        return $this->renderMarket($server);       
    }

    public function actionThor()
    {
        $server = 2;
        return $this->renderMarket($server);
    }

    public function actionLoki()
    {
        $server = 3;
        return $this->renderMarket($server);
    }

    public function renderMarket($server)
    {
        $searchModel = new ShopItemSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->query->andFilterWhere(['shop.server' => $server]);
        $dataProvider->query->andFilterWhere(['shop.status' => 10]);
        $dataProvider->query->andFilterWhere(['shop_item.status' => 10]);
        $items = Item::find()->all();

        $shopItem = ShopItem::find()->asArray()->all();
        $option = [];
        $option = array_merge($option, ArrayHelper::getColumn($shopItem, 'card_1'));
        $option = array_merge($option, ArrayHelper::getColumn($shopItem, 'card_2'));
        $option = array_merge($option, ArrayHelper::getColumn($shopItem, 'card_3'));
        $option = array_merge($option, ArrayHelper::getColumn($shopItem, 'card_4'));
        $option = array_filter($option);
        array_push($option, '994', '995', '996', '997');
        $option_item = Item::findAll(['source_id' => $option]);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'items' => $items,
            'option_item' => $option_item,
            'server' => Shop::$server[$server],
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
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionFeedback($id, $feedback_id)
    {
        $feedback = Feedback::findOne(['shop_item_id' => $id, 'feedback_by' => Yii::$app->user->identity->id, 'feedback_id' => $feedback_id]);
        if(empty($feedback)){
            $feedback = new Feedback();
            $feedback->shop_item_id = $id;
            $feedback->feedback_by = Yii::$app->user->identity->id;
            $feedback->feedback_id = $feedback_id;
            $feedback->save();
        }

        $feedback = [1 => Yii::t('app', 'Dislike'), 2 => Yii::t('app', 'Like')];
        Yii::$app->getSession()->setFlash('success', Yii::t('app', 'Successful, You have been given a {feedback_type} already.', ['feedback_type' => $feedback[$feedback_id]]));

        return $this->redirect(Yii::$app->request->referrer);
    }

    public function actionDetail($id)
    {
        $this->layout = 'detail';
        return $this->render('detail', ['model' => $this->findModel($id)]);
    }
}
