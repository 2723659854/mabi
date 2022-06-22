<?php
require_once __DIR__ .'/src/Mabi.php';
require_once __DIR__ . '/vendor/autoload.php';
use src\mabi\Mabi;
//引入需要用到的类
use Workerman\Worker;
use Workerman\Connection\TcpConnection;
use Workerman\Protocols\Http\Request;

// 创建一个Worker监听2345端口，使用http协议通讯
$http_worker = new Worker("http://0.0.0.0:2345");

// 启动4个进程对外提供服务
$http_worker->count = 4;

$http_worker->name= 'my_http';
// 接收到浏览器发送的数据时回复hello world给浏览器,使用的是tcp连接，参数使用http的request方法
$http_worker->onMessage = function(TcpConnection $connection, Request $request)
{
    $mabi=new Mabi();
    $mabi->say();
    $data=$request->get('name','tom');
    echo "\r\n";
    $connection->send($data);
};

// 运行worker
Worker::runAll();