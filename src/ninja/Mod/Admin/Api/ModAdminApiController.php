<?php

namespace ninja;

/**
 * Class ModAdminApiController - the index action shall take care of it all indeed
 *
 * @package ninja
 */
class ModAdminApiController extends \ModAdminController {

	/**
	 * I validate model classname - default implementation just checkes if class exists. you can filter available
	 * 		models by overwriting this class
	 * @param string $classname
	 * @param string $actionName - action to validate for
	 * @return bool
	 */
	protected function _validateClass($classname, $actionName) {
		return class_exists($classname);
	}

	/**
	 * I validate fieldnames to be served. current implementation only checks if each value is in model's field names
	 * 		I only use array_diff but that shall be fine
	 * @param string $fieldnames
	 * @param string $modelClassname
	 * @param string $actionName - action to validate for
	 * @throws \HttpException
	 */
	protected function _validateFieldnames($fieldnames, $modelClassname, $actionName) {
		$schemaFields = $modelClassname::getFieldnames();
		$diffFields = array_diff($fieldnames, $schemaFields);
		if (count($diffFields)) {
			return false;
		}
		return true;
	}

	/**
	 * I process special params (and remove them from $params)
	 * @param $params
	 * @param $modelClassname
	 * @return \ModelFinderConstraints
	 * @throws \HttpException
	 */
	protected function _processSpecialParams(&$params, $modelClassname) {

		$start = 0;
		if (isset($params['_start'])) {
			$start = intval($params['_start']);
			if ($start<0) {
				throw new \HttpException(\Response::HTTP_BAD_REQUEST);
			}
			unset($params['_start']);
		}
		$limit = 0;
		if (isset($params['_limit'])) {
			$limit = intval($params['_limit']);
			if ($limit<0) {
				throw new \HttpException(\Response::HTTP_BAD_REQUEST);
			}
			unset($params['_limit']);
		}

		$fields = [];
		if (isset($params['_fields'])) {
			$fields = $params['_fields'];
			if (is_string($fields)) {
				$fields = explode(',', $fields);
			}
			if (!$this->_validateFieldnames($fields, $modelClassname, 'actionIndex')) {
				throw new \HttpException(\Response::HTTP_BAD_REQUEST);
			}
			unset($params['_fields']);
		}

		return new \ModelFinderConstraints($start, $limit, $fields);

	}

	/**
	 * I return some static data for now
	 * @param mixed[] $params
	 * @return null|\Response
	 */
	public function actionIndex($params = null) {

		// get model to be served
		$modelName = $this->_Request->getNextRemainingUriPart();
		$modelClassname = 'Mod' . $modelName . 'Model';
		if (($modelName === false) || !$this->_validateClass($modelClassname, 'actionIndex')) {
			// @todo make some more consolidated notfoundexception
			throw new \HttpException(\Response::HTTP_NOT_FOUND);
		}
		$this->_Request->shiftCntUriParts(1);

		$Constraints = $this->_processSpecialParams($params, $modelClassname);

		$Collection = \ModPageModel::Finder()->findAll($Constraints);
		$Response = \ApiResponse::fromCollection(true, $Collection);
//		$data = $Collection->getData();
//		foreach ($data as &$eachData) {
//			...
//		}
//		$Response = \ApiResponse::build(true, $data);
		$Response->setIsFinal(true);
		return $Response;
	}

	/**
	 * This action wraps actionIndex and adds meta info to results
	 * @param null $params
	 * @return null|\Response
	 */
	public function actionWithmeta($params = null) {
		$ret = $this->actionIndex($params);
		$ret->getContent()->meta = \SchemaManager::getSchema('ModPageModel')->toMeta();
		return $ret;
	}

}
