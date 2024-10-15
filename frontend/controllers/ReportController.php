<?php

namespace frontend\controllers;

use frontend\repositories\BookRepositoryInterface;
use Yii;
use yii\data\ArrayDataProvider;
use yii\web\Controller;


class ReportController extends Controller
{

    /**
     * Lists all Book models.
     *
     * @return string
     */
    public function actionIndex(int $year)
    {
        $repository = Yii::createObject(BookRepositoryInterface::class);


        $dataProvider = new ArrayDataProvider([
            'allModels' => $repository->findTop10Authors($year),
        ]);

        return $this->render('top10', [
            'dataProvider' => $dataProvider,
            'year' => $year
        ]);

    }

}
