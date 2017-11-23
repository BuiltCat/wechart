<?php
namespace app\index\controller;
use \think\Request;
use \think\Db;
use \think\Session;

class Admin extends \think\Controller
{
    public function index(){
        return $this->fetch('index');
    }
}