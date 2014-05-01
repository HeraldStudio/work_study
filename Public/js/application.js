var currentPage=1;

jQuery(document).ready(function(){
	$("#publishBtn").click(function(){ publish(); });
	$("#login").click(function(){ login(); });
});

function publish(){
	var postJson={
		"workSummary" : $("#workSummary").val(),
		"workDescribe" : $("#workDescribe").val(),
		"workPlace" : $("#workPlace").val(),
		"workTime" : $("#workTime").val(),
		"workSalary" : $("#workSalary").val()
	};

	$.ajax({
		url: 'index.php/Index/publish',
		type:'post',
		dataType:'json',
		data: postJson,
		success:function(data){
			if(data.status==0){
				var tip='<div class="alert alert-success" id="publishTip">发布成功</div>';
				$("#publishBody").append(tip);
				setTimeout(function(){
					$("#publishModal").modal('hide');
					$("#publishTip").remove();
				},300);
				$("#workSummary").val("");
				$("#workDescribe").val("");
				$("#workPlace").val("");
				$("#workTime").val("");
				$("#workSalary").val("");
				loadItems(currentPage);
			}else if(data.status==1){
				alert("很抱歉，发布失败");
			}else if(data.status==2){
				alert("您尚未登录，请先登录");
			}
		},
		error:function(){
			alert("很抱歉，网络错误，请稍后重试");
		}
	});
}

function login(){
	var postJson={
		"username": $("#username").val(),
		"password": $("#password").val()
	};

	$.ajax({
		//url: 'index.php/Manage/Index/login',
		url: 'index.php/Index/login',
		type:'post',
		dataType:'json',
		data: postJson,
		success:function(data){
			if(data.status==0){
				$("#pleaseLogin").removeClass("alert-danger");
				$("#pleaseLogin").addClass("alert-success");
				$("#pleaseLogin").html("登陆成功");
				setTimeout(function(){
					loadItems(1)
				},300);
			}else{
				$("#pleaseLogin").html("登陆失败");
			}
		},
		error:function(){
			alert("很抱歉，网络错误，请稍后重试");
		}
	});
}

function addFunction(){
	$(".paging").click(function(){
		loadItems(this.id);
	});

	$(".statusBtn").click(function(){
		itemStatus(this.id);
	});

	$(".deleteBtn").click(function(){
		var c=confirm("你是否要删除此项？");
		if(c==true){
			deleteItem(this.id);
		}
	});
}

function loadItems(page){	//page代表加载第几页
	var postJson={
		"page":page
	};

	$.ajax({
		url: 'index.php/Index/addContent',
		type:'post',
		dataType:'json',
		data: postJson,
		success:function(data){
			if(data.status==0){
				$("#main").html(data.content);
				addFunction();
			}else if(data.status==2){
				alert("您尚未登录，请先登录");
			}else if(data.status==1){
				alert("加载失败");
			}
			currentPage=page;
		},
		error:function(){
			alert("很抱歉，网络错误，请稍后重试");
		}
	});	
}

function deleteItem(id){
	var postJson={
		"deleteId": id
	};
	$.ajax({
		url: 'index.php/Index/deleteItem',
		type:'post',
		dataType:'json',
		data: postJson,
		success:function(data){
			if(data.status==true){
				loadItems(currentPage);
			}else if(data.status==false){
				alert("很抱歉，删除失败");
			}else{
				alert("您尚未登录，请先登录");
			}
		},
		error:function(){
			alert("很抱歉，网络错误，请稍后重试");
		}
	});		
}

function itemStatus(id){
	var postJson={
		"itemId": id
	};
	$.ajax({
		url: 'index.php/Index/itemStatus',
		type:'post',
		dataType:'json',
		data: postJson,
		success:function(data){
			if(data.status==true){
				if(data.newItemStatus==true){
					var newContent='<span class="glyphicon glyphicon-pause"></span>&nbsp已经招满';
					$(".statusBtn."+id).removeClass("btn-success");
					$(".statusBtn."+id).addClass("btn-default");
				}else{
					var newContent='<span class="glyphicon glyphicon-ok"></span>&nbsp正在招聘';
					$(".statusBtn."+id).removeClass("btn-default");
					$(".statusBtn."+id).addClass("btn-success");
				}
				$(".statusBtn."+id).html(newContent);
			}else if(data.newStatus==false){
				alert("很抱歉，删除失败");
			}else{
				alert("您尚未登录，请先登录");
			}
		},
		error:function(){
			alert("很抱歉，网络错误，请稍后重试");
		}
	});		
}