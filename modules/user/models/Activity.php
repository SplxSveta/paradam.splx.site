<?php

namespace app\modules\user\models;

use Yii;

/**
 * This is the model class for table "activity".
 *
 * @property int $id
 * @property int|null $user_id
 * @property int|null $type 0 => profile, 1 => add_funds, 2 => service, 3 => photo, 4 => video, 5 => products, 6 => investition
 * @property string|null $additional
 * @property string|null $created_at
 *
 * @property User $user
 */
class Activity extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'activity';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'type'], 'integer'],
            [['additional'], 'string'],
            [['created_at'], 'safe'],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'type' => 'Type',
            'additional' => 'Additional',
            'created_at' => 'Created At',
        ];
    }

    /**
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    public function getNameType($type) {
    	switch ($type) {
		    case 0:
		    	return 'Profile';
		    	break;
		    case 1:
			    return 'Add funds';
		    	break;
		    case 2:
			    return 'Service';
		    	break;
		    case 3:
			    return 'Photo';
		    	break;
		    case 4:
			    return 'Video';
		    	break;
		    case 5:
			    return 'Products';
		    	break;
		    case 6:
		    	return 'Investition';
		    	break;
	    }
    }

	public function getAdditionalFormated($additional)
	{
		if ($this->isJSON($additional)) {
			$html = '';
			$parse = json_decode($additional, true);

			if (isset($parse['amount'])) {
				$html = '<span class="label label-primary">Amount: ' . $parse['amount'] . ' ' . User::CURRENCY_BIT . '</span><br>';
			}

			return $html;
		} else {
			return $additional;
		}
    }

    private function isJSON($string){
	    return is_string($string) && is_array(json_decode($string, true)) ? true : false;
    }
}
