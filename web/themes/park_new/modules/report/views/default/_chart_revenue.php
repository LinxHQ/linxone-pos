<?php
use miloschuman\highcharts\Highcharts;
use yii\web\JsExpression;
if($month>=1 && $month<=12){
    $month_new = $month - 1;
    $url = new JsExpression('function(e){ location.href = "paymentreport?month="+'.$month_new.'+"&year="+'.$year.'+"&revenue_name="+e.point.name}');
}else{
    $url = new JsExpression('function(e){ location.href = "paymentreport?year="+'.$year.'+"&revenue_name="+e.point.name+"&a=1"}');
}
echo Highcharts::widget([
    'htmlOptions'=>[
        'id'=>'revenue-chart',
    ],
    'options' => [
    'chart'=> [
        'type'=> 'pie'
    ],
    'title'=> [
        'text'=> ''
    ],
//    'subtitle'=> [
//        'text'=> ''
//    ],
    'plotOptions'=> [
        'series'=> [
            'dataLabels'=> [
                'enabled'=> true,
                'format'=> '{point.name}: {point.y:.1f}%'
            ],
            'cursor'=> 'pointer',
            'point'=> [
                'events'=> [
                    'click' => $url
                ]
            ]
        ]
    ],

    'tooltip'=> [
        'headerFormat'=> '<span style="font-size:11px">{series.name}</span><br>',
        'pointFormat'=> '<span style="color:{point.color}">{point.name}</span>: {point.amount} - <b>{point.y:.2f}%</b> of total<br/>'
    ],
    'series'=> [[
        'name'=>Yii::t('app','Revenue'),
        'colorByPoint'=> true,
        'data'=> $revenue_type_arr
    ]],
    ]
]);