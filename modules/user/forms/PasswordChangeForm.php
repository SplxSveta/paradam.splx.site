<?php
// paradam.me.loc/PasswordChangeForm.php
	
	namespace app\modules\user\forms;
	
	use app\modules\admin\models\User;
	use app\modules\user\Module;
	use yii\base\Model;
	
	/**
	 * Password reset form
	 */
	class PasswordChangeForm extends Model
	{
		public $currentPassword;
		public $newPassword;
		public $newPasswordRepeat;
		
		/**
		 * @var User
		 */
		private $_user;
		
		/**
		 * @param User $user
		 * @param array $config
		 */
		public function __construct(User $user, $config = [])
		{
			$this->_user = $user;
			parent::__construct($config);
		}
		
		public function rules()
		{
			return [
				[['currentPassword', 'newPassword', 'newPasswordRepeat'], 'required'],
				['currentPassword', 'currentPassword'],
				['newPassword', 'string', 'min' => 6],
				['newPasswordRepeat', 'compare', 'compareAttribute' => 'newPassword'],
			];
		}
		
		public function attributeLabels()
		{
			return [
				'newPassword' => Module::t('app', 'Новый пароль'),
				'newPasswordRepeat' => Module::t('app', 'Новый пароль (Повторите для проверки)'),
				'currentPassword' => Module::t('app', 'Текущий пароль'),
			];
		}
		
		/**
		 * @param string $attribute
		 * @param array $params
		 */
		public function currentPassword($attribute, $params)
		{
			if (!$this->hasErrors()) {
				if (!$this->_user->validatePassword($this->$attribute)) {
					$this->addError($attribute, Module::t('app', 'ERROR_WRONG_CURRENT_PASSWORD'));
				}
			}
		}
		
		/**
		 * @return boolean
		 * @throws \yii\base\Exception
		 */
		public function changePassword()
		{
			if ($this->validate()) {
				$user = $this->_user;
				$user->setPassword($this->newPassword);
				return $user->save();
			} else {
				return false;
			}
		}
	}