<?php

/**
 * This is the model class for table "tb_cartao_credito".
 *
 * The followings are the available columns in table 'tb_cartao_credito':
 * @property string $id_cartao_credito
 * @property string $id_usuario
 * @property string $numero_cartao
 * @property string $nome_titular
 * @property string $mes_validade
 * @property string $ano_validade
 * @property string $codigo_seguranca
 * @property string $cpf_titutar
 * @property string $telefone_titular
 * @property boolean $ativo
 * @property boolean $liberado
 *
 * The followings are the available model relations:
 * @property Usuario $idUsuario
 */
class CartaoCredito extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return CartaoCredito the static model class
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
		return 'tb_cartao_credito';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id_usuario, numero_cartao, nome_titular, mes_validade, ano_validade, codigo_seguranca, cpf_titutar, telefone_titular', 'required'),
			array('numero_cartao, nome_titular', 'length', 'max'=>45),
			array('mes_validade', 'length', 'max'=>2),
			array('ano_validade, codigo_seguranca', 'length', 'max'=>4),
			array('cpf_titutar', 'length', 'max'=>11),
			array('telefone_titular', 'length', 'max'=>15),
			array('ativo, liberado', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id_cartao_credito, id_usuario, numero_cartao, nome_titular, mes_validade, ano_validade, codigo_seguranca, cpf_titutar, telefone_titular, ativo, liberado', 'safe', 'on'=>'search'),
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
			'id_cartao_credito' => 'Id Cartao Credito',
			'id_usuario' => 'Id Usuario',
			'numero_cartao' => 'Numero Cartao',
			'nome_titular' => 'Nome Titular',
			'mes_validade' => 'Mes Validade',
			'ano_validade' => 'Ano Validade',
			'codigo_seguranca' => 'Codigo Seguranca',
			'cpf_titutar' => 'Cpf Titutar',
			'telefone_titular' => 'Telefone Titular',
			'ativo' => 'Ativo',
			'liberado' => 'Liberado',
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

		$criteria->compare('id_cartao_credito',$this->id_cartao_credito,true);
		$criteria->compare('id_usuario',$this->id_usuario,true);
		$criteria->compare('numero_cartao',$this->numero_cartao,true);
		$criteria->compare('nome_titular',$this->nome_titular,true);
		$criteria->compare('mes_validade',$this->mes_validade,true);
		$criteria->compare('ano_validade',$this->ano_validade,true);
		$criteria->compare('codigo_seguranca',$this->codigo_seguranca,true);
		$criteria->compare('cpf_titutar',$this->cpf_titutar,true);
		$criteria->compare('telefone_titular',$this->telefone_titular,true);
		$criteria->compare('ativo',$this->ativo);
		$criteria->compare('liberado',$this->liberado);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}