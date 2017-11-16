<?php
namespace app\index\controller;
use \think\Request;
use \think\Db;
use \think\Session;

class Index extends \think\Controller
{
    public function index()
    {
        return $this->fetch('index');
    }
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
    public function myself()
    {
        $user =Session::get('user');
        if($user){
            $this->assign('user',$user);
            return $this->fetch('myself');
        }else{
            return $this->fetch('index');
        }
    }
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
    public function upload(){
    // 获取表单上传文件 例如上传了001.jpg

        $user =Session::get('user');
        if($user){
            $file = request()->file('file');
            if($file){
            // 移动到框架应用根目录/public/uploads/ 目录下
                $info = $file->validate(['size'=>536870912,'ext'=>'jpg,png,gif,mp4'])->move(ROOT_PATH . 'public' . DS . 'uploads/'.$user['userclass'].'/'.$user['userid'],'');
            if($info){
                $filepath = 'public' . DS . 'uploads/'.$user['userclass'].'/'.$user['userid'];
                $data = [
                    'fileid' => date("YmdHis"),
                    'userid' => $user['userid'],
                    'filepath' => $filepath,
                    'filename' => $info->getSaveName(),
                    'update' => date("Y-m-d H:i:s"),
                    ];
                $msg=Db::table('file')->insert($data);
                return $msg;
            }else{
                // 上传失败获取错误信息
                return $file->getError();
            }
        }else{
            return 0;
        }
    }
    
    }
}
