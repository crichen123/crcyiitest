<?php
namespace app\service;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;
use app\models\Supplier;

class Supplierservice extends Supplier
{
        /**
         * cache timeout
         */
        const CACHE_TIMEOUT = 5;
        /**
         * @var  singlone instance
         */
        private static $_instance;

        /**
         * @return singlone|Supplierservice
         */
        public static function getService()
        {
            if(!(self::$_instance instanceof self)) {
                self::$_instance = new self();
            }
            return self::$_instance;
        }

        /**
         * @return array
         */
        public function getStatusArr()
        {
            return [Supplier::STATUS_OK=>Supplier::STATUS_OK,Supplier::STATUS_HOLD=>Supplier::STATUS_HOLD];
        }


        /**
         * return dataprovider for gridview
         * @param array $params
         * @param bool $needPage
         * @return ActiveDataProvider
         */
        public function getDataProvider(array $params =[],$needPage = true)
        {
            $query = Supplier::find();

            $option = [
                'query' => $query,
                'sort' => [
                    'defaultOrder' => [
                        'id' => SORT_DESC,
                    ]
                ],
            ];
            if($needPage) {
                $option['pagination'] = [ 'pageSize' => 5 ];
            }

            $dataProvider = new ActiveDataProvider($option);
            $this->load($params);

            if (! $this->validate()) {
                return $dataProvider;
            }
            $this->combindWhere($query);
            //var_dump($this);die();

            //cache
            $db = Yii::$app->db;
            Yii::$app->db->cache(function() use($dataProvider){
                $dataProvider->prepare();
            }, self::CACHE_TIMEOUT);
            return $dataProvider;
        }

        /**
         * @param array $items
         * @return array|\yii\db\ActiveRecord[]
         */
        public function getIdsExportResult(array $items) {
            $params = explode(',',$items['selectitems']);
            if(!empty($params)) {
                $params = array_map(function($v){
                    return (integer)$v;
                },$params);
                $query = Supplier::find();
                $db = Yii::$app->db;
                return $db->cache(function ($db) use ($query, $params) {
                    return $query->where(['in','id',$params])->orderBy("id desc")->asArray()->all();
                }, self::CACHE_TIMEOUT);
            }
            return [];
        }

        /**
         * use params conditon search result
         * @return array|\yii\db\ActiveRecord[]
         */
        public function getConditionExportResult()
        {
            $query = Supplier::find();

            $this->load(Yii::$app->request->getQueryParams());
            $this->combindWhere($query);
            $db = Yii::$app->db;
            return $db->cache(function ($db) use ($query) {
                return $query->orderBy("id desc")->asArray()->all();
            }, self::CACHE_TIMEOUT);
        }

        /**
         * combind search condition
         * @param $query
         */
        private function combindWhere(&$query)
        {
            if ($this->getAttribute('id')) {
                if($this->nequal){
                    $query->andWhere([$this->nequal,'id',$this->id]);
                }else{
                    $query->andWhere(['id'=>$this->id]);
                }
            }
            if ($this->getAttribute('t_status')) {
                $query->andWhere(['t_status'=>$this->t_status]);
            }
            if ($this->getAttribute('name')) {
                $query->andWhere(['like', 'name', $this->name]);
            }
            if ($this->getAttribute('code')) {
                $query->andWhere(['like', 'code', $this->code]);
            }

        }

        /**
         *
         */
        private function getFromCache()
        {
            return [];
        }
}
