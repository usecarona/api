<?php

/**
 * Arquivo da classe Controller.
 *
 * @package api\components
 * @filesource
 */

/**
 * Classe que possui métodos comuns a todos os controllers.
 *
 * @package api\components
 */
class Controller extends CController
{

	/**
	 * Inicializa o controlador.
	 *
	 * Este método é chamado pela aplicação antes da execução do controlador.
	 *
	 * {@source}
	 */
	public function init()
	{
		Yii::app()->language = Yii::app()->request->getPreferredLanguage() ? : 'pt_br';
	}

	/**
	 * Ação que responde ao método HTTP GET.
	 *
	 * {@source}
	 * @throws CHttpException
	 */
	public function actionList()
	{
		throw new CHttpException(501, Yii::t('rest', Yii::app()->response->getStatusCodeMessage(501)));
	}

	/**
	 * Ação que responde ao método HTTP GET.
	 *
	 * {@source}
	 * @throws CHttpException
	 */
	public function actionView()
	{
		throw new CHttpException(501, Yii::t('rest', Yii::app()->response->getStatusCodeMessage(501)));
	}

	/**
	 * Ação que responde ao método HTTP POST.
	 *
	 * {@source}
	 * @throws CHttpException
	 */
	public function actionCreate()
	{
		throw new CHttpException(501, Yii::t('rest', Yii::app()->response->getStatusCodeMessage(501)));
	}

	/**
	 * Ação que responde ao método HTTP PUT.
	 *
	 * {@source}
	 * @throws CHttpException
	 */
	public function actionUpdate()
	{
		throw new CHttpException(501, Yii::t('rest', Yii::app()->response->getStatusCodeMessage(501)));
	}

	/**
	 * Ação que responde ao método HTTP DELETE.
	 *
	 * {@source}
	 * @throws CHttpException
	 */
	public function actionDelete()
	{
		throw new CHttpException(501, Yii::t('rest', Yii::app()->response->getStatusCodeMessage(501)));
	}

}
