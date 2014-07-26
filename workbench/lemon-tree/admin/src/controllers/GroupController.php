<?php namespace LemonTree;

class GroupController extends BaseController {

	public function postSaveElementPermissions(Group $group)
	{
		$scope = array();

		$loggedUser = \Sentry::getUser();

		if ( ! $loggedUser->hasAccess('admin')) {
			$scope['redirect'] = \URL::route('admin');
			return json_encode($scope);
		}

		if ($loggedUser->inGroup($group)) {
			$scope['redirect'] = \URL::route('admin.users');
			return json_encode($scope);
		}

		$input = \Input::all();

		$defaultGroupPermission = $group->default_permission
			? $group->default_permission
			: 'deny';

		$groupItemPermissions = $group->itemPermissions;

		$groupItemPermissionMap = array();

		foreach ($groupItemPermissions as $groupItemPermission) {
			$class = $groupItemPermission->class;
			$groupItemPermissionMap[$class] = $groupItemPermission;
		}

		$groupElementPermissions = $group->elementPermissions;

		$groupElementPermissionMap = array();

		foreach ($groupElementPermissions as $groupElementPermission) {
			$classId = $groupElementPermission->class_id;
			$groupElementPermissionMap[$classId] = $groupElementPermission;
		}

		try {

			foreach ($input as $key => $value) {

				list($class, $id) = explode('_', $key);

				if ( ! $class || ! $id) continue;

				$classId = $class.Element::ID_SEPARATOR.$id;

				$defaultPermission = isset($groupItemPermissionMap[$class])
					? $groupItemPermissionMap[$class]->permission
					: $defaultGroupPermission;

				if (isset($groupElementPermissionMap[$classId])) {

					$groupElementPermission =
						$groupElementPermissionMap[$classId];

					$permission = $groupElementPermission->permission;

					if ($defaultPermission == $value) {
						$groupElementPermission->delete();
					} elseif ($permission != $value) {
						$groupElementPermission->permission = $value;
						$groupElementPermission->save();
					}

				} elseif ($defaultPermission != $value) {

					$groupElementPermission = new GroupElementPermission;

					$groupElementPermission->group_id = $group->id;
					$groupElementPermission->class_id = $classId;
					$groupElementPermission->permission = $value;

					$groupElementPermission->save();

				}

			}

			UserAction::log(
				UserActionType::ACTION_TYPE_SAVE_ELEMENT_PERMISSIONS_ID,
				'ID '.$group->id.' ('.$group->name.')'
			);

			$scope['status'] = 'ok';

		} catch (\Exception $e) {
			$scope['message'] = $e->getMessage().$e->getTraceAsString();
		}

		return json_encode($scope);
	}

	public function getEditElementPermissions(Group $group)
	{
		$scope = array();

		$loggedUser = \Sentry::getUser();

		if ( ! $loggedUser->hasAccess('admin')) {
			return \Redirect::route('admin');
		}

		if ($loggedUser->inGroup($group)) {
			return \Redirect::route('admin.users');
		}

		$scope['currentTitle'] = $group->name.' - Доступ к элементам';
		$scope['currentTabTitle'] = $group->name;

		$scope = CommonFilter::apply($scope);

		$defaultGroupPermission = $group->default_permission
			? $group->default_permission
			: 'deny';

		$groupItemPermissions = $group->itemPermissions;

		$groupItemPermissionMap = array();

		foreach ($groupItemPermissions as $groupItemPermission) {
			$class = $groupItemPermission->class;
			$permission = $groupItemPermission->permission;
			$groupItemPermissionMap[$class] = $permission;
		}

		$groupElementPermissions = $group->elementPermissions;

		$groupElementPermissionMap = array();

		foreach ($groupElementPermissions as $groupElementPermission) {
			$classId = $groupElementPermission->class_id;
			$permission = $groupElementPermission->permission;
			$groupElementPermissionMap[$classId] = $permission;
		}

		$site = \App::make('site');

		$itemList = $site->getItemList();

		$itemElementList = array();

		foreach ($itemList as $itemName => $item) {

			if ( ! $item->getElementPermissions()) {
				unset($itemList[$itemName]);
				continue;
			}

			$elementList =
				$item->getClass()->
				orderBy($item->getMainProperty())->
				cacheTags($itemName)->
				rememberForever()->
				get();;

			if (sizeof ($elementList)) {
				$itemElementList[$itemName] = $elementList;
			} else {
				unset($itemList[$itemName]);
			}

		}

		$scope['group'] = $group;
		$scope['itemList'] = $itemList;
		$scope['itemElementList'] = $itemElementList;
		$scope['groupElementPermissionMap'] = $groupElementPermissionMap;
		$scope['groupItemPermissionMap'] = $groupItemPermissionMap;
		$scope['defaultGroupPermission'] = $defaultGroupPermission;

		return \View::make('admin::groupElements', $scope);
	}

