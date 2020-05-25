<?php 
use miloschuman\highcharts\Highcharts;
use yii\web\JsExpression;
$ListSetup = new app\models\ListSetup();
    $xaxis = $ListSetup->getMonth();
    if($type==0){
        $xaxis = $ListSetup->getMonth();
        $url = new JsExpression('function(e){ location.href = "chekin?month="+this.x+"&year="+this.series.name+"&a=1"}');
    }
    if($type==1){
        $xaxis = $ListSetup->getWeek();
        $url = '';
    }
    if($type==2){
        $xaxis = $ListSetup->getDay();
        $url = '';
    }
    if($type==3){
        $xaxis = $ListSetup->getHour();
        $url = '';
    }

    echo Highcharts::widget([
        'htmlOptions'=>[
            'id'=>'checkin-chart',
        ],
        'options' => [
            
            'chart'=>[
                    'type'=> 'spline'
                ],
           'title' => ['text' => ''],
           'xAxis' => [
              'categories' => $xaxis
           ],
           'yAxis' => [
              'title' => ['text' => '']
           ],
            'tooltip'=> [
                'crosshairs'=> true,
                'shared'=> true
            ],
            'plotOptions'=> [
                'spline'=> [
                    'marker'=> [
                        'radius'=> 4,
                        'lineColor'=> '#666666',
                        'lineWidth'=> 1
                    ]
                ],
                'series'=> [
                    'cursor'=> 'pointer',
                    'point'=> [
                        'events'=> [
                            'click' => $url

                        ]
                    ]
                ]
            ],
            'series'=> [[
                    'name'=> $year,
                    'marker'=> [
                        'symbol'=> 'square'
                    ],
                    'data'=> $this_year

                ]]

        ]
    ]);
    
    
?>


