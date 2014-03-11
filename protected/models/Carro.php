<?php

/**
 * This is the model class for table "tb_carro".
 *
 * The followings are the available columns in table 'tb_carro':
 * @property string $id_carro
 * @property string $id_usuario
 * @property string $numero_chassi
 * @property string $cpf_proprietario
 * @property string $marca
 * @property string $modelo
 * @property string $ano
 * @property string $placa
 * @property integer $quantidade_passageiros
 * @property integer $quantidade_portas
 * @property string $cor
 * @property integer $wifi
 * @property integer $ar_condicionado
 * @property integer $som
 * @property integer $dvd
 * @property integer $airbag
 * @property integer $ativo
 * @property integer $liberado
 *
 * The followings are the available model relations:
 * @property Usuario $idUsuario
 */
class Carro extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'tb_carro';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id_usuario, numero_chassi, cpf_proprietario, marca, modelo, ano, placa, cor', 'required'),
			array('quantidade_passageiros, quantidade_portas, wifi, ar_condicionado, som, dvd, airbag, ativo, liberado', 'numerical', 'integerOnly'=>true),
			array('id_usuario', 'length', 'max'=>20),
			array('numero_chassi, marca, modelo, placa, cor', 'length', 'max'=>45),
			array('cpf_proprietario', 'length', 'max'=>11),
			array('ano', 'length', 'max'=>4),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id_carro, id_usuario, numero_chassi, cpf_proprietario, marca, modelo, ano, placa, quantidade_passageiros, quantidade_portas, cor, wifi, ar_condicionado, som, dvd, airbag, ativo, liberado', 'safe', 'on'=>'search'),
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
			'idUsuario' => array(self::BELONGS_TO, 'Usuario', 'id_usuario'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id_carro' => 'Id Carro',
			'id_usuario' => 'Id Usuario',
			'numero_chassi' => 'Numero Chassi',
			'cpf_proprietario' => 'Cpf Proprietario',
			'marca' => 'Marca',
			'modelo' => 'Modelo',
			'ano' => 'Ano',
			'placa' => 'Placa',
			'quantidade_passageiros' => 'Quantidade Passageiros',
			'quantidade_portas' => 'Quantidade Portas',
			'cor' => 'Cor',
			'wifi' => 'Wifi',
			'ar_condicionado' => 'Ar Condicionado',
			'som' => 'Som',
			'dvd' => 'Dvd',
			'airbag' => 'Airbag',
			'ativo' => 'Ativo',
			'liberado' => 'Liberado',
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

		$criteria->compare('id_carro',$this->id_carro,true);
		$criteria->compare('id_usuario',$this->id_usuario,true);
		$criteria->compare('numero_chassi',$this->numero_chassi,true);
		$criteria->compare('cpf_proprietario',$this->cpf_proprietario,true);
		$criteria->compare('marca',$this->marca,true);
		$criteria->compare('modelo',$this->modelo,true);
		$criteria->compare('ano',$this->ano,true);
		$criteria->compare('placa',$this->placa,true);
		$criteria->compare('quantidade_passageiros',$this->quantidade_passageiros);
		$criteria->compare('quantidade_portas',$this->quantidade_portas);
		$criteria->compare('cor',$this->cor,true);
		$criteria->compare('wifi',$this->wifi);
		$criteria->compare('ar_condicionado',$this->ar_condicionado);
		$criteria->compare('som',$this->som);
		$criteria->compare('dvd',$this->dvd);
		$criteria->compare('airbag',$this->airbag);
		$criteria->compare('ativo',$this->ativo);
		$criteria->compare('liberado',$this->liberado);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Carro the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
