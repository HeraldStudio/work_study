<?php
// 本类由系统自动生成，仅供测试用途
namespace Home\Controller;
use Think\Controller;
class ShowController extends Controller {
	public function index(){
		$this->show();
	}

	public function addContent(){
		$itemId=I('post.itemId');
		$addCount=I('post.addCount');

		$table=M('message');

		if( $itemId==0 ){
			$itemId=$table->order('publish_time desc')->limit(1)->getField('work_id')+1;
		}

		$sqlString='select work_id,work_summary,work_describe,work_place,work_time,work_salary '.
			'from message where work_id <'.$itemId.
			' order by publish_time desc limit '.$addCount;
		$assignArray["sqlArray"]=$table->query($sqlString);

		if( $assignArray["sqlArray"]!=false ){
			$returnArray["status"]=0;
			$returnArray["itemId"]=$assignArray["sqlArray"][ count( $assignArray["sqlArray"] )-1 ]["work_id"];
			$this->assign($assignArray);
			$returnArray["content"]=$this->fetch();
		}else{
			$returnArray["status"]=1;	//没有更多
			$returnArray["itemId"]=$itemId;
			$returnArray["content"]="";
		}
		$this->AjaxReturn($returnArray,'json');
	}

}