<?php

namespace backend\controllers;

use Yii;
use common\models\Carousel;
use common\models\CarouselSearch;
use yii\web\NotFoundHttpException;

/**
 * CarouselController implements the CRUD actions for Carousel model.
 */
class CarouselController extends BehaviorsController
{
    /**
     * Lists all Carousel models.
     * @return mixed
     */
    public function actionIndex()
    {


        $searchModel = new CarouselSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Carousel model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'modelCarousel' => $this->findModel($id),
        ]);
    }

    /**
     * При создании нового элемента карусели, это элемента карусели автоматически создается, для загрузки изображений для данного элемента карусели
     * Создание элемента карусели происходит в методе $modelCarousel->createObject(); модели Carousel
     * После успешного создания элемента карусели в сессии добавляются две переменных tempModel (объект элемента карусели) и tempId (id объекта)
     * При обновлении действия create, загружается объект tempId модели tempModel
     * Если не была нажата кнопка "Создать элемента карусели", а был сделан переход на другое действие или контроллер, то срабатывает поведение ClearTempBehaviors
     * подключенное в поведениях
     *
     * Creates a new Carousel model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        /* @var $modelCarousel \common\models\Carousel */
        $modelCarousel = Carousel::findOne(Yii::$app->session->get('tempId'));

        if(!isset($modelCarousel)):
            Yii::$app->session->remove('tempModel');            // ощищаем сессии
            Yii::$app->session->remove('tempId');
        endif;

        if (isset($modelCarousel) && $modelCarousel->load(Yii::$app->request->post())):
            if($modelCarousel->updateObject($modelCarousel)):
                return $this->redirect(['view', 'id' => $modelCarousel->id]);
            endif;
        endif;

        if(Yii::$app->session->get('tempModel') != 'Carousel'):
            $modelCarousel = new Carousel();
            $modelCarousel = $modelCarousel->createObject();
        endif;

        return $this->render('create', [
            'modelCarousel' => $modelCarousel,
        ]);
    }

    /**
     * Updates an existing Carousel model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        /* @var $modelCarousel \common\models\Carousel */

        $modelCarousel = $this->findModel($id);

        if ($modelCarousel->load(Yii::$app->request->post())):
            if($modelCarousel->updateObject($modelCarousel)):
                return $this->redirect(['view', 'id' => $modelCarousel->id]);
            endif;
        endif;

        return $this->render('update', [
            'modelCarousel' => $modelCarousel,
        ]);
    }

    /**
     * Deletes an existing Carousel model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        /* @var $modelCarousel \common\models\Carousel */

        $modelCarousel = $this->findModel($id);

        $modelCarousel->deleteObject($modelCarousel);

        return $this->redirect(['index']);
    }

    /**
     * Finds the Carousel model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Carousel the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Carousel::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
