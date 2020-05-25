<?php 
use miloschuman\highcharts\Highcharts;
use yii\web\JsExpression;
$ListSetup = new app\models\ListSetup();

    echo Highcharts::widget([
        'htmlOptions'=>[
            'id'=>'live-facility-chart',
        ],
        'options' => [
            
            'chart'=>[
                    'type'=> 'spline'
                ],
           'title' => ['text' => ''],
           'xAxis' => [
              'categories' => $ListSetup->getMonth()
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
                            'click' => new JsExpression('function(e){ location.href = "history_booking?month="+this.x+"&facility_name="+this.series.name+"&year="+'.$year.'+"&a=1"}')

                        ]
                    ]
                ],
                
            ],
            'series'=> $facility_arr
        ]
    ]);
    
    
?>



