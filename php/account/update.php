<?
	require_once '../public/common.php';
	require_once '../public/connect.php';
	require_once '../public/lkw.php';
	require_once '../dosql/mysql-function.php';

	$userId = $_POST['userId'];
	$userName = $_POST['userName'];
	$passWord = $_POST['passWord'];
	$passMd5 = md5($passWord);

	# 执行修改
	if ($passWord) {
		$sqlRes=dosql(array(
			'table'=>'user',
			'type'=>'update',
			'cond'=>array(
				'like'=>'userId='.$userId,
				'field'=>array('userName','passWord','updateTime'),
				'value'=>array($userName,$passMd5,'now()')
			)
		));
	} else {
		$sqlRes=dosql(array(
			'table'=>'user',
			'type'=>'update',
			'cond'=>array(
				'like'=>'userId='.$userId,
				'field'=>array('userName','updateTime'),
				'value'=>array($userName,'now()')
			)
		));
	}


	$request = array(
		'code'=>0,
		'message'=>'修改成功!',
		'sqlRes'=>$sqlRes,
		);

	echo json_encode($request);
?>