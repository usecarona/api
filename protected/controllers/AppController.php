<?php

/**
 * AppController class file.
 *
 * @package api\controllers
 * @filesource
 */

/**
 * Gerencia todas as mensagens de erro dos sistemas.
 */
class AppController extends Controller
{

	/**
	 * Raiz da api.
	 * @throws CHttpException
	 */
	public function actionIndex()
	{
		$response = array('message' => Yii::t('app', 'Welcome to {appName}.', array('{appName}' => Yii::app()->name)));
		Yii::app()->response->sendResponse($response);
	}

	/**
	 * Manipula as exceções da api.
	 */
	public function actionError()
	{
		$error = array(
			'code' => Yii::app()->errorHandler->error['code'],
			'message' => Yii::app()->errorHandler->error['message'],
			'type' => Yii::app()->errorHandler->error['type']
		);

		if (!$error) {
			$error = array('message' => Yii::t('app', 'An error has occurred in your request.'));
		}

		Yii::app()->response->sendResponse($error);
	}

}
