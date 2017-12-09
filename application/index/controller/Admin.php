<?php
namespace app\index\controller;
use \think\Request;
use \think\Db;
use \think\Session;
use think\Loader;
use PHPExcel_IOFactory;
use PHPExcel;

class Admin extends \think\Controller
{
    public function index(){
        return $this->fetch('login');
    }
    public function login(){
        $info = Request::instance()->param();
        if($info['userid'] == 'yqx'&&$info['userpw'] == 'yqxyqx123'){
            Session::set('info',$info);
            return 1;
        }else{
            return 0;
        }
    }
    public function Administration(){
        if(Session::get('info')){
            $users = Db::table('user')->select();
            $teacher = Db::table('teacher')->select();
            $this->assign('users',$users);
            $this->assign('teacher',$teacher);
            return $this->fetch('admin');
        }else{
            return $this->fetch('login');
        }
    }
    public function upload(){
        // 获取表单上传文件 例如上传了001.jpg
        $file = request()->file('image');
        // 移动到框架应用根目录/public/uploads/ 目录下
        $info = $file->validate(['size'=>156780,'ext'=>'xls'])->move(ROOT_PATH . 'public','');
        if($info){
            $exts = $info->getExtension();
            $file = $info->getSaveName();
            $array = $this->getExcelData($file,$exts);
            if($array){
                 $this->success('新增成功', '/Administration');
            }else{
                $this->error('上传失败');
            }
        }else{
            // 上传失败获取错误信息
            $this->error($file->getError());
        }
    }
    public function getExcelData($filename,$exts){
        // $filename = iconv("utf-8","gb2312//IGNORE",$filename);
        Loader::import('PHPExcel.Classes.PHPExcel');
        Loader::import('PHPExcel.Classes.PHPExcel.IOFactory.PHPExcel_IOFactory');
        $PHPExcel = new PHPExcel();
        if($exts == 'xls'){
            $PHPReader = PHPExcel_IOFactory::createReader('Excel5');
        }else{
            $PHPReader = PHPExcel_IOFactory::createReader('Excel2007');
        }
        $PHPExcel = $PHPReader->load($filename);
        //获取表中的第一个工作表，如果要获取第二个，把0改为1，依次类推
        $currentSheet = $PHPExcel->getSheet(0);
        //获取总行数
        $allRow = $currentSheet->getHighestRow();
        //循环获取表中的数据，$currentRow表示当前行，从哪行开始读取数据，索引值从0开始
        for ($currentRow = 2; $currentRow <= $allRow; $currentRow++) {
            //从哪列开始，A表示第一列
            for ($currentColumn = 'B'; $currentColumn <= 'D'; $currentColumn++) {
                //数据坐标
                $address = $currentColumn . $currentRow;
                //读取到的数据，保存到数组$arr中
                $info = $currentSheet->getCell($address)->getValue();
                if($info){
                    if($currentColumn == 'B'){
                        $data[$currentRow-2]['userid'] = $info;
                    }
                    if($currentColumn == 'C'){
                        $data[$currentRow-2]['usernm'] = $info;
                    }
                    if($currentColumn == 'D'){
                        $data[$currentRow-2]['userpw'] = 123456;
                    }
                }
            }
        }
        @unlink ( $filename ); //删除上传的文件
        return Db::name('user')->insertAll($data);
    }
    public function delectMany(){
        $url = Request::instance()->url();
        $baseurl = Request::instance()->baseUrl();
        $query = str_replace($baseurl.'?','',$url);
        $query = str_replace('userid=','',$query);
        $query = explode("&",$query);
        foreach ($query as $key => $value) {
            Db::table('user')->where('userid',$value)->delete();
        }
        $this->success('删除成功', '/Administration');
    }
}