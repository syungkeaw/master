<?php

namespace frontend\controllers;

use Yii;
use common\models\Item;
use common\models\Shop;
use common\models\ShopSearch;
use common\models\ShopItem;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\base\Model;
use common\models\ShopItemSearch;
use yii\helpers\ArrayHelper;
use yii\web\ForbiddenHttpException;

/**
 * ShopController implements the CRUD actions for Shop model.
 */
class ShopController extends Controller
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
                        'actions' => ['index', 'cart', 'create', 'update', 'delete', 'close', 'open'],
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    /**
     * Lists all Shop models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ShopItemSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->query->andFilterWhere(['shop.created_by' => Yii::$app->user->identity->id]);
        $dataProvider->pagination->pageSize = 10;
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
        ]);
    }

    /**
     * Lists all Shop models.
     * @return mixed
     */
    public function actionCart()
    {
        $searchModel = new ShopSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->query->andFilterWhere(['shop.created_by' => Yii::$app->user->identity->id]);
        $items = Item::find()->all();

        $shopItem = ShopItem::find()->asArray()->all();
        $option = [];
        $option = array_merge($option, ArrayHelper::getColumn($shopItem, 'card_1'));
        $option = array_merge($option, ArrayHelper::getColumn($shopItem, 'card_2'));
        $option = array_merge($option, ArrayHelper::getColumn($shopItem, 'card_3'));
        $option = array_merge($option, ArrayHelper::getColumn($shopItem, 'card_4'));
        $option = array_filter($option);
        $option_item = Item::findAll(['source_id' => ['994', '995', '996', '997'] + $option]);

        return $this->render('index_cart', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'items' => $items,
            'option_item' => $option_item,
        ]);
    }

    /**
     * Displays a single Shop model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $shopItems = ShopItem::find()->where(['shop_id' => $id, 'status' => ShopItem::STATUS_ACTIVE])->all();
        if(empty($shopItems)){
            $shopItems = new ShopItem();
            $shopItems = [$shopItems];
        }

        return $this->render('view', [
            'model' => $this->findModel($id),
            'shopItems' => $shopItems,
        ]);
    }

    /**
     * Creates a new Shop model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $item_model = Item::find()->all();
        $shop_model = new Shop();
        for($slot = 0; $slot <= 11; $slot++){
            $shop_item_model[$slot] = new ShopItem();
        }

        $this->saveShop($shop_model, $shop_item_model);

        return $this->render('create', [
            'shop_model' => $shop_model,
            'shop_item_model' => $shop_item_model,
            'item_model' => $item_model,
        ]);
    }

    /**
     * Updates an existing Shop model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $item_model = Item::find()->all();
        $shop_model = $this->findModel($id);
        $shop_item_model = ShopItem::findAll(['shop_id' => $id]);

        for($slot = count($shop_item_model); $slot <= 11; $slot++){
            $shop_item_model[$slot] = new ShopItem();
        }

        foreach ($shop_item_model as $key => $model)
            $shop_item_model_old[$key] = clone $model;

        $this->saveShop($shop_model, $shop_item_model, $shop_item_model_old);
        
        return $this->render('update', [
            'shop_model' => $shop_model,
            'shop_item_model' => $shop_item_model,
            'item_model' => $item_model,
        ]);
    }

    /**
     * Deletes an existing Shop model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(Yii::$app->request->referrer);
    }

    public function actionClose($id)
    {
        $model = $this->findModel($id);
        $model->status = 0;

        if($model->save()){
            Yii::$app->getSession()->setFlash('success', Yii::t('app', 'Successful, Your shop "{shop_name}" has been closen already.',[
                'shop_name' => $model->shop_name,
            ]));
        }else{
            Yii::$app->getSession()->setFlash('danger', Yii::t('app', 'Something went wrong, Your shop "{shop_name}" cannot be closen.',[
                'shop_name' => $model->shop_name,
            ]));
        }

        return $this->redirect(Yii::$app->request->referrer);
    }

    public function actionOpen($id)
    {
        $model = $this->findModel($id);
        $model->status = 10;
        
        if($model->save()){
            Yii::$app->getSession()->setFlash('success', Yii::t('app', 'Successful, Your shop "{shop_name}" has been openned already.',[
                'shop_name' => $model->shop_name,
            ]));
        }else{
            Yii::$app->getSession()->setFlash('danger', Yii::t('app', 'Something went wrong, Your shop "{shop_name}" cannot be openned.',[
                'shop_name' => $model->shop_name,
            ]));
        }

        return $this->redirect(Yii::$app->request->referrer);
    }

    /**
     * Finds the Shop model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Shop the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Shop::findOne($id)) !== null) {
            $this->authen($model);
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    protected function authen($model)
    {
        if($model->created_by != Yii::$app->user->identity->id){
            throw new ForbiddenHttpException(Yii::t('app', 'It is NOT yours!!. You are not allowed to access this page.'));
        }
    }

    protected function saveShop($shop_model, $shop_item_model, $shop_item_model_old = null)
    {
        if ($shop_model->load(Yii::$app->request->post()) && $shop_model->validate()) {
            if (Model::loadMultiple($shop_item_model, Yii::$app->request->post()) && Model::validateMultiple($shop_item_model)) {
                if($shop_model->save()){
                    foreach ($shop_item_model as $key => $model) {
                        if(is_null($shop_item_model_old)|| ($shop_item_model_old[$key]->item_id != $model->item_id
                                                        || $shop_item_model_old[$key]->amount != $model->amount
                                                        || $shop_item_model_old[$key]->price != $model->price
                                                        || $shop_item_model_old[$key]->card_1 != $model->card_1
                                                        || $shop_item_model_old[$key]->card_2 != $model->card_2
                                                        || $shop_item_model_old[$key]->card_3 != $model->card_3
                                                        || $shop_item_model_old[$key]->card_4 != $model->card_4
                                                        || $shop_item_model_old[$key]->enhancement != $model->enhancement
                                                        || $shop_item_model_old[$key]->very != $model->very
                                                        || $shop_item_model_old[$key]->element != $model->element
                                                        )
                        )
                        {
                            if($model->item_id) {
                                $model->shop_id = $shop_model->id;
                                $model->save();
                            }
                        }

                    }
                }
                return $this->redirect(['index']);
            }
        } 
    }
}
