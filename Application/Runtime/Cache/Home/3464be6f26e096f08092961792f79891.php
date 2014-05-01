<?php if (!defined('THINK_PATH')) exit();?><table class="table table-hover" id="infoTable">
  <tr class="success">
    <th id="wId"><strong>#</strong></th>
    <th id="wSummary"><strong>工作摘要</strong></th>
    <th id="wDescribe"><strong>工作描述</strong></th>
    <th id="wPlace"><strong>地点</strong></th>
    <th id="wTime"><strong>工作时间</strong></th>
    <th id="wSalary"><strong>薪水</strong></th>
    <th id="wPubTime"><strong>发布时间</strong></th>
    <th id="wStatus"><strong>招聘状态</strong></th>
    <th id="wDelete"><strong>删除</strong></th>
  </tr>

<?php
 for($i=0; $i<20 && $i<count($sqlArray); $i++){ ?>
	  <tr>
	    <td><?php echo ($sqlArray[$i]["work_id"]); ?></td>
	    <td><?php echo ($sqlArray[$i]["work_summary"]); ?></td>
	    <td><?php echo ($sqlArray[$i]["work_describe"]); ?></td>
	    <td><?php echo ($sqlArray[$i]["work_place"]); ?></td>
	    <td><?php echo ($sqlArray[$i]["work_time"]); ?></td>
	    <td><?php echo ($sqlArray[$i]["work_salary"]); ?></td>
	    <td><?php echo ($sqlArray[$i]["publish_time"]); ?></td>
	    <td>
	    	<?php
 if($sqlArray[$i]["finish"]){ ?>
				<button type="pick" class="btn btn-sm btn-default statusBtn <?php echo ($sqlArray[$i]['work_id']); ?>" id='<?php echo ($sqlArray[$i]["work_id"]); ?>'>
					<span class="glyphicon glyphicon-pause"></span>&nbsp已经招满
				</button>
			<?php
 }else{ ?>
				<button type="pick" class="btn btn-sm btn-success statusBtn <?php echo ($sqlArray[$i]['work_id']); ?>" id='<?php echo ($sqlArray[$i]["work_id"]); ?>'>
					<span class="glyphicon glyphicon-ok"></span>&nbsp正在招聘
				</button>
			<?php
 } ?>			
	    </td>
	    <td>
			<button type="pick" class="btn btn-sm btn-danger deleteBtn" id='<?php echo ($sqlArray[$i]["work_id"]); ?>'>
				<span class="glyphicon glyphicon-remove"></span>&nbsp删 除
			</button>
	    </td>
	  </tr>

<?php
 } ?>
</table>

<center>
<ul class="pagination">
  <!-- <li><a href="#">&laquo;</a></li> -->
  <li><a href="#">&nbsp</a></li>

<?php
 if($pageCount<=5){ for($i=1; $i<=$pageCount; $i++){ if($i== $page){ ?>
				<li class="active"><a href="#"><?php echo ($i); ?></a></li>
			<?php
 }else{ ?>
				<li class="paging" id="<?php echo ($i); ?>"><a href="#"><?php echo ($i); ?></a></li>
			<?php
 } } }else if($page<=3){ for($i=1; $i<=5; $i++){ if($i== $page){ ?>
				<li class="active"><a href="#"><?php echo ($i); ?></a></li>
			<?php
 }else{ ?>
				<li class="paging" id="<?php echo ($i); ?>"><a href="#"><?php echo ($i); ?></a></li>
			<?php
 } } ?>
			<li><a>...</a></li>
		<?php
 }else if($page>3 && $page<$pageCount-2){ ?>
			<li><a>...</a></li>
		<?php
 for($i=$page-2; $i<=$page+2; $i++){ if($i== $page){ ?>
				<li class="active"><a href="#"><?php echo ($i); ?></a></li>
			<?php
 }else{ ?>
				<li class="paging" id="<?php echo ($i); ?>"><a href="#"><?php echo ($i); ?></a></li>
			<?php
 } } ?>
			<li><a>...</a></li>
		<?php
 }else{ ?>
			<li><a>...</a></li>
		<?php
 for($i=$pageCount-4; $i<=$pageCount; $i++){ if($i== $page){ ?>
				<li class="active"><a href="#"><?php echo ($i); ?></a></li>
			<?php
 }else{ ?>
				<li class="paging" id="<?php echo ($i); ?>"><a href="#"><?php echo ($i); ?></a></li>
			<?php
 } } } ?>

  <!-- <li><a href="#">&raquo;</a></li> -->
  <li><a href="#">&nbsp</a></li>
</ul>
</center>