<?php

/**
 * This is the model class for table "tb_usuario".
 *
 * The followings are the available columns in table 'tb_usuario':
 * @property string $id_usuario
 * @property string $uid_facebook
 * @property string $email
 * @property string $senha
 * @property string $nome
 * @property string $celular
 * @property integer $ativo
 *
 * The followings are the available model relations:
 * @property Carro[] $carros
 * @property CartaoCredito[] $cartaoCreditos
 * @property Token[] $tokens
 */
class Usuario extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'tb_usuario';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('email, senha, nome, celular', 'required'),
			array('ativo', 'numerical', 'integerOnly'=>true),
			array('uid_facebook', 'length', 'max'=>20),
			array('email, senha', 'length', 'max'=>45),
			array('nome', 'length', 'max'=>100),
			array('celular', 'length', 'max'=>15),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id_usuario, uid_facebook, email, senha, nome, celular, ativo', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'carros' => array(self::HAS_MANY, 'Carro', 'id_usuario'),
			'cartaoCreditos' => array(self::HAS_MANY, 'CartaoCredito', 'id_usuario'),
			'tokens' => array(self::HAS_MANY, 'Token', 'id_usuario'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id_usuario' => 'Id Usuario',
			'uid_facebook' => 'Uid Facebook',
			'email' => 'Email',
			'senha' => 'Senha',
			'nome' => 'Nome',
			'celular' => 'Celular',
			'ativo' => 'Ativo',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 *
	 * Typical usecase:
	 * - Initialize the model fields with values from filter form.
	 * - Execute this method to get CActiveDataProvider instance which will filter
	 * models according to data in model fields.
	 * - Pass data provider to CGridView, CListView or any similar widget.
	 *
	 * @return CActiveDataProvider the data provider that can return the models
	 * based on the search/filter conditions.
	 */
	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id_usuario',$this->id_usuario,true);
		$criteria->compare('uid_facebook',$this->uid_facebook,true);
		$criteria->compare('email',$this->email,true);
		$criteria->compare('senha',$this->senha,true);
		$criteria->compare('nome',$this->nome,true);
		$criteria->compare('celular',$this->celular,true);
		$criteria->compare('ativo',$this->ativo);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Usuario the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
