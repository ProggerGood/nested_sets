<?php

namespace app\controllers;

use app\models\Folders;
use app\models\FoldersSearch;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use klisl\nestable\NodeMoveAction;

/**
 * FoldersController implements the CRUD actions for Folders model.
 */
class FoldersController extends Controller
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
                'access' => [
                    'class' => AccessControl::class,
                    'rules' => [
                        [
                            'actions' => ['index', 'update', 'create', 'delete', 'view', 'nestable', 'nodeMove'],
                            'allow' => true,
                            'roles' => ['admin'],
                        ],
                    ],
                ],
            ]
        );
    }

    /**
     * Lists all Folders models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new FoldersSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Folders model.
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
     * Creates a new Folders model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new Folders();

        if (!empty($this->request->post('Folders'))) {

            $model = new Folders();
            $post            = $this->request->post('Folders');
            $model->name     = $post['name'];
            $parent_id       = $post['parent_id'];

            if (empty($parent_id))
                $model->makeRoot();
            else
            {
                $model->parent_id = $parent_id;
                $parent = Folders::findOne($parent_id);
                $model->appendTo($parent);
            }
            $model->save();
            return $this->redirect(['view', 'id' => $model->id]);

        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Folders model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if (!empty($this->request->post('Folders'))) {

            $post = $this->request->post('Folders');
            $model->name     = $post['name'];
            $parent_id       = $post['parent_id'];
            $model->parent_id  = $parent_id;

            if ($model->save())
            {
                if (empty($parent_id))
                {
                    if (!$model->isRoot())
                        $model->makeRoot();

                }
                else // move node to other root
                {
                    if ($model->id != $parent_id)
                    {
                        $parent = Folders::findOne($parent_id);
                        $model->appendTo($parent);
                    }
                }
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Folders model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);

        if ($model->isRoot())
            $model->deleteWithChildren();
        else
            $model->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Folders model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Folders the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Folders::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }


    public function actionNestable()
    {
        return $this->render('nestable');
    }

    public function actions() {
        return [
           'nodeMove' => [
               'class' => NodeMoveAction::class,
               'modelName' => Folders::class
           ],
       ];
    }

}
