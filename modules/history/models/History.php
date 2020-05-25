<?php

namespace app\modules\history\models;

use Yii;

/**
 * This is the model class for table "history".
 *
 * @property integer $history_id
 * @property integer $history_user
 * @property string $history_action
 * @property string $history_date
 * @property integer $history_item
 * @property string $history_table
 * @property string $history_module
 * @property string $history_description
 * @property string $history_content
 */
class History extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'history';
    }
    
    /**
     * List of names for each status.
     * @var array
     */
    public $actionList = [
        'Delete'   => 'Delete',
        'Add' => 'Add',
        'Update'  => 'Update'
    ];

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['history_user', 'history_action', 'history_date', 'history_item'], 'required'],
            [['history_user','history_item'], 'integer'],
            [['history_date'], 'safe'],
            [['history_content'], 'string'],
            [['history_action'], 'string', 'max' => 100],
            [['history_table', 'history_module'], 'string', 'max' => 50],
            [['history_description'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'history_id' => Yii::t('app','ID'),
            'history_user' => Yii::t('app','User'),
            'history_action' => Yii::t('app','Action'),
            'history_date' => Yii::t('app','Date'),
            'history_item' => Yii::t('app','Item'),
            'history_table' => Yii::t('app','Table'),
            'history_module' => Yii::t('app','Module'),
            'history_description' => Yii::t('app','Description'),
            'history_content' => Yii::t('app','Content'),
        ];
    }
    
    public function getUser()
    {
        return $this->hasOne(\app\models\User::className(), ['id' => 'history_user']);
    }
    
    public function addHistory($record_id,$table_name,$module,$action,$description,$content=false){
        $history = new History();
        $history->history_user = YII::$app->user->id;
        $history->history_item = $record_id;
        $history->history_table = $table_name;
        $history->history_description = $description;
        $history->history_module = $module;
        $history->history_action = $action;
        if($content)
            $history->history_content = $content;
        $history->history_date = date('Y-m-d h:i:s');
        if($history->save())
            return true;
        return false;
    }
}
