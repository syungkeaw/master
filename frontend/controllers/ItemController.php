<?php

namespace frontend\controllers;

use Yii;
use common\models\Job;
use common\models\Item;
use common\models\ItemSearch;
use common\models\ItemClass;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\classes\Ro;

/**
 * ItemController implements the CRUD actions for Item model.
 */
class ItemController extends Controller
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
                        'allow' => false,
                    ],
                ],
            ],
        ];
    }

    public function actionNewitem()
    {
        ini_set('max_execution_time', 300);
        $url = 'http://ratemyserver.net/index.php?page=item_db'; 
        $ro = new Ro();

        $type_id = 3;
        for($i = 1; $i <= 136 ; $i++){

            $items = $ro->getInfo($url, $type_id, $i);

            foreach ($items as $key => $value) {
                $item = new Item();
                $item->source_id = $value['item_id'];
                $item->item_name = $value['item_name'];
                $item->item_slot = empty($value['item_slot']) || !is_numeric($value['item_slot']) ? 0 : $value['item_slot'];;
                $item->item_slot_spare = empty($value['item_slot_spare']) ? 0 : $value['item_slot_spare'];;
                $item->item_num_hand = $value['item_num_hand'] == 'One Hand' ? 1 : 2;
                $item->item_type_id = $value['item_type_id'];
                $item->item_type = $value['item_type'];
                $item->item_class = $value['item_class'];
                $item->item_attack = empty($value['item_attack']) ? 0 : $value['item_attack'];
                $item->item_defense = empty($value['item_defense']) ? 0 : $value['item_defense'];
                $item->item_required_lvl = empty($value['item_required_lvl']) || $value['item_required_lvl'] == 'None' ? 0 : $value['item_required_lvl'];
                $item->item_weapon_lvl = empty($value['item_weapon_lvl']) || $value['item_weapon_lvl'] == 'n/a' ? 0 : $value['item_weapon_lvl'];
                $item->item_description = $value['item_description'];

                $jobs = [];

                if(!empty($value['item_jobs'])){
                    foreach ($value['item_jobs'] as $jobx) {
                        $exist_job = Job::find()->where(['job_name' => $jobx])->one();
                        if(!empty($exist_job)){
                            $jobs[] = $exist_job->id;
                            continue;
                        } 

                        $job = new Job();
                        $job->job_name = $jobx;
                        $job->save();
                        $jobs[] = $job->id;

                        $error = $job->getErrors();
                        if(!empty($error)){
                            echo 'error-page:'. $i;
                            echo '<pre>';
                            print_r($error);
                            die;
                        }
                    }
                }

                if($value['item_class']){
                    if(ItemClass::find()->where(['class_name' => $value['item_class']])->count() == 0){
                        $item_class = new ItemClass();
                        $item_class->class_name = $value['item_class'];
                        $item_class->class_type_id = $type_id;
                        $item_class->save();
                        $error = $item_class->getErrors();
                        if(!empty($error)){
                            echo 'error-page:'. $i;
                            echo '<pre>';
                            print_r($error);
                            die;
                        }
                    } 
                }

                $item->jobIds = $jobs;

                if(Item::find()->where(['source_id' => $value['item_id']])->count() > 0) continue;

                $item->save();

                $error = $item->getErrors();
                if(!empty($error)){
                    echo 'item_slot:'. $value['item_slot']. '/' . $value['item_slot_spare']. '<br>';
                    echo 'error-page:'. $i;
                    echo '<pre>';
                    print_r($error);
                    die;
                }
            }
        }

        echo '<pre>';
        print_r($items);
        die;
    }

    public function actionItemimg()
    {
        ini_set('max_execution_time', 10000);

        $existed = [];
        foreach(glob('../web/images/items/small/*.*') as $filename){
            $filename = str_replace('../web/images/items/small/', '', $filename);
            $filename = str_replace('.gif', '', $filename);
            $existed[] = $filename;
         }

        //  echo '<pre>', print_r($existed);

        // die; 
        $items = Item::find()->where(['not in', 'source_id', $existed])->all();

        // echo '<pre>', print_r($items); die;

        foreach ($items as $key => $item) {
            $imgp = Yii::getAlias('@web'). '/images/items/small/'. $item->source_id . '.gif';
            $imgl = Yii::getAlias('@web'). '/images/items/large/'. $item->source_id . '.gif';

            $url = Yii::$app->params['item_small_image_url2']. $item->source_id . '.gif';
            $img = Yii::getAlias('@frontend'). '/web/images/items/small/'. $item->source_id . '.gif';

            $ch = curl_init($url);
            $fp = fopen($img, 'wb');
            curl_setopt($ch, CURLOPT_FILE, $fp);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_exec($ch);
            curl_close($ch);
            fclose($fp);


            // $url = Yii::$app->params['item_large_image_url']. $item->source_id . '.gif';
            // $img = Yii::getAlias('@frontend'). '/web/images/items/large/'. $item->source_id . '.gif';

            // $ch = curl_init($url);
            // $fp = fopen($img, 'wb');
            // curl_setopt($ch, CURLOPT_FILE, $fp);
            // curl_setopt($ch, CURLOPT_HEADER, 0);
            // curl_exec($ch);
            // curl_close($ch);
            // fclose($fp);

            echo ($key+1). ' :: <img src="'. $imgp .'">  |  ';
            echo '<img src="'. $imgl .'"> <br>';

        }

    }

    /**
     * Lists all Item models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ItemSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Item model.
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
     * Creates a new Item model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Item();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Item model.
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
     * Deletes an existing Item model.
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
     * Finds the Item model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Item the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Item::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
