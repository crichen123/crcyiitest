<?php

namespace app\controllers;

use Yii;

use yii\web\Controller;
use app\service\Supplierservice;



class SupplierController extends Controller
{


    /**
     * Display suppliers.
     *
     * @return string
     */
    public function actionIndex()
    {
        $formParams = Yii::$app->request->getBodyParams();
        $service = Supplierservice::getService();
        if(isset($formParams['export'])){
            return $this->exportFile($service,$formParams);
        }

        $dataProvider = $service->getDataProvider(Yii::$app->request->getQueryParams());
        return $this->render('index', [
            'searchModel' => $service,
            'dataProvider' => $dataProvider,
        ]);

    }

    /**
     * export excel.
     *
     * @return file
     */
    public function exportFile(yii\db\ActiveRecord $service, array $formParams)
    {

        $result = [];
        //var_dump($formParams);die();
        if(isset($formParams['export']['selectforallpage']) && (integer)$formParams['export']['selectforallpage'] == 1) {
            $result = $service->getConditionExportResult();
        }elseif (isset($formParams['export']['selectitems'])) {
            $result = $service->getIdsExportResult($formParams['export']);
        }
        $this->simpleCsv($result);
        Yii::$app->response->statusCode = 200;
        return true;

    }

    /**
     * simple make csv
     * @param array $array
     * @return false|string|null
     */
    private function simpleCsv(array &$array)
    {
        $webpath = '/files/'.md5(uniqid()).'.csv';
        $filepath = Yii::getAlias('@web').$webpath;

        if (empty($array)) {
            Yii::warning("cannot be empty");
        }
        $csv = tmpfile();

        $bFirstRowHeader = true;
        foreach ($array as $row) {
            if ($bFirstRowHeader)  {
                fputcsv($csv, array_keys($row));
                $bFirstRowHeader = false;
            }
            fputcsv($csv, array_values($row));
        }

        rewind($csv);
        $fstat = fstat($csv);
        $this->setHeader($filepath, $fstat['size']);

        fpassthru($csv);
        fclose($csv);

        return $webpath;
    }

    /**
     * set export header
     * @param $filename
     * @param $filesize
     */
    private function setHeader($filename, $filesize)
    {
        $now = gmdate("D, d M Y H:i:s");
        $headers = Yii::$app->response->headers;

        $headers->add("Expires","Tue, 01 Jan 2001 00:00:01 GMT");
        $headers->add("Cache-Control","max-age=0, no-cache, must-revalidate, proxy-revalidate");
        $headers->add("Last-Modified","{$now} GMT");

        $headers->add("Content-Type","application/force-download");
        $headers->add("Content-Type","application/octet-stream");
        $headers->add("Content-Type","application/download");
        $headers->add("Content-Type","text/x-csv");


        if (isset($filename) && strlen($filename) > 0)
            $headers->add("Content-Disposition","attachment;filename={$filename}");
        if (isset($filesize))
            $headers->add("Content-Length",$filesize);
        $headers->add("Content-Transfer-Encoding","binary");
        $headers->add("Connection","close");
    }


}
