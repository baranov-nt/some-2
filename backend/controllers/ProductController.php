<?php

namespace backend\controllers;

use Yii;
use common\models\Product;
use common\models\ProductSearch;
use yii\web\NotFoundHttpException;

/**
 * ProductController implements the CRUD actions for Product model.
 */
class ProductController extends BehaviorsController
{
    /**
     * Lists all Product models.
     * @return mixed
     */
    public function actionIndex()
    {
        $pageSize = 10;

        $searchModel = new ProductSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, $pageSize);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Product model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'modelProduct' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Product model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        /* @var $modelProduct \common\models\Product */

        $modelProduct = Product::findOne(Yii::$app->session->get('tempId'));

        if(!isset($modelProduct)):
            Yii::$app->session->remove('tempModel');
            Yii::$app->session->remove('tempId');
        endif;

        if (isset($modelProduct) && $modelProduct->load(Yii::$app->request->post())):
            if($modelProduct->updateObject($modelProduct)):
                return $this->redirect(['view', 'id' => $modelProduct->id]);
            endif;
        endif;

        if(Yii::$app->session->get('tempModel') != 'Product'):

            $modelProduct = new Product();
            $modelProduct = $modelProduct->createObject();
        endif;

        return $this->render('create', [
            'modelProduct' => $modelProduct,
        ]);
    }

    /**
     * Updates an existing Product model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        /* @var $modelProduct \common\models\Product */

        $modelProduct = $this->findModel($id);

        if ($modelProduct->load(Yii::$app->request->post())):
            if($modelProduct->updateObject($modelProduct)):
                return $this->redirect(['view', 'id' => $modelProduct->id]);
            endif;
        endif;

        return $this->render('update', [
            'modelProduct' => $modelProduct,
        ]);
    }

    /**
     * Deletes an existing Product model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        /* @var $modelProduct \common\models\Carousel */

        $modelProduct = $this->findModel($id);

        $modelProduct->deleteObject($modelProduct);

        return $this->redirect(['index']);
    }

    /**
     * Finds the Product model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Product the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Product::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
