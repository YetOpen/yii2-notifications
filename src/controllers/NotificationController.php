<?php

namespace webzop\notifications\controllers;

use webzop\notifications\model\Notifications;
use webzop\notifications\model\NotificationSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\Json;

/**
 * NotificationController implements the CRUD actions for Notifications model.
 */
class NotificationController extends Controller
{
    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                'verbs' => [
                    'class' => VerbFilter::class,
                    'actions' => [
                        'delete' => ['POST'],
                    ],
                ],
            ]
        );
    }

    /**
     * Lists all Notifications models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new NotificationSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        if (\Yii::$app->request->post('hasEditable'))
        {
            $id =\Yii::$app->request->post('editableKey');          
            $model  = $this->findModel($id);
           
            $out = Json::encode(['output'=>'', 'message'=>'']);          
            $post = [];

            $posted = current(\Yii::$app->request->post('Notifications'));  //model
            $post['Notifications'] = $posted;
       
            if ($model->load($post)) {
                $model->save();

                $output = '';
                if (isset($posted['editableAttribute'])){
                    $output = $model->editableAttribute;
                    $out = Json::encode(['output'=>$output, 'message'=>'']);
                }
            }
                 
         // return JSON encoded output in the below format
          echo $out;
          return;
        }

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);

    }

    /**
     * Displays a single Notifications model.
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
     * Finds the Notifications model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Notifications the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Notifications::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
