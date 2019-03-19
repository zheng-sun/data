<?php
set_time_limit(0);
ini_set('memory_limit','3072M');

require_once('Import.php');
require_once('DB.php');

$DB = new DB('127.0.0.1', 'root', '', 'insurance');

if( isset($_FILES['file']) ) {
	
	// 读取execl数据
	$Import = new Import();
	$data = $Import->get_execl($_FILES['file']);

	$lists = [];

	$project = '';  // 项目名称
	foreach($data as $key=>$value) {
		if( $key == 1) {
			$project = $value[0];  
		} else {
			$lists[] = array(
				'project' => $project,
				'big_classification' => $value[0],
				'middle_classification' => $value[1],
				'small_classification' => $value[2],
				'code' => $value[3],
				'name' => $value[4],
				'type' => $value[5]
			);
		}
	}

	if( empty($lists) == false ) {
		// 删除同项目下的数据
		$sql = "delete from project where project = '".$project."'";
		$result = $DB->query($sql);
		
		foreach($lists as $list) {
			$sql = "insert into project (project, big_classification, middle_classification, small_classification, code, name, type) values ('".$list['project']."', '".$list['big_classification']."', '".$list['middle_classification']."','".$list['small_classification']."','".$list['code']."','".$list['name']."','".$list['type']."')";
			$result = $DB->query($sql);
		}
	}

	unset($_FILES['file']);
	// 跳转 list
	echo "<script>location.href='?action=list';</script>";
}

$_GET['action'] = isset($_GET['action'])?$_GET['action']:'list';

// 获取列表信息
if( isset($_GET['action']) && $_GET['action'] == 'list') {
	// 获取信息
	$sql = "select * from project";
	$list = $DB->query($sql);

	// 获取项目
	$sql = "select project from project group by project";
	$project = $DB->query($sql);
}

// 搜索数据
if( isset($_GET['action']) && $_GET['action'] == 'search' ) {
	// 获取信息
	$search_project = $_GET['search_project'];
	$search_name = $_GET['search_name'];
	$where = '1 = 1';
	if( $search_project != '' ) {
		$where .= " and project = '".$search_project."'";
	}
	if( $search_name != '' ) {
		$where .= " and name like '%".$search_name."%'";
	}

	$sql = "select * from project where ".$where;
	$list = $DB->query($sql);

	// 获取项目
	$sql = "select project from project group by project";
	$project = $DB->query($sql);
}

?>


<!DOCTYPE html>
<html>
<head>
	<title></title>
</head>

<body style="font-size: 12px;">
	<form method="post" enctype="multipart/form-data">
		<input type="file" name="file">
		<input type="submit" name="提交">
	</form>
	<br />
	<form>
		<input type="hidden" name="action" value="search">
		<label>项目:</label>
		<select name="search_project">
			<option value="">全部项目</option>
			<?php foreach( $project->rows as $p) {?>
			<option value="<?php echo $p['project']?>" 
				<?php if( isset($search_project) && $search_project != '' && $search_project == $p['project'] ) { echo 'selected';}?>
				>
				<?php echo $p['project']?>
			</option>
			<?php } ?>
		</select>
		<label>职业：</label>
		<input type="text" name="search_name" value="<?php echo isset($search_name)?$search_name:''; ?>">
		<input type="submit" name="提交">
	</form>

	<table width="100%" border="1px">
		<thead>
			<th>ID</th>
			<th>项目</th>
			<th>大分类</th>
			<th>中分类</th>
			<th>小分类</th>
			<th>编码</th>
			<th>职业</th>
			<th>类别</th>
		</thead>
		<?php if(isset($list)) {?>
		<?php foreach( $list->rows as $key=>$value) {?>
			<tr>
				<td><?php echo $value['id'];?></td>
				<td><?php echo $value['project'];?></td>
				<td><?php echo $value['big_classification']?></td>
				<td><?php echo $value['middle_classification']?></td>
				<td><?php echo $value['small_classification']?></td>
				<td><?php echo $value['code']?></td>
				<td><?php echo $value['name']?></td>
				<td><?php echo $value['type']?></td>
			</tr>
		<?php } } ?>
	</table>

</body>
</html>
