<?php

/**
 * This is the model class for table "tb_token".
 *
 * The followings are the available columns in table 'tb_token':
 * @property string $id_token
 * @property string $id_cliente
 * @property string $id_usuario
 * @property string $token_acesso
 * @property string $token_atualizacao
 * @property string $tipo_token
 * @property string $data_cadastro
 * @property integer $expira_em
 *
 * The followings are the available model relations:
 * @property Cliente $idCliente
 * @property Usuario $idUsuario
 */
class Token extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Token the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'tb_token';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id_cliente, id_usuario, token_acesso, token_atualizacao, tipo_token, data_cadastro', 'required'),
			array('expira_em', 'numerical', 'integerOnly'=>true),
			array('token_acesso, token_atualizacao, tipo_token', 'length', 'max'=>45),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id_token, id_cliente, id_usuario, token_acesso, token_atualizacao, tipo_token, data_cadastro, expira_em', 'safe', 'on'=>'search'),
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
			'idCliente' => array(self::BELONGS_TO, 'Cliente', 'id_cliente'),
			'idUsuario' => array(self::BELONGS_TO, 'Usuario', 'id_usuario'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id_token' => 'Id Token',
			'id_cliente' => 'Id Cliente',
			'id_usuario' => 'Id Usuario',
			'token_acesso' => 'Token Acesso',
			'token_atualizacao' => 'Token Atualizacao',
			'tipo_token' => 'Tipo Token',
			'data_cadastro' => 'Data Cadastro',
			'expira_em' => 'Expira Em',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id_token',$this->id_token,true);
		$criteria->compare('id_cliente',$this->id_cliente,true);
		$criteria->compare('id_usuario',$this->id_usuario,true);
		$criteria->compare('token_acesso',$this->token_acesso,true);
		$criteria->compare('token_atualizacao',$this->token_atualizacao,true);
		$criteria->compare('tipo_token',$this->tipo_token,true);
		$criteria->compare('data_cadastro',$this->data_cadastro,true);
		$criteria->compare('expira_em',$this->expira_em);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}