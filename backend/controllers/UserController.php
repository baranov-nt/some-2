<?php

namespace backend\controllers;

use Yii;
use common\models\User;
use common\models\UserSearch;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use common\rbac\models\AuthItem;
use common\rbac\models\Role;

/**
 * UserController implements the CRUD actions for User model.
 */
class UserController extends BehaviorsController
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all User models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new UserSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single User model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'modelUser' => $this->findModel($id),
        ]);
    }

    /**
     * Updates an existing User model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $modelUser = $this->findModel($id);

        if(Yii::$app->request->post('User')):
            //
            Role::deleteAll(['user_id' => $id]);

            if(Yii::$app->request->post('User')['item_name'] == ''):
                $sendRoles[] = 'Пользователь';
            else:
                $sendRoles = Yii::$app->request->post('User')['item_name'];
            endif;

            //
            foreach($sendRoles as $one):
                $model = new Role();
                $model->item_name = $one;
                $model->user_id = $id;
                $model->created_at = time();
                $model->save();
            endforeach;
        endif;

        if ($modelUser->load(Yii::$app->request->post()) && $modelUser->save()) {
            return $this->redirect(['view', 'id' => $modelUser->id]);
        } else {
            $value = ArrayHelper::map($modelUser->role, "item_name", "item_name");
            $allRoles=[];
            foreach (AuthItem::getRoles() as $item_name):
                if($item_name->name != 'Создатель'):
                    $allRoles[$item_name->name] = $item_name->name;
                endif;
            endforeach;

            return $this->render('update', [
                'modelUser' => $modelUser,
                'allRoles' => $allRoles,
                'value' => $value
            ]);
        }
    }

    /**
     * Deletes an existing User model.
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
     * Finds the User model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return User the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($modelUser = User::findOne($id)) !== null) {
            return $modelUser;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
