<?php

/** @var yii\web\View $this */
use yii\grid\GridView;
use yii\widgets\ActiveForm;
use yii\helpers\Html;
use app\widgets\CombindContent;
use yii\bootstrap4\Modal;
use app\widgets\JsFragment;


$this->title = 'Suppier List';


$errorsList = '';





?>

<h1 style="position: relative;text-align: center">Suppliers</h1>
<div class="supplier-index">
    <div class="post-search">
        <?php $form = ActiveForm::begin([
            'method' => 'post',
        ]); ?>
        <div class="col-md-12 bg-light text-right">
                <?= Html::Button('Export', ['class' => 'btn btn-primary ml-2','id'=>'exportfile','data-target'=>'#modal']) ?>
                <?= Html::input('hidden', 'export[selectitems]','',['id'=>'selectitems']) ?>
                <?= Html::input('hidden', 'export[selectforallpage]',0,['id'=>'selectforallpage']) ?>

        </div>

        <?php ActiveForm::end(); ?>
    </div>
<style>
    .help-block{
        color:red;
    }
</style>
<?= GridView::widget([
    'dataProvider' => $dataProvider,
    'filterModel' => $searchModel,
    'tableOptions' =>['class' => 'table table-striped table-bordered'],
    'columns' => [
        [
            'class' => 'yii\grid\CheckboxColumn',
            'headerOptions' => ['style' => 'text-align:center;', 'id'=>'selectall'],
            'contentOptions' => ['style' => 'text-align:center;'],
            'checkboxOptions' => function ($model, $key, $index, $column) {
                     return ['value' => $model->id];
             }

        ],
        [
            'class' => CombindContent::className(),
            'attribute'=>'id',
            'headerOptions' => ['style' => 'text-align:center','class'=>'col-md-3'],
            'contentOptions' => ['style' => 'margin-left:100px'],
            'filterInputOptions' =>['class' => 'form-control'],
            'filterContent' => function() use($searchModel)
            {
                $content =  '<div class="btn-group"> ' .Html::dropDownList('Supplierservice[nequal]',$searchModel->nequal ,
                        ['='=>'=','>'=>'>','<'=>'<','>='=>'>=','<='=>'<=','<>'=>'<>'],['class'=>'form-control  col-md-3'] ).
                    Html::input('text','Supplierservice[id]',$searchModel->id,
                        ['class' => (array_key_exists('id', $searchModel->errors)) ? 'form-control border-danger' : 'form-control']);

                $content .=  '</div>';
                if(array_key_exists('id', $searchModel->errors)){
                    $errorStr ='';
                    foreach ($searchModel->errors['id'] as $e) {
                        $errorStr .= $e;
                    }
                    $content .= Html::tag('div',$errorStr,['class'=>'help-block']);
                }
                return $content;
            },

        ],
        [
            'attribute'=>'name',
            'headerOptions' => [ 'style' => 'text-align:center;',],
            'contentOptions' => ['style' => 'text-align:center;'],
            'filterInputOptions' => [
                'class' => (array_key_exists('name', $searchModel->errors)) ? 'form-control border-danger' : 'form-control'
            ],
        ],
        [
           'attribute'=>'code',
            'headerOptions' => ['style' => 'text-align:center;'],
            'contentOptions' => ['style' => 'text-align:center;'],
            'filterInputOptions' => [
                'class' => (array_key_exists('code', $searchModel->errors)) ? 'form-control border-danger' : 'form-control'
            ],
        ],

        [
            'attribute'=>'t_status',
            'headerOptions' => ['style' => 'text-align:center;'],
            'contentOptions' => ['style' => 'text-align:center;'],
            'filter'=>$searchModel->getStatusArr(),
            'filterInputOptions'=>['prompt'=>'--List All--','class'=>'form-control'],
        ],

    ],
    'pager'=>[
        'disabledListItemSubTagOptions'=>['tag' => 'a', 'class' => 'page-link'],
        'firstPageCssClass' => 'page-item',
        'lastPageCssClass' => 'page-item',
        'linkOptions' => ['class' => 'page-link'],
        'pageCssClass' => 'page-item',
        'prevPageCssClass' => 'page-item',
        'nextPageCssClass' => 'page-item',
    ],
    'layout'=>"{summary}\n
                {items}\n
                <div style='text-align: right;'>
                    <div id='promptselect'  class='pull-right' style='display: none'>
                        <small>All <span id='selectcount'></span> conversations on this page have been selected .</small>
                        <a id='seleactallpage' href='#'>Select all conversations that match this search</a>
                    </div>
                     <div id='promptclear' class='pull-right' style='display: none'>
                        <small>All  conversations in this search have been selected .</small>
                        <a id='clearallpage' href='#'>clear selection</a>
                    </div>
                </div>\n
                <div style=\"position:absolute;bottom:0\" aria-label='Page navigation example'>{pager}</div>",
            'options'=>['class' => 'grid-view','style'=>'position:relative;height:500px;overflow:hidden'],


]);
?>

</div>
<?php JsFragment::begin()?>
<script>
    $(document).ready(function(){
        let pageresultcount = <?=$dataProvider->getCount() ?>;
        let totalcount = <?=$dataProvider->getTotalCount() ?>;

        $('#exportfile').click(function(){
            let keys = $('#w1').yiiGridView('getSelectedRows');
            if(keys.length<1 && $("#selectforallpage").val() != 1){
                $('#datamiss').modal('show');
                return;
            }else{
                $("#modal").modal('show')
            }


            //$('#selectitems').val(keys);
            //$('#w0').submit()
        });
        $('#selectall input').on('change',function(){
            let keys = $('#w1').yiiGridView('getSelectedRows');
            $('#selectitems').attr('value',keys)
            if(keys.length>0 && keys.length == pageresultcount && totalcount > pageresultcount){
                $("#selectcount").text(keys.length)
                $("#promptselect").show()
            }else{
                $("#promptselect").hide()
            }

        });
        $('#seleactallpage').on('click',function(){
            $("#selectforallpage").attr('value',1)
            $("#promptselect").hide();
            $("#promptclear").show();
            $("#w1 input").attr('disabled',true);
        })
        $('#clearallpage').on('click',function(){
            $("#selectforallpage").attr('value',0)
            $("#promptselect").show();
            $("#promptclear").hide();
            $("#w1 input").attr('disabled',false);
        })
        $('#confirmDown').on('click', function() {
            let keys = $('#w1').yiiGridView('getSelectedRows');
            $('#selectitems').val(keys);
            $('#w0').submit()
        });
    });

</script>
<?php JsFragment::end()?>
<?php
Modal::begin([
    'id' => 'modal',
    'headerOptions' => ['id' =>'modalHeader','style'=>'background-color:lightgrey;height:3em'],
    'footer' => '<button id="confirmDown" class="btn btn-primary" data-dismiss="modal">Download</button>',
]);
echo "<div id='modalContent'> <h5> Ready to download file?</h5></div>";
Modal::end();
?>
<?php
Modal::begin([
    'id' => 'datamiss',
    'headerOptions' => ['style'=>'background-color:lightgrey;height:3em'],
    'footer' => '<button id="confirmDown" class="btn btn-primary" data-dismiss="modal">Ok</button>',
]);
echo "<div id='modalContent'> <h5> Please select data first.</h5></div>";
Modal::end();
?>

