<?php
namespace app\widgets;

use Yii;
use yii\grid\DataColumn;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\grid\ActionColumn;
use yii\helpers\ArrayHelper;

class CombindContent extends DataColumn
{
    public $filterContent;

    protected function renderFilterCellContent()
    {
        return $this->filterContent instanceof \Closure ? call_user_func($this->filterContent) : $this->filterContent;
    }



}

