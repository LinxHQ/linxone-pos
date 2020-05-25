<?php 
use miloschuman\highcharts\Highcharts;
use yii\web\JsExpression;
$ListSetup = new app\models\ListSetup();

    echo Highcharts::widget([
        'setupOptions'=>[
            'lang'=>[
                'numericSymbols'=>$ListSetup->getNumericSymbols()
            ]
        ],
        'htmlOptions'=>[
            'id'=>'payment-chart',
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
                        'click' => new JsExpression('function(e){ location.href = "paymentreport?month="+this.x+"&year="+this.series.name}')
                        
                    ]
                ]
        ]
            ],
            'series'=> [
                [
                    'name'=>$year,
                    'marker'=> [
                        'symbol'=> 'diamond'
                    ],
                    'data'=> $this_year
                ],
                [
                    'name'=> $last_y,
                    'marker'=> [
                        'symbol'=> 'square'
                    ],
                    'data'=> $last_year

                ]]

        ]
    ]);
    
    
?>

