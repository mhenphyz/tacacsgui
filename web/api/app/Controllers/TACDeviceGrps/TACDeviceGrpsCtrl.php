<?php

namespace tgui\Controllers\TACDeviceGrps;

use tgui\Models\TACDeviceGrps;
use tgui\Models\TACDevices;
use tgui\Controllers\Controller;
use Respect\Validation\Validator as v;

class TACDeviceGrpsCtrl extends Controller
{
################################################
########	Add New Device Group	###############START###########	
	#########	GET Add New Device Group	#########
	public function getDeviceGroupAdd($req,$res)
	{
		//INITIAL CODE////START//
		$data=array();
		$data=$this->initialData([
			'type' => 'get',
			'object' => 'device group',
			'action' => 'add',
		]);
		#check error#
		if ($_SESSION['error']['status']){
			$data['error']=$_SESSION['error'];
			return $res -> withStatus(401) -> write(json_encode($data));
		}
		//INITIAL CODE////END//
		
		return $res -> withStatus(200) -> write(json_encode($data));
	}
	
	#########	POST Add New Device	Group#########
	public function postDeviceGroupAdd($req,$res)
	{
		//INITIAL CODE////START//
		$data=array();
		$data=$this->initialData([
			'type' => 'post',
			'object' => 'device group',
			'action' => 'add',
		]);
		#check error#
		if ($_SESSION['error']['status']){
			$data['error']=$_SESSION['error'];
			return $res -> withStatus(401) -> write(json_encode($data));
		}
		//INITIAL CODE////END//
		//CHECK ACCESS TO THAT FUNCTION//START//
		if(!$this->checkAccess(3))
		{
			return $res -> withStatus(403) -> write(json_encode($data));
		}
		//CHECK ACCESS TO THAT FUNCTION//END//
		
		$validation = $this->validator->validate($req, [
			'name' => v::noWhitespace()->notEmpty()->deviceGroupAvailable(),
			'enable' => v::noWhitespace(),
			'enable_flag' => v::noWhitespace()->numeric(),
			'key' => v::noWhitespace(),
		]);
		
		if ($validation->failed()){
			$data['error']['status']=true;
			$data['error']['validation']=$validation->error_messages;
			return $res -> withStatus(200) -> write(json_encode($data));
		}
		
		$data['default_flag'] = ($req->getParam('default_flag') === true OR $req->getParam('default_flag') === 'true') ? 1 : 0;
		
		if ($data['default_flag']) TACDeviceGrps::where([['default_flag', '=', 1]])->update(['default_flag' => 0]);
		
		$data['enable']=$req->getParam('enable');
		
		if (isset($data['enable']) AND ($req->getParam('enable_encrypt') === true OR $req->getParam('enable_encrypt') === 'true'))
		{
			if ($req->getParam('enable_flag') == 1)
			{
				$data['enable']=trim(shell_exec('openssl passwd -1 '.$data['enable']));
			} elseif ($req->getParam('enable_flag') == 2)
			{
				$data['enable']=trim(shell_exec('openssl passwd crypt '.$data['enable']));
			} 
		} 
		
		$deviceGroup = TACDeviceGrps::create([
			'name' => $req->getParam('name'),
			'key' => $req->getParam('key'),
			'enable' => $data['enable'],
			'enable_flag' => $req->getParam('enable_flag'),
			'banner_welcome' => $req->getParam('banner_welcome'),
			'banner_motd' => $req->getParam('banner_motd'),
			'banner_failed' => $req->getParam('banner_failed'),
			'default_flag' => $data['default_flag'],
			'manual' => $req->getParam('manual'),
		]);
		
		//$this->auth->check();
		$data['deviceGroup']=$deviceGroup; 
		
		$data['changeConfiguration']=$this->changeConfigurationFlag(['unset' => 0]);
		
		$logEntry=array('action' => 'add', 'objectName' => $deviceGroup['name'], 'objectId' => $deviceGroup['id'], 'section' => 'tacacs device groups', 'message' => 202);
		$data['logging']=$this->APILoggingCtrl->makeLogEntry($logEntry);
		
		return $res -> withStatus(200) -> write(json_encode($data));
	}
########	Add New Device Group	###############END###########	
################################################
########	Edit Device	Group###############START###########
	#########	GET Edit Device	Group#########
	public function getDeviceGroupEdit($req,$res)
	{
		//INITIAL CODE////START//
		$data=array();
		$data=$this->initialData([
			'type' => 'get',
			'object' => 'device group',
			'action' => 'edit',
		]);
		#check error#
		if ($_SESSION['error']['status']){
			$data['error']=$_SESSION['error'];
			return $res -> withStatus(401) -> write(json_encode($data));
		}
		//INITIAL CODE////END//
		
		$data['group']=TACDeviceGrps::select('id','name','key','enable','enable_flag', 'default_flag','banner_welcome','banner_motd','banner_failed','manual','created_at', 'updated_at')->
			where([['id','=',$req->getParam('id')],['name','=',$req->getParam('name')]])->
			first();
		
		return $res -> withStatus(200) -> write(json_encode($data));
	}
	