	public function postSaveItemPermissions(Group $group)
	{
		$scope = array();

		$loggedUser = \Sentry::getUser();

		if ( ! $loggedUser->hasAccess('admin')) {
			$scope['redirect'] = \URL::route('admin');
			return json_encode($scope);
		}

		if ($loggedUser->inGroup($group)) {
			$scope['redirect'] = \URL::route('admin.users');
			return json_encode($scope);
		}

		$input = \Input::all();

		$site = \App::make('site');

		$itemList = $site->getItemList();

		foreach ($itemList as $item) {
			$rules = array(
				$item->getName() => 'required|in:deny,view,update,delete',
			);
			$messages = array(
				$item->getName().'.required' => $item->getName(),
				$item->getName().'.in' => $item->getName(),
			);
		}

		$validator = \Validator::make($input, $rules, $messages);

		if ($validator->fails()) {
			$messages = $validator->messages();
			$scope['error'] = $messages->all();
			return json_encode($scope);
		}

		$defaultGroupPermission = $group->default_permission
			? $group->default_permission
			: 'deny';

		$groupItemPermissions = $group->itemPermissions;

		$groupItemPermissionMap = array();

		foreach ($groupItemPermissions as $groupItemPermission) {
			$class = $groupItemPermission->class;
			$groupItemPermissionMap[$class] = $groupItemPermission;
		}

		try {

			foreach ($itemList as $item) {

				$class = $item->getName();

				if (isset($groupItemPermissionMap[$class])) {

					$groupItemPermission = $groupItemPermissionMap[$class];

					$permission = $groupItemPermission->permission;

					if ($defaultGroupPermission == $input[$class]) {
						$groupItemPermission->delete();
					} elseif ($permission != $input[$class]) {
						$groupItemPermission->permission = $input[$class];
						$groupItemPermission->save();
					}

				} elseif ($defaultGroupPermission != $input[$class]) {

					$groupItemPermission = new GroupItemPermission;

					$groupItemPermission->group_id = $group->id;
					$groupItemPermission->class = $class;
					$groupItemPermission->permission = $input[$class];

					$groupItemPermission->save();

				}

			}

			UserAction::log(
				UserActionType::ACTION_TYPE_SAVE_ITEM_PERMISSIONS_ID,
				'ID '.$group->id.' ('.$group->name.')'
			);

			$scope['status'] = 'ok';

		} catch (\Exception $e) {
			$scope['message'] = $e->getMessage();
		}

		return json_encode($scope);
	}

	public function getEditItemPermissions(Group $group)
	{
		$scope = array();

		$loggedUser = \Sentry::getUser();

		if ( ! $loggedUser->hasAccess('admin')) {
			return \Redirect::route('admin');
		}

		if ($loggedUser->inGroup($group)) {
			return \Redirect::route('admin.users');
		}

		$scope['currentTitle'] = $group->name.' - Доступ по умолчанию';
		$scope['currentTabTitle'] = $group->name;

		$scope = CommonFilter::apply($scope);

		$site = \App::make('site');

		$itemList = $site->getItemList();

		$defaultGroupPermission = $group->default_permission
			? $group->default_permission
			: 'deny';

		$groupItemPermissions = $group->itemPermissions;

		$groupItemPermissionMap = array();

		foreach ($groupItemPermissions as $groupItemPermission) {
			$class = $groupItemPermission->class;
			$permission = $groupItemPermission->permission;
			$groupItemPermissionMap[$class] = $permission;
		}

		$scope['group'] = $group;
		$scope['itemList'] = $itemList;
		$scope['groupItemPermissionMap'] = $groupItemPermissionMap;
		$scope['defaultGroupPermission'] = $defaultGroupPermission;

		return \View::make('admin::groupItems', $scope);
	}

