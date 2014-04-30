<?php
// 本类由系统自动生成，仅供测试用途
namespace Home\Controller;
use Think\Controller;
class IndexController extends Controller {
	public function index(){
		//验证是否登录
		$outPutArray["login"]=true;
		//
		$this->assign($outPutArray);
		$this->display();
	}

	//管理员登录函数
	public function login(){
		$username=I('post.username');
		$password=I('post.password');

		//验证登录信息
		if($username=="" && $password==""){
			$login=true;
		}else{
			$login=false;
		}
		//
		if($login){
			$returnArray["status"]=1;
		}else{
			$returnArray["status"]=2;
		}
		$this->AjaxReturn($returnArray,'json');
	}

	//管理员退出函数
	public function logout(){

	}

	//消息发布函数
	public function publish(){
		//验证是否登录
		$login=true;
		//

		if($login){
			$receiveInfo=array(
				"work_describe"=>I('post.workDescribe'),
				"work_place"=>I('post.workPlace'),
				"work_time"=>I('post.workTime'),
				"work_salary"=>I('post.workSalary'),
				"publish_time"=>date('Y-m-d H:i:s')
				);

			$id=M('message')->data($receiveInfo)->add();
			if($id){//成功添加
				$returnArray=array('status'=>1);
			}else{//添加失败
				$returnArray=array('status'=>2);
			}
			$this->AjaxReturn($returnArray,'json');
		}else{
			$returnArray=array('status'=>3);
			$this->AjaxReturn($returnArray,'json');
		}
	}

	public function addContent(){//加载内容
		$pageItems=20;//设置每页可以加载信息的条数
		$page=I('post.page');
		$table=M('message');
		$sqlString='select work_id,work_describe,work_place,work_time,work_salary,publish_time,finish '.
			'from message order by publish_time desc limit '.
			($page-1)*$pageItems .','.$pageItems;
		$assignArray["sqlArray"]=$table->query($sqlString);

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
		$content=$this->fetch();

		$returnData["content"]=$content;
		$returnData["status"]=1;
		$this->AjaxReturn($returnData,'json');
	}

	public function deleteItem()
	{
		$deleteId=I('post.deleteId');
		$table=M('message');
		$returnArray["status"]=$table->where('work_id='.$deleteId)->delete();
		$this->AjaxReturn($returnArray,'json');
	}

	public function itemStatus(){
		$itemId=I('post.itemId');
		$table=M('message');
		$currentStatus=$table->where('work_id='.$itemId)->getField('finish');
		if($currentStatus){
			$returnArray["newStatus"]=$data["finish"]=fasle;
		}else{
			$returnArray["newStatus"]=$data["finish"]=true;
		}
		$returnArray["status"]=$table->where('work_id='.$itemId)->save($data);
		$this->AjaxReturn($returnArray,'json');
	}
}