<?php
namespace app\index\controller;
use \think\Request;
use \think\Db;
use \think\Session;

class Index extends \think\Controller
{
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
    // 个人中心
    // return 页面
    public function myself()
    {
        $user =Session::get('user');
        if($user['need'] == 0){
            $this->assign('user',$user);
            return $this->fetch('newuser');
        }else if($user){
            $this->assign('user',$user);
            return $this->fetch('myself');
        }else{
            return $this->fetch('index');
        }
    }
    public function register(){
        $user =Session::get('user');
        if($user){
            $request = Request::instance()->param();
            $result = Db::table('user')->where('userid',$user['userid'])->update($request);
            if($result){
                return 1;
            }else {
                return 0;
            }
        }

    }
    // 上传页面
    // return 页面
    public function uppage()
    {
        $user =Session::get('user');
        if($user){
            $file = Db::table('file')->where('userid',$user['userid'])->select();
            $this->assign('file',$file);
            return $this->fetch('upload');
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
            if($file){
            // 移动到框架应用根目录/public/uploads/ 目录下
                $info = $file->validate(['size'=>536870912,'ext'=>'jpeg,jpg,png,mp4'])->move(ROOT_PATH . 'public' . DS . 'uploads/'.$user['userclass'].'/'.$user['userid'],'');
                if($info){
                    $filepath = 'public' . DS . 'uploads/'.$user['userclass'].'/'.$user['userid'];
                    $data = [
                        'fileid' => date("YmdHis"),
                        'userid' => $user['userid'],
                        'filepath' => $filepath,
                        'filename' => iconv("GB2312","UTF-8//IGNORE",$info->getSaveName()),
                        'update' => date("Y-m-d H:i:s"),
                        ];
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
        $path = str_replace('public/','',$info['filepath']).'/'.$info['filename'];
        if(unlink($path)){
            $msg = Db::table('file')->where($delete)->delete();
            return $msg;
        }else {
            return false;
        }
    }
    
    public function admin(){
        $teacher =Session::get('teacher');
        $request = Request::instance()->param('option');
        if($teacher){
            $class = Db::table('teaclass')->where('userid','=',$teacher['userid'])->column('class');
            $users = Db::table('user')->select();
            if($request){
                $this->assign('choose',$request);
            }else{
                $this->assign('choose',$class[0]);
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
        $patharray = Db::table('file')->where($request)->find();
        $result = Db::table('user')->where($request)->delete();
        $result1 = Db::table('file')->where($request)->delete();
        $path = str_replace('public/','',$patharray['filepath']);
        $this->delDirAndFile($path);
        return $result;
    }
    function delDirAndFile( $dirName )
    {
        if ( $handle = opendir( "$dirName" ) ) {
            while ( false !== ( $item = readdir( $handle ) ) ) {
                if ( $item != "." && $item != ".." ) {
                    if ( is_dir( "$dirName/$item" ) ) {
                        $this->delDirAndFile( "$dirName/$item" );
                    } else {
                        unlink( "$dirName/$item");
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
            return "已存在";
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
        $request = Request::instance()->param();
        $isExit=Db::table('user')->where('userid',$request['userid'])->find();
        if($isExit){
            return "已存在";
        }else{
            if($request){
                $request['userpw'] = "123456";
                $request['need'] = 0;
                $result = Db::table('user')->insert($request);
                if($result){
                     return 1;
                }else{
                    return 0;
                }
            }
        }
    }
    public function downloadOne()
    {
        $request = Request::instance()->param();
        $info = Db::table('file')->where($request)->find();
        $zipname = $info['userid'].'.zip';
        $zippath = str_replace('public/','',$info['filepath']).'/';
        $zip=new \ZipArchive();
        if($zip->open($zipname, \ZipArchive::CREATE)){
            $this->addFileToZip($zippath, $zip); //调用方法，对要打包的根目录进行操作，并将ZipArchive的对象传递给方法
            $zip->close(); //关闭处理的zip文件
        }
        return $zipname;
    }
    public function downloadAll()
    {
        $request = Request::instance()->param();
        $zipname = $request['class'];
        $zippath = 'uploads/'.$zipname.'/';
        $zipname = $zipname.'.zip';
        $zip=new \ZipArchive();
        if($zip->open($zipname, \ZipArchive::CREATE)){
            $this->addFileToZip($zippath, $zip); //调用方法，对要打包的根目录进行操作，并将ZipArchive的对象传递给方法
            $zip->close(); //关闭处理的zip文件
        }
        return $zipname;
    }
    function addFileToZip($path,$zip){
        $handler=opendir($path); //打开当前文件夹由$path指定。
        while(($filename=readdir($handler))!==false){
            if($filename != "." && $filename != ".."){//文件夹文件名字为'.'和‘..’，不要对他们进行操作
                if(is_dir($path."/".$filename)){// 如果读取的某个对象是文件夹，则递归
                    $this->addFileToZip($path."/".$filename, $zip);
                }else{ //将文件加入zip对象
                    $zip->addFile($path."/".$filename);
                }
            }
        }
        @closedir($path);
    }
    function downfile()
    {
        $fileurl = Request::instance()->param('fileurl');
        $file=fopen($fileurl,"r");
        header("Content-Type: application/octet-stream");
        header("Accept-Ranges: bytes");
        header("Accept-Length: ".filesize($fileurl));
        header("Content-Disposition: attachment; filename=".$fileurl);
        echo fread($file,filesize($fileurl));
        fclose($file);
    }
}
