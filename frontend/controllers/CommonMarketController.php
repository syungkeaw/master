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
class CommonMarketController extends Controller
{
    public function getViewPath()
    {
        return Yii::getAlias('@frontend/views/market');
    }

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
        $this->handleLastServer();
        $searchModel = new ShopItemSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->query->andFilterWhere(['shop.server' => Yii::$app->request->get('server')]);
        $dataProvider->query->andFilterWhere(['shop.status' => 10]);
        $dataProvider->query->andFilterWhere(['shop_item.status' => 10]);
        $dataProvider->query->andWhere(['<', 'shop_item.delete_count', 10]);
        $dataProvider->query->andWhere(
            ['OR', 
                ['>', 'shop_item.created_at', (time()- 60 * 60 * 3)],
                ['AND', ['IS NOT', 'shop.created_by', null], ['>', 'shop_item.updated_at', (time()- 60 * 60 * 6)]],
            ]
        );

        $hideItems = json_decode(Yii::$app->request->cookies->getValue('hideItems'));
        $hideItems = !is_array($hideItems) ? [$hideItems] : $hideItems;
        $dataProvider->query->andWhere(['NOT IN', 'shop_item.id', array_filter($hideItems)]);

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
            'server' => Yii::$app->request->get('server'),
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

    protected function handleLastServer(){
        if(Yii::$app->request->cookies->getValue('lastServer', 'thor') != Yii::$app->request->get('server')){
            Yii::$app->response->cookies->add(new \yii\web\Cookie([
                'name' => 'lastServer',
                'value' => Yii::$app->request->get('server'),
                'expire' => (time() + 60 * 60 * 24 * 7),
            ]));
        }
    }

    public function actionHide($id)
    {
        $hideItems = Yii::$app->request->cookies->getValue('hideItems');
        $hideItems = json_decode($hideItems);
        $hideItems = !is_array($hideItems) ? [$hideItems] : $hideItems;
        array_push($hideItems, $id);

        Yii::$app->response->cookies->add(new \yii\web\Cookie([
            'name' => 'hideItems',
            'value' => json_encode($hideItems),
            'expire' => (time() + 60 * 60 * 24 * 7),
        ]));

        Yii::$app->getSession()->setFlash('success', Yii::t('app', 'Successful, The item has been hidden.'));

        return $this->redirect(Yii::$app->request->referrer);
    }

    public function actionMarkDelete($id)
    {
        
        $reportItems = Yii::$app->request->cookies->getValue('reportItems');
        $reportItems = json_decode($reportItems);
        $reportItems = !is_array($reportItems) ? [$reportItems] : $reportItems;

        if(in_array($id, $reportItems)){
            Yii::$app->getSession()->setFlash('danger', Yii::t('app', 'Fail, You have reported already.'));
            return $this->redirect(Yii::$app->request->referrer);
        }

        $model = $this->findModel($id);
        $model->delete_count = $model->delete_count + 1;
        $model->detachBehavior('timestamp');
        
        if($model->save()){
            array_push($reportItems, $id);

            Yii::$app->response->cookies->add(new \yii\web\Cookie([
                'name' => 'reportItems',
                'value' => json_encode($reportItems),
                'expire' => (time() + 60 * 60 * 24 * 7),
            ]));

            Yii::$app->getSession()->setFlash('success', Yii::t('app', 'Successful, This item will be deleted if there are many people reporting.', [
                'item_name' => $model->item->item_name,
                'shop_name' => $model->shop->shop_name,
            ]));
        }else{
            Yii::$app->getSession()->setFlash('danger', Yii::t('app', 'Fail, Something went wrong.'));
        }

        return $this->redirect(Yii::$app->request->referrer);
    }
}
