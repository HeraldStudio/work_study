var currentId=0;
var addCount=4;
var addMore=new addMoreClass();

loadContent();

function loadContent(){
	var postJson={
		"itemId": currentId,
		"addCount": addCount
	};
	$.ajax({
		//url: 'index.php/Home/Show/addContent',
		url: 'addContent',
		type:'post',
		dataType:'json',
		data: postJson,
		success:function(data){
			if(data.status==0){
				$("#container").append(data.content);
				$("#container").listview("refresh");
				currentId=data.itemId;
				addMore.stop();
			}else{
				addMore.noMore();
			}
		},
		error:function(){
			alert("很抱歉，网络错误，请稍后重试");
			addMore.stop();
		}
	});	


}

function addMoreClass(){
	var btn=new Object;
	btn.loading=function(){
		$("#addMore").html("……正在加载……");
		$("#addMore").button("refresh");
		$("#addMore").unbind();
	}
	btn.stop=function(){
		$("#addMore").html("加载更多");
		$("#addMore").button("refresh");
		$("#addMore").click(function(){
			loadContent();
			addMore.loading();
		});
	}
	btn.noMore=function(){
		$("#addMore").html("没有更多");
		$("#addMore").button("refresh");
		$("#addMore").unbind();
	}
	return btn;
}