	public function getDelete(Group $group)
	{
		$scope = array();

		$loggedUser = \Sentry::getUser();

		if ( ! $loggedUser->hasAccess('admin')) {
			return \Redirect::route('admin');
		}

		if ($loggedUser->inGroup($group)) {
			return \Redirect::route('admin.users');
		}

		try {
			$group->delete();
			UserAction::log(
				UserActionType::ACTION_TYPE_DROP_GROUP_ID,
				'ID '.$group->id.' ('.$group->name.')'
			);
		} catch (\Exception $e) {}

		return \Redirect::route('admin.users');
	}

	public function postAdd()
	{
		$scope = array();

		$loggedUser = \Sentry::getUser();

		if ( ! $loggedUser->hasAccess('admin')) {
			$scope['redirect'] = \URL::route('admin');
			return json_encode($scope);
		}

		$input = \Input::all();

		$rules = array(
			'name' => 'required',
			'default_permission' => 'required|in:deny,view,update,delete',
		);

		$messages = array(
			'name.required' => 'name',
			'default_permission.required' => 'default_permission',
			'default_permission.in' => 'default_permission',
		);

		$validator = \Validator::make($input, $rules, $messages);

		if ($validator->fails()) {
			$messages = $validator->messages();
			$scope['error'] = $messages->all();
			return json_encode($scope);
		}

		$group = new Group;

		$group->name = $input['name'];

		$group->default_permission = $input['default_permission'];

		$permissions = $group->getPermissions();
		$permissions['admin'] = isset($input['admin']) ? 1 : 0;
		$group->permissions = $permissions;

		try {
			$group->save();
			UserAction::log(
				UserActionType::ACTION_TYPE_ADD_GROUP_ID,
				'ID '.$group->id.' ('.$group->name.')'
			);
			$scope['status'] = 'ok';
			$scope['redirect'] = \URL::route('admin.users');
		} catch (\Exception $e) {
			$scope['message'] = $e->getMessage();
		}

		return json_encode($scope);
	}

	public function postSave(Group $group)
	{
		$scope = array();

		$loggedUser = \Sentry::getUser();

		if ( ! $loggedUser->hasAccess('admin')) {
			$scope['redirect'] = \URL::route('admin');
			return json_encode($scope);
		}

		if ($loggedUser->inGroup($group)) {
			$scope['redirect'] = \URL::route('admin.users');
			return json_encode($scope);
		}

		$input = \Input::all();

		$rules = array(
			'name' => 'required',
			'default_permission' => 'required|in:deny,view,update,delete',
		);

		$messages = array(
			'name.required' => 'name',
			'default_permission.required' => 'default_permission',
			'default_permission.in' => 'default_permission',
		);

		$validator = \Validator::make($input, $rules, $messages);

		if ($validator->fails()) {
			$messages = $validator->messages();
			$scope['error'] = $messages->all();
			return json_encode($scope);
		}

		$group->name = $input['name'];

		$group->default_permission = $input['default_permission'];

		$permissions = $group->getPermissions();
		$permissions['admin'] = isset($input['admin']) ? 1 : 0;
		$group->permissions = $permissions;

		try {
			$group->save();
			UserAction::log(
				UserActionType::ACTION_TYPE_SAVE_GROUP_ID,
				'ID '.$group->id.' ('.$group->name.')'
			);
			$scope['status'] = 'ok';
		} catch (\Exception $e) {
			$scope['message'] = $e->getMessage();
		}

		return json_encode($scope);
	}

	public function getCreate()
	{
		$scope = array();

		$loggedUser = \Sentry::getUser();

		if ( ! $loggedUser->hasAccess('admin')) {
			return \Redirect::route('admin');
		}

		$scope['currentTitle'] = 'Добавление группы';
		$scope['currentTabTitle'] = 'Добавление группы';

		$scope = CommonFilter::apply($scope);

		$group = new Group;

		$scope['group'] = $group;

		return \View::make('admin::group', $scope);
	}

	public function getEdit(Group $group)
	{
		$scope = array();

		$loggedUser = \Sentry::getUser();

		if ( ! $loggedUser->hasAccess('admin')) {
			return \Redirect::route('admin');
		}

		if ($loggedUser->inGroup($group)) {
			return \Redirect::route('admin.users');
		}

		$scope['currentTitle'] = $group->name.' - Редактирование группы';
		$scope['currentTabTitle'] = $group->name;

		$scope = CommonFilter::apply($scope);

		$scope['group'] = $group;

		return \View::make('admin::group', $scope);
	}

}