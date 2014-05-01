<?php
// 本类由系统自动生成，仅供测试用途
namespace Manage\Controller;
use Think\Controller;
use Think\Cache;
class IndexController extends Controller {

	public function index(){
		$this->show();
	}

	//管理员登录函数
	public function login(){
		$username=I('post.username');
		$password=I('post.password');

		//验证登录信息
		$table=M('users');
		$sqlResult=$table->where("username='".$username."'")->find();
		$returnArray["val"]=$sqlResult;

		if(!is_null($sqlResult) && $password==$sqlResult["password"]){
			$login=true;
			$this->setSession();
		}else{
			$login=false;
		}
		//
		if($login){
			$returnArray["status"]=0;
		}else{
			$returnArray["status"]=1;
		}
		$this->AjaxReturn($returnArray,'json');
	}

	//管理员退出函数
	public function logout(){
		if($this->logStatus()){
			$Cache = Cache::getInstance('File',array('expire'=>900,'length'=>5));
			$Cache->rm('sessionCache');
			setcookie('sessionCache', "", time()+900, '/');
			$returnArray["status"]=0;
		}else{
			$returnArray["status"]=1;
		}
		$this->AjaxReturn($returnArray,'json');
	}

	private function logStatus(){
		$Cache = Cache::getInstance('File',array('expire'=>900,'length'=>5));
		$cookie=$_COOKIE["sessionCache"];
		$sessionCache=$Cache->get('sessionCache');
		$returnArray["cookie"]=$cookie;
		$returnArray["sessionCache"]=$sessionCache;
		if($cookie==$sessionCache){
			return true;
		}else{
			return false;
		}
	}

	private function setSession(){
		$Cache = Cache::getInstance('File',array('expire'=>1800,'length'=>5));
		$sessionCache=md5(rand()+time());
		$Cache->set('sessionCache',$sessionCache);	//写入服务器缓存
		setcookie('sessionCache', $sessionCache, time()+1800, '/');
	}

	//消息发布函数
	public function publish(){
		if($this->logStatus()){
			$receiveInfo=array(
				"work_summary"=>I('post.workSummary'),
				"work_describe"=>I('post.workDescribe'),
				"work_place"=>I('post.workPlace'),
				"work_time"=>I('post.workTime'),
				"work_salary"=>I('post.workSalary'),
				"publish_time"=>date('Y-m-d H:i:s')
				);

			$id=M('message')->data($receiveInfo)->add();
			if($id){//成功添加
				$returnArray=array('status'=>0);
			}else{//添加失败
				$returnArray=array('status'=>1);
			}
			$this->setSession();
			$this->AjaxReturn($returnArray,'json');
		}else{
			$returnArray=array('status'=>2);
			$this->AjaxReturn($returnArray,'json');
		}
	}

	public function addContent(){//加载内容
		if(!$this->logStatus()){
			$returnData["content"]="";
			$returnData["status"]=2;
		}
		else{
			$pageItems=20;//设置每页可以加载信息的条数
			$page=I('post.page');
			$table=M('message');
			$sqlString='select work_id,work_summary,work_describe,work_place,work_time,work_salary,publish_time,finish '.
				'from message order by publish_time desc limit '.
				($page-1)*$pageItems .','.$pageItems;
			$assignArray["sqlArray"]=$table->query($sqlString);

			if(is_null($assignArray["sqlArray"])){	//加载失败的情况
				$returnData["content"]="";
				$returnData["status"]=1;
			}else{
				$itemCount=$table->count();
				$pageCount=$itemCount/$pageItems;
				if($pageCount>(int)$pageCount){
					$pageCount=(int)$pageCount+1;
				}else{
					$pageCount=(int)$pageCount;
				}
				$assignArray["page"]=$page;
				$assignArray["pageCount"]=$pageCount;

				$this->assign($assignArray);
				$returnData["content"]=$this->fetch();
				$returnData["status"]=0;
				$this->setSession();
			}
		}
		$this->AjaxReturn($returnData,'json');
	}

	public function deleteItem(){
		if( $this->logStatus() ){
			$deleteId=I('post.deleteId');
			$table=M('message');
			$returnArray["status"]=$table->where('work_id='.$deleteId)->delete();
			$this->setSession();
		}else{
			$returnArray["status"]=2;
		}
		$this->AjaxReturn($returnArray,'json');
	}

	public function itemStatus(){
		if( $this->logStatus() ){
			$itemId=I('post.itemId');
			$table=M('message');
			$currentStatus=$table->where('work_id='.$itemId)->getField('finish');
			if($currentStatus){
				$returnArray["newItemStatus"]=$data["finish"]=fasle;
			}else{
				$returnArray["newItemStatus"]=$data["finish"]=true;
			}
			$returnArray["status"]=$table->where('work_id='.$itemId)->save($data);
			$this->setSession();
		}else{
			$returnArray["status"]=2;
		}
		$this->AjaxReturn($returnArray,'json');
	}
}