	#########	POST Edit Device Group	#########
	public function postDeviceGroupEdit($req,$res)
	{
		//INITIAL CODE////START//
		$data=array();
		$data=$this->initialData([
			'type' => 'post',
			'object' => 'device group',
			'action' => 'edit',
		]);
		#check error#
		if ($_SESSION['error']['status']){
			$data['error']=$_SESSION['error'];
			return $res -> withStatus(401) -> write(json_encode($data));
		}
		//INITIAL CODE////END//
		//CHECK ACCESS TO THAT FUNCTION//START//
		if(!$this->checkAccess(3))
		{
			return $res -> withStatus(403) -> write(json_encode($data));
		}
		//CHECK ACCESS TO THAT FUNCTION//END//
		
		$validation = $this->validator->validate($req, [
			'name' => v::noWhitespace()->notEmpty()->deviceGroupAvailable($req->getParam('id')),
			'enable' => v::noWhitespace(),
			'enable_flag' => v::noWhitespace()->numeric(),
			'key' => v::noWhitespace(),
		]);
		
		if ($validation->failed()){
			$data['error']['status']=true;
			$data['error']['validation']=$validation->error_messages;
			return $res -> withStatus(200) -> write(json_encode($data));
		}
		
		$data['default_flag'] = ($req->getParam('default_flag') === true OR $req->getParam('default_flag') === 'true') ? 1 : 0;
		
		if ($data['default_flag']) TACDeviceGrps::where([['default_flag', '=', 1]])->update(['default_flag' => 0]);
		
		$data['enable']=$req->getParam('enable');
		
		if (isset($data['enable']) AND ($req->getParam('enable_encrypt') === true OR $req->getParam('enable_encrypt') === 'true'))
		{
			if ($req->getParam('enable_flag') == 1)
			{
				$data['enable']=trim(shell_exec('openssl passwd -1 '.$data['enable']));
			} elseif ($req->getParam('enable_flag') == 2)
			{
				$data['enable']=trim(shell_exec('openssl passwd crypt '.$data['enable']));
			} 
		} 
		
		$data['group_update']=TACDeviceGrps::where([['id','=',$req->getParam('id')],['name','=',$req->getParam('name_old')]])->
			update([
				'name' => $req->getParam('name'), 
				'default_flag' => $data['default_flag'], 
				'key' => $req->getParam('key'),
				'enable' => $data['enable'],
				'enable_flag' => $req->getParam('enable_flag'),
				'banner_welcome' => $req->getParam('banner_welcome'),
				'banner_motd' => $req->getParam('banner_motd'),
				'banner_failed' => $req->getParam('banner_failed'),
				'manual' => $req->getParam('manual'),
			]);
		
		$data['changeConfiguration']=$this->changeConfigurationFlag(['unset' => 0]);
		
		$logEntry=array('action' => 'edit', 'objectName' => $req->getParam('name'), 'objectId' => $req->getParam('id'), 'section' => 'tacacs device groups', 'message' => 302);
		$data['logging']=$this->APILoggingCtrl->makeLogEntry($logEntry);
		
		return $res -> withStatus(200) -> write(json_encode($data));
	}
########	Edit Device	Group###############END###########
################################################
########	Delete Device Group	###############START###########
	#########	GET Delete Device Group	#########
	public function getDeviceGroupDelete($req,$res)
	{
		//INITIAL CODE////START//
		$data=array();
		$data=$this->initialData([
			'type' => 'get',
			'object' => 'device group',
			'action' => 'delete',
		]);
		#check error#
		if ($_SESSION['error']['status']){
			$data['error']=$_SESSION['error'];
			return $res -> withStatus(401) -> write(json_encode($data));
		}
		//INITIAL CODE////END//
		
		return $res -> withStatus(200) -> write(json_encode($data));
	}
	
