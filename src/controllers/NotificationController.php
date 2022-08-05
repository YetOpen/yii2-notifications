<?php

namespace webzop\notifications\controllers;

use yii;
use webzop\notifications\model\Notifications as NotificationModel;
use webzop\notifications\widgets\Notifications;
use webzop\notifications\model\NotificationSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\Json;
use yii\helpers\Url;
use webzop\notifications\helpers\TimeElapsed;

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
        if (($model = NotificationModel::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionList()
    {
        $userId = Yii::$app->getUser()->getId();
        $list = NotificationModel::find()
            ->andWhere(['or', 'user_id = 0', 'user_id = :user_id'], [':user_id' => $userId])
            ->andWhere(['<=', 'send_at', date('Y-m-d H:i:s')])
            ->orderBy(['id' => SORT_DESC])
            ->limit(10)
            ->asArray()
            ->all();
        $notifs = $this->prepareNotifications($list);
        $this->ajaxResponse(['list' => $notifs]);
    }

    private function prepareNotifications($list){
        $notifs = [];
        $seen = [];
        foreach($list as $notif){
            if(!$notif['seen']){
                $seen[] = $notif['id'];
            }
            $route = @unserialize($notif['route']);
            $notif['url'] = !empty($route) ? Url::to($route) : '';
            $notif['timeago'] = TimeElapsed::timeElapsed($notif['created_at']);
            $notifs[] = $notif;
        }

        if(!empty($seen)){
            Yii::$app->getDb()->createCommand()->update('{{%notifications}}', ['seen' => true], ['id' => $seen])->execute();
        }

        return $notifs;
    }

    public function actionNote(){
        return $this->render('note');
    }

    public function actionCount()
    {
        $count = Notifications::getCountUnseen();
        $this->ajaxResponse(['count' => $count]);
    }

    public function actionRead($id)
    {
        Yii::$app->getDb()->createCommand()->update('{{%notifications}}', ['read' => true], ['id' => $id])->execute();

        if(Yii::$app->getRequest()->getIsAjax()){
            return $this->ajaxResponse(1);
        }

        return Yii::$app->getResponse()->redirect(['/notifications/notification/index']);
    }

    public function actionReadAll()
    {
        Yii::$app->getDb()->createCommand()->update(
            '{{%notifications}}',
            ['read' => true, 'seen' => true],
            ['user_id' => Yii::$app->user->id]
            )->execute();
        if(Yii::$app->getRequest()->getIsAjax()){
            return $this->ajaxResponse(1);
        }

        Yii::$app->getSession()->setFlash('success', Yii::t('modules/notifications', 'All notifications have been marked as read.'));

        return Yii::$app->getResponse()->redirect(['/notifications/notification/index']);
    }

    public function actionDeleteAll()
    {
        Yii::$app->getDb()->createCommand()->delete('{{%notifications}}')->execute();

        if(Yii::$app->getRequest()->getIsAjax()){
            return $this->ajaxResponse(1);
        }

        Yii::$app->getSession()->setFlash('success', Yii::t('modules/notifications', 'All notifications have been deleted.'));

        return Yii::$app->getResponse()->redirect(['/notifications/notification/index']);
    }

    public function ajaxResponse($data = [])
    {
        if(is_string($data)){
            $data = ['html' => $data];
        }

        $session = \Yii::$app->getSession();
        $flashes = $session->getAllFlashes(true);
        foreach ($flashes as $type => $message) {
            $data['notifications'][] = [
                'type' => $type,
                'message' => $message,
            ];
        }
        return $this->asJson($data);
    }
}
