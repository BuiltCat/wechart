<?php
namespace app\index\controller;
use \think\Request;
use \think\Db;
use \think\Session;

class Index extends \think\Controller
{
    /****************************学生*********************/
    // 学生端主页
    // return 页面
    public function index()
    {
        return $this->fetch('index');
    }
    // 登陆请求页面
    // return 0为登陆失败，1为登录成功
    public function login()
    {
        $request = Request::instance();
        $user = Db::table('user')->where($request->param())->find();
        if($user){
            Session::set('user',$user);
            return 1;
        }else{
            return 0;
        }
    }
    public function changepw()
    {
        return $this->fetch('changepw');
    }
    public function toChange()
    {
        $request = Request::instance()->param();
        $toExist = [
            'userid' => $request['userid'],
            'userpw' => $request['olduserpw']
        ];
        $isExist = Db::table('user')->where($toExist)->find();
        if($isExist){
            $result = Db::table('user')->where('userid',$request['userid'])->update(['userpw'=>$request['userpw']]);
            if($request){
                return 1;
            }else{
                return '插入失败,请重试!';
            }
        }else{
            return '密码不正确';
        }
    }
    // 个人中心
    // return 页面
    public function myself()
    {
        $user =Session::get('user');
        if($user){
            $myclass = Db::table('stuclass')->where('userid',$user['userid'])->field('userclass')->select();
            $this->assign('myclass',$myclass);
            $this->assign('user',$user);
            return $this->fetch('myself');
        }else{
            return $this->fetch('index');
        }
    }
    public function userclass()
    {
        $user =Session::get('user');
        if($user){
            $sql = 'SELECT class FROM teaclass WHERE class not in ( SELECT userclass FROM stuclass WHERE userid = '.$user['userid'].' )';
            $class = Db::query($sql);
            $myclass = Db::table('stuclass')->where('userid',$user['userid'])->field('userclass')->select();
            $this->assign('myclass',$myclass);
            $this->assign('class',$class);
            $this->assign('user',$user);
            return $this->fetch('userclass');
        }else{
            return $this->fetch('index');
        }
    }
    public function stuClass(){
        $user =Session::get('user');
        $request = Request::instance()->param();
        $data = [
            'userid' => $user['userid'],
            'userclass' => $request['class']
        ];
        $info = Db::table('stuclass')->insert($data);
        return $info;
    }
    public function newuser(){
        return $this->fetch('newuser');
    }
    public function register(){
        $request = Request::instance()->param();
        $isExist = Db::table('user')->where('userid',$request['userid'])->find();
        if($isExist){
            return "用户已存在，请登录。";
        }else { 
            $result = Db::table('user')->insert($request);
            if($result){
                return 1;
            }else {
                return "注册失败！请重试.";
            }
        }

    }
    // 上传页面
    // return 页面
    public function uppage()
    {
        $user =Session::get('user');
        if($user){
            $class = Request::instance()->param('class');
            if($class){
                Session::set('class',$class);
                $file = Db::table('file')->where('userid',$user['userid'])->where('class',$class)->select();
                $this->assign('file',$file);
                return $this->myself();
            }
        }else{
            return $this->fetch('index');
        }
    }
    // 文件上传请求
    // return 1 成功 2 错误信息
    public function upload(){
    // 获取表单上传文件 例如上传了001.jpg

        $user =Session::get('user');
        if($user){
            $file = request()->file('file');
            $class = Session::get('class');
            if($file){
            // 移动到框架应用根目录/public/目录下
                $filepath ='public' . DS . $class . DS .$user['userid'];
                $filepath = iconv("utf-8","gb2312//IGNORE",$filepath);
                $info = $file->validate(['size'=>536870912,'ext'=>'jpeg,jpg,png,mp4'])->move(ROOT_PATH . $filepath ,'');
                if($info){
                    $filepath = 'public' . DS . $class . DS . $user['userid'];
                    $data = [
                        'fileid' => date("YmdHis"),
                        'userid' => $user['userid'],
                        'filepath' => $filepath,
                        'filename' => iconv("GB2312","UTF-8//IGNORE",$info->getSaveName()),
                        'update' => date("Y-m-d H:i:s"),
                        'class' => $class
                        ];
                    $dele = Db::table('file')->where('filename',$data['filename'])->delete();
                    $msg=Db::table('file')->insert($data);
                    return 1;
                }else{
                    // 上传失败获取错误信息
                    return $file->getError();
                }
            }else{
                return "请选择上传文件";
            }
        }
    
    }
    // 删除文件请求
    // ruturn 成功 删除行数 失败 0
    public function delete(){
        $delete = request()->delete();
        $info = Db::table('file')->where($delete)->find();
        $path = str_replace('public'.DS,'',$info['filepath']).DS.$info['filename'];
        $path = iconv("utf-8","gb2312//IGNORE",$path);
        if(@unlink($path)){
            $msg = Db::table('file')->where($delete)->delete();
            return $msg;
        }else {
            return false;
        }
    }
    /****************************老师*********************/
    public function admin(){
        $teacher =Session::get('teacher');
        $request = Request::instance()->param('option');
        if($teacher){
            $class = Db::table('teaclass')->where('userid','=',$teacher['userid'])->column('class');
            $sql = "SELECT * FROM `user`,stuclass WHERE `user`.userid = stuclass.userid";
            $users = Db::query($sql);
            if($class){
                if($request){
                    $this->assign('choose',$request);
                    Session::set('setClass',$request);   
                    $fileList = Db::table('file')->field('userid,filename,update,class')->select();
                    $this->assign('fileList',$fileList);
                }else{
                    $this->assign('choose',$class[0]);
                    Session::set('setClass',$class[0]);   
                    $fileList = Db::table('file')->field('userid,filename,update,class')->select();
                    $this->assign('fileList',$fileList);
                }    
            }else{  
                $this->assign('choose',null);
                $this->assign('fileList',null);
            }
            $this->assign('teacher',$teacher);
            $this->assign('class',$class);
            $this->assign('users',$users);

            return $this->fetch('admin');
        }else{
            return $this->fetch('telogin');
        }
    }
    // 老师登录页面
    // return 页面
    public function telogin(){
            return $this->fetch('telogin');
    }
    // 老师登陆验证
    // return 0为登陆失败，1为登录成功
    public function checklog()
    {
        $request = Request::instance();
        $teacher = Db::table('teacher')->where($request->param())->find();
        if($teacher){
            Session::set('teacher',$teacher);
            return 1;
        }else{
            return 0;
        }
    }
    // 删除学生
    public function deleteStu()
    {
        $request = Request::instance()->param();
        // $patharray = Db::table('file')->where($request)->find();
        $result = Db::table('stuclass')->where($request)->delete();
        // $result1 = Db::table('file')->where($request)->delete();
        // $path = str_replace('public/','',$patharray['filepath']);
        // $this->delDirAndFile($path);
         return $result;
    }
    function delDirAndFile($dirName)
    {
        if ( $handle = opendir( "$dirName" ) ) {
            while ( false !== ( $item = readdir( $handle ) ) ) {
                if ( $item != "." && $item != ".." ) {
                    if ( is_dir( "$dirName/$item" ) ) {
                        $this->delDirAndFile( "$dirName/$item" );
                    } else {
                        @unlink( "$dirName/$item");
                    }
                }
            }
            closedir( $handle );
            rmdir( $dirName );
        }   
    }
    public function deleteCla()
    {
        $request = Request::instance()->param();
        $result = Db::table('teaclass')->where($request)->delete();
        return $result;
    }
    public function addClass(){
        $teacher =Session::get('teacher');
        $request = Request::instance()->param();
        $isExit=Db::table('teaclass')->where($request)->find();
        if($isExit){
            return "该班级已被其他老师选择";
        }else{
            if($request){
                $data['userid'] = $teacher['userid'];
                $data['class'] = $request['class'];
                $result = Db::table('teaclass')->insert($data);
                if($result){
                     return 1;
                }else{
                    return 0;
                }
            }
        }
    }
    public function addStu(){
        $teacher =Session::get('teacher');
        $class = Session::get('setClass');
        if($class){
            $request = Request::instance()->param();
            $isExit=Db::table('user')->where('userid',$request['userid'])->find();
            if($isExit){
                return 30;
            }else{
                if($request){
                    $request['userpw'] = "123456";
                    $data = [
                        'userid' => $request['userid'],
                        'userclass' => $class
                    ];
                    $result = Db::table('user')->insert($request);
                    $result1 = Db::table('stuclass')->insert($data);
                    if($result&&$result1){
                        return 1;
                    }else{
                        return 20;
                    }
                }
            }
        }else{
            return 40;
        }
    }
    public function downloadOne()
    {
        $request = Request::instance()->param();
        $info = Db::table('file')->where($request)->find();
        if($info){
            $zipname = $info['userid'].'.zip';
            $zippath = str_replace('public'.DS,'',$info['filepath']);
            $zippath = iconv("utf-8","gb2312//IGNORE",$zippath);
            @unlink($request['userid'].'.zip');
            $zip=new \ZipArchive();
            if($zip->open($zipname, \ZipArchive::CREATE)){
                $this->addFileToZip($zippath, $zip); //调用方法，对要打包的根目录进行操作，并将ZipArchive的对象传递给方法
                $zip->close(); //关闭处理的zip文件
            }
            return $zipname;
        }else{
            return 0;
        }
    }
    public function downloadAll()
    {
        $request = Request::instance()->param();
        $info = Db::table('file')->where($request)->find();
        if($info){
            $zipname = $request['class'];
            $zipname = $zipname.'.zip';
            $zippath = iconv("utf-8","gb2312//IGNORE",$request['class']);
            $zip=new \ZipArchive();
            if($zip->open($zipname, \ZipArchive::CREATE)){
                // $this->addFileToZip($zippath, $zip); //调用方法，对要打包的根目录进行操作，并将ZipArchive的对象传递给方法
                $zip->close(); //关闭处理的zip文件
            }
            return $zipname;
        }else{
            return 0;
        }
    }
    function addFileToZip($path,$zip){
        $handler=opendir($path); //打开当前文件夹由$path指定。
        while(($filename=readdir($handler))!==false){
            if($filename != "." && $filename != ".."){//文件夹文件名字为'.'和‘..’，不要对他们进行操作
                if(is_dir($path.DS.$filename)){// 如果读取的某个对象是文件夹，则递归
                    $this->addFileToZip($path.DS.$filename, $zip);
                }else{ //将文件加入zip对象
                    $zip->addFile($path.DS.$filename);
                }
            }
        }
        @closedir($path);
    }
    function downfile()
    {
        $fileurl = Request::instance()->param('fileurl');
        $fileurl =  iconv("utf-8","gb2312//IGNORE",$fileurl);
        $file=fopen($fileurl,"r");
        header("Content-Type: application/octet-stream");
        header("Accept-Ranges: bytes");
        header("Accept-Length: ".filesize($fileurl));
        header("Content-Disposition: attachment; filename=".$fileurl);
        echo fread($file,filesize($fileurl));
        fclose($file);
    }
    function teReg()
    {
        return $this->fetch('tereg');
    }
    function regist()
    {
        $request = Request::instance()->param();
        $isExist = Db::table('teacher')->where('userid',$request['userid'])->find();
        if(!$isExist){
            if($request['zcm'] == 'yqxyqxls123'){
                unset($request['zcm']);
                $msg = Db::table('teacher')->insert($request);
                return $msg;     
            }else{
                return 400;
            }
        }else{
            return 500;
        }
    }
}