	#########	POST Delete Device	Group#########
	public function postDeviceGroupDelete($req,$res)
	{
		//INITIAL CODE////START//
		$data=array();
		$data=$this->initialData([
			'type' => 'post',
			'object' => 'device group',
			'action' => 'delete',
		]);
		#check error#
		if ($_SESSION['error']['status']){
			$data['error']=$_SESSION['error'];
			return $res -> withStatus(401) -> write(json_encode($data));
		}
		//INITIAL CODE////END//
		//CHECK ACCESS TO THAT FUNCTION//START//
		if(!$this->checkAccess(3))
		{
			return $res -> withStatus(403) -> write(json_encode($data));
		}
		//CHECK ACCESS TO THAT FUNCTION//END//
		
		if (TACDeviceGrps::select()->where([['id','=',$req->getParam('id')],['name','=',$req->getParam('name')],['default_flag','=',1]])->count()) {
			$data['error']['status']=true;
			$data['error']['reason']='default_flag';
			$data['error']['message']="You can't delete the default group";
			return $res -> withStatus(200) -> write(json_encode($data));
		}
		
		if (TACDevices::where([['group','=',$req->getParam('id')]])->count())
		{
			$defaultGroup=TACDeviceGrps::select('id')->where([['default_flag','=',1]])->first();
			TACDevices::where([['group','=',$req->getParam('id')]])->update([
				'group' => $defaultGroup['id'],
			]);
		}
		
		$data['deleteGroup']=TACDeviceGrps::where([
			['id','=',$req->getParam('id')],
			['name','=',$req->getParam('name')],
		])->delete();
		$data['id']=$req->getParam('id');
		$data['name']=$req->getParam('name');
		
		$data['changeConfiguration']=$this->changeConfigurationFlag(['unset' => 0]);
		
		$logEntry=array('action' => 'delete', 'objectName' => $req->getParam('name'), 'objectId' => $req->getParam('id'), 'section' => 'tacacs device groups', 'message' => 402);
		$data['logging']=$this->APILoggingCtrl->makeLogEntry($logEntry);
		
		return $res -> withStatus(200) -> write(json_encode($data));
	}
########	Delete Device Group	###############END###########
################################################
########	Device Groups Datatables ###############START###########
	#########	POST Device Groups Datatables	#########
	public function postDeviceGroupsDatatables($req,$res)
	{
		//INITIAL CODE////START//
		$data=array();
		$data=$this->initialData([
			'type' => 'post',
			'object' => 'device group',
			'action' => 'datatables',
		]);
		#check error#
		if ($_SESSION['error']['status']){
			$data['error']=$_SESSION['error'];
			return $res -> withStatus(401) -> write(json_encode($data));
		}
		//INITIAL CODE////END//
		
		unset($data['error']);//BEACAUSE DATATABLES USES THAT VARIABLE//
		
		$params=$req->getParams(); //Get ALL parameters form Datatables
		
		$columns = array( 
		// datatable column index  => database column name
			0 => 'id', 
			1 => 'name',
			2 => 'default_flag',
			3 => 'key',
			4 => 'enable',
		); //Array of all columnes that will used
		
		//Get temp data for Datatables with Fliter and some other parameters
		$tempData = TACDeviceGrps::select($columns)->
			when($params['columns'][0]['search']['value'], 
				function($query) use ($params,$columns)
				{
					return $query->where($columns[0],'LIKE','%'.$params['columns'][0]['search']['value'].'%');
				}) ->
			when($params['columns'][1]['search']['value'], 
				function($query) use ($params,$columns)
				{
					return $query->where($columns[1],'LIKE','%'.$params['columns'][1]['search']['value'].'%');
				}) ->
			orderBy($columns[$params['order'][0]['column']],$params['order'][0]['dir'])->
			take($params['length'])->
			offset($params['start'])->
			get()->toArray();
		//Creating correct array of answer to Datatables 
		$data['data']=array();
		
		foreach($tempData as $deviceGroup){
			$buttons='<button class="btn btn-warning btn-xs btn-flat" onclick="editDeviceGroup(\''.$deviceGroup['id'].'\',\''.$deviceGroup['name'].'\')">Edit</button> <button class="btn btn-danger btn-xs btn-flat" onclick="deleteDeviceGroup(\''.$deviceGroup['id'].'\',\''.$deviceGroup['name'].'\')">Del</button>';
			$deviceGroup['buttons']=$buttons;
			$deviceGroup['key']= ($deviceGroup['key'] != '') ? true : false; 
			$deviceGroup['enable']=($deviceGroup['enable'] != '') ? true : false; 
			if($deviceGroup['default_flag']){array_unshift($data['data'],$deviceGroup);}
			else array_push($data['data'],$deviceGroup);
		}
		//Some additional parameters for Datatables
		$data['draw']=intval( $params['draw'] );
		$data['recordsTotal'] = TACDeviceGrps::count();
		$data['recordsFiltered'] = TACDeviceGrps::select($columns)->
			when($params['columns'][0]['search']['value'], 
				function($query) use ($params,$columns)
				{
					return $query->where($columns[0],'LIKE','%'.$params['columns'][0]['search']['value'].'%');
				}) ->
			when($params['columns'][1]['search']['value'], 
				function($query) use ($params,$columns)
				{
					return $query->where($columns[1],'LIKE','%'.$params['columns'][1]['search']['value'].'%');
				}) ->
				count();
		
		return $res -> withStatus(200) -> write(json_encode($data));
	}
	
########	Device Groups Datatables	###############END###########
################################################
########	List Device Group	###############START###########
	#########	GET List Device	Group#########
	public function getDeviceGroupList($req,$res)
	{
		//INITIAL CODE////START//
		$data=array();
		$data=$this->initialData([
			'type' => 'get',
			'object' => 'device group',
			'action' => 'list',
		]);
		#check error#
		if ($_SESSION['error']['status']){
			$data['error']=$_SESSION['error'];
			return $res -> withStatus(401) -> write(json_encode($data));
		}
		//INITIAL CODE////END//
		
		///IF GROUPID SET///
		if ($req->getParam('groupId') != null){
			if ($req->getParam('groupId') == 0) {
				$data['item'] = TACDeviceGrps::select(['id','name','key','enable','default_flag'])->
				where([['default_flag', '=', 1]])->
				first();
				$data['item']['text'] = $data['item']['name'];
				$data['item']['key'] = ($data['item']['key'] != '') ? true : false;
				$data['item']['enable'] = ($data['item']['enable'] != '') ? true : false;
				$data['item']['default_flag'] = ($data['item']['default_flag'] == 1) ? true : false;
			}
			if ($req->getParam('groupId') > 0) 
			{	
				$data['item'] = TACDeviceGrps::select(['id','name','key','enable','default_flag'])->
				where([['id', '=', $req->getParam('groupId')]])->
				first();
				$data['item']['text'] = $data['item']['name'];
				$data['item']['key'] = ($data['item']['key'] != '') ? true : false;
				$data['item']['enable'] = ($data['item']['enable'] != '') ? true : false;
				$data['item']['default_flag'] = ($data['item']['default_flag'] == 1) ? true : false;
			}
			return $res -> withStatus(200) -> write(json_encode($data));
		}
		//////////////////////
		////LIST OF GROUPS////
		$data['incomplete_results'] = false;
		$data['totalCount'] = TACDeviceGrps::select(['id','name'])->count();
		$tempData = TACDeviceGrps::select(['id','name','key','enable','default_flag'])->get()->toArray();
		$data['items']=array();
		foreach($tempData as $group)
		{
			$group['text'] = $group['name'];
			//unset($group['name']);
			$group['key'] = ($group['key'] != '') ? true : false;
			$group['enable'] = ($group['enable'] != '') ? true : false;
			$group['default_flag'] = ($group['default_flag'] == 1) ? true : false;
			$group['selected'] = ($group['default_flag']) ? true : false;
			array_push($data['items'],$group);
		}
		
		return $res -> withStatus(200) -> write(json_encode($data));
	}
########	List Device Group	###############END###########
################################################

}//END OF CLASS//
