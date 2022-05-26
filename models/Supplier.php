<?php

namespace app\models;

use yii\db\ActiveRecord;
use yii\data\ActiveDataProvider;

class Supplier extends ActiveRecord
{

        /**
         * addition attribute for id condition
         * @var
         */
        public $nequal;

        /**
         * constant for enum status
         */
        const STATUS_OK = 'ok';
        const STATUS_HOLD = 'hold';


        /**
         * set tablename
         * @return string
         */
        public static function tableName()
        {
            return '{{supplier}}';
        }


        /**
         * @return array the validation rules.
         */
        public function rules()
        {
            return [
                ['id','integer'],
                ['name','stringValidate'],
                ['name','string','max'=>20],
                ['code','stringValidate'],
                ['code','string','max'=>3],
                ['t_status','string','max'=>4],
                ['nequal', 'safe'],
                ['nequal','string','max'=>3]
            ];
        }

        /**
         * string validate
         * @param $attribute
         * @param $params
         */
        public function stringValidate($attribute, $params) {
            $value = $this->$attribute;
            if(ctype_space($value)){
                $this->addError($attribute,$attribute.' cannot be empty');
            }elseif(preg_match('/[@_!#$%^&*()<>?\}{~\-:=]/',$value)) {
                $this->addError($attribute,$attribute.' only can be integer and string');
            }
      }

        /**
         * @return array the validation rules.
         */
        public function attributeLabels()
        {
            return [
                'id' => \Yii::t('app', 'ID'),
                'name' => \Yii::t('app', 'Name'),
                'code' => \Yii::t('app', 'Code'),
                't_status' => \Yii::t('app', 'Status'),
            ];
        }

        /**
         * @param $nequal
         */
        public function setNequal($nequal)
        {
            $this->nequal = (string) $nequal;
        }

        /**
         * @return mixed
         */
        public function getNequal()
        {
            return $this->nequal;
        }








}
