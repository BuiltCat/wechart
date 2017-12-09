<?php
namespace app\index\controller;
use \think\Request;
use \think\Db;
use \think\Session;

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
        }else{
            // 上传失败获取错误信息
            echo $file->getError();
        }
    }
     public function getExcelData($filename, $exts){
        require_once(ROOT_PATH ."PHPExcal".DS."PHPExcel.php");
        // 不同类型的文件导入不同的类
        if ($exts == 'xls') {
            import("Org.Util.PHPExcel.Reader.Excel5");
            $PHPReader = new \PHPExcel_Reader_Excel5();
            var_dump(1);
        } else if ($exts == 'xlsx') {
            import("Org.Util.PHPExcel.Reader.Excel2007");
            $PHPReader = new \PHPExcel_Reader_Excel2007();
            var_dump(1);
        }
        //载入文件
        $PHPExcel = $PHPReader->load($filename);
        //获取表中的第一个工作表，如果要获取第二个，把0改为1，依次类推
        $currentSheet = $PHPExcel->getSheet(0);
        //获取总列数
        $allColumn = $currentSheet->getHighestColumn();
        //获取总行数
        $allRow = $currentSheet->getHighestRow();
        //循环获取表中的数据，$currentRow表示当前行，从哪行开始读取数据，索引值从0开始
        for ($currentRow = 1; $currentRow <= $allRow; $currentRow++) {
            //从哪列开始，A表示第一列
            for ($currentColumn = 'A'; $currentColumn <= $allColumn; $currentColumn++) {
                //数据坐标
                $address = $currentColumn . $currentRow;
                //读取到的数据，保存到数组$arr中
                $data[$currentRow][$currentColumn] = $currentSheet->getCell($address)->getValue();
            }
        }
        @unlink ( $filename ); //删除上传的文件
        return $data;
    }
}