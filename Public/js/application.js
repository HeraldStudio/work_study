var currentPage=1;

jQuery(document).ready(function(){
	$("#publishBtn").click(function(){ publish(); });
	$("#login").click(function(){ login(); });
});

function publish(){
	var postJson={
		"workDescribe" : $("#workDescribe").val(),
		"workPlace" : $("#workPlace").val(),
		"workTime" : $("#workTime").val(),
		"workSalary" : $("#workSalary").val()
	};

	$.ajax({
		url: 'index.php/Home/Index/publish',
		type:'post',
		dataType:'json',
		data: postJson,
		success:function(data){
			if(data.status==1){
				var tip='<div class="alert alert-success" id="publishTip">发布成功</div>';
				$("#publishBody").append(tip);
				setTimeout(function(){
					$("#publishModal").modal('hide');
					$("#publishTip").remove();
				},500);
				$("#workDescribe").val("");
				$("#workPlace").val("");
				$("#workTime").val("");
				$("#workSalary").val("");
				loadItems(currentPage);
			}else if(data.status==2){
				alert("很抱歉，发布失败");
			}else if(data.status==3){
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
		url: 'index.php/Home/Index/login',
		type:'post',
		dataType:'json',
		data: postJson,
		success:function(data){
			if(data.status==1){
				$("#pleaseLogin").removeClass("alert-danger");
				$("#pleaseLogin").addClass("alert-success");
				$("#pleaseLogin").html("登陆成功");
				setTimeout(function(){
					loadItems(1)
				},500);
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

function loadItems(page){
	var postJson={
		"page":page
	};

	$.ajax({
		url: 'index.php/Home/Index/addContent',
		type:'post',
		dataType:'json',
		data: postJson,
		success:function(data){
			if(data.status==1){
				$("#main").html(data.content);
				addFunction();
			}else if(data.status==2){
				alert("登录失败");
			}else if(data.status==3){
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
		url: 'index.php/Home/Index/deleteItem',
		type:'post',
		dataType:'json',
		data: postJson,
		success:function(data){
			if(data.status){
				loadItems(currentPage);
			}else{
				alert("很抱歉，删除失败");
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
		url: 'index.php/Home/Index/itemStatus',
		type:'post',
		dataType:'json',
		data: postJson,
		success:function(data){
			if(data.status){
				if(data.newStatus==true){
					var newContent='<span class="glyphicon glyphicon-pause"></span>已经招满';
					$(".statusBtn."+id).removeClass("btn-success");
					$(".statusBtn."+id).addClass("btn-default");
				}else{
					var newContent='<span class="glyphicon glyphicon-ok"></span>正在招聘';
					$(".statusBtn."+id).removeClass("btn-default");
					$(".statusBtn."+id).addClass("btn-success");
				}
				$(".statusBtn."+id).html(newContent);
			}else{
				alert("很抱歉，删除失败");
			}
		},
		error:function(){
			alert("很抱歉，网络错误，请稍后重试");
		}
	});		
}