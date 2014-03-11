<?php

/**
 * This is the model class for table "tb_cliente".
 *
 * The followings are the available columns in table 'tb_cliente':
 * @property string $id_cliente
 * @property string $tipo_cliente
 * @property string $senha
 * @property string $nome
 * @property string $descricao
 * @property string $website
 * @property string $logo_image
 * @property string $uri_redirecionamento
 * @property integer $ativo
 *
 * The followings are the available model relations:
 * @property Token[] $tokens
 */
class Cliente extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'tb_cliente';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('senha, nome', 'required'),
			array('ativo', 'numerical', 'integerOnly'=>true),
			array('tipo_cliente, senha, nome, website', 'length', 'max'=>45),
			array('descricao', 'length', 'max'=>255),
			array('uri_redirecionamento', 'length', 'max'=>100),
			array('logo_image', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id_cliente, tipo_cliente, senha, nome, descricao, website, logo_image, uri_redirecionamento, ativo', 'safe', 'on'=>'search'),
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
			'tokens' => array(self::HAS_MANY, 'Token', 'id_cliente'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id_cliente' => 'Id Cliente',
			'tipo_cliente' => 'Tipo Cliente',
			'senha' => 'Senha',
			'nome' => 'Nome',
			'descricao' => 'Descricao',
			'website' => 'Website',
			'logo_image' => 'Logo Image',
			'uri_redirecionamento' => 'Uri Redirecionamento',
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

		$criteria->compare('id_cliente',$this->id_cliente,true);
		$criteria->compare('tipo_cliente',$this->tipo_cliente,true);
		$criteria->compare('senha',$this->senha,true);
		$criteria->compare('nome',$this->nome,true);
		$criteria->compare('descricao',$this->descricao,true);
		$criteria->compare('website',$this->website,true);
		$criteria->compare('logo_image',$this->logo_image,true);
		$criteria->compare('uri_redirecionamento',$this->uri_redirecionamento,true);
		$criteria->compare('ativo',$this->ativo);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Cliente the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
