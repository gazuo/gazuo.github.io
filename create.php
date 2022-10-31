<?php
require "config.php";
require "function.php";

if ($config['type'] == 'abc') {
    $saveFile = 'urlsabc.php';
} elseif ($config['type'] == '123') {
    $saveFile = 'urls123.php';
} else {
    exit('config.php配置文件错误，请检查。');
}

// 检查本地文件是否可读写
if (!is_writable($saveFile) || !is_readable($saveFile)) {
    die('请将urlsabc.php和urls123.php文件设置为777权限');
}

//引入保存网址的php文件
$arr = require $saveFile;

// 判断是生成还是跳转
@$id = trim($_GET['id']);
if ($id) {
    //跳转
    if ($arr[$id]) {
        header('location:' . $arr[$id]);
    }
    $message = "短网址不存在";
    echo json_encode(array('code' => 8404, 'message' => $message));
    exit;
} else {
    //生成
    header("Access-Control-Allow-Origin:*");
    header("Content-Type:text/html,application/json; charset=utf-8");
    $url = isset($_GET['url']) ? trim($_GET['url']) : trim($_POST['url']);
    if ($url == '') {
        $message = '请输入网址';
        echo json_encode(array('code' => 8001, 'message' => $message));
        exit;
    }
    /*判断是否正则为正确的网址
    $regex = "/^(http(s)?:\/\/)([\w\-]+\.)+[\w\-]+(\/[\w\-.\/?%&=#]*)?$/i";
    if (!preg_match($regex, $url)) {
        $message = '请输入正确的网址';
        echo json_encode(array('code' => 8002, 'message' => $message));
        exit;
    }*/
    if (stripos($url, 'http') === false) {
        $url = 'http://' . $url;
    }
    if (stripos($url, ".") < 8 || stristr($url, '.') == false) {
        $message = '请输入正确的网址';
        echo json_encode(array('code' => 8002, 'message' => $message));
        exit;
    }
    //判断是否是黑名单域名
    if (!checkBlackList($url, $config['blackList'])) {
        $message = '抱歉，黑名单域名无法缩短';
        echo json_encode(array('code' => 8003, 'message' => $message));
        exit;
    }
    //限制网址长度为1000字符
    if(strlen($url) > 1000){
        $message = '网址长度超过限制';
        echo json_encode(array('code' => 8004, 'message' => $message));
        exit;
    }
    //检查是否已存在重复网址
    $find = array_search($url, $arr);
    if ($find !== false) {
        $id = $find;   //返回之前的短网址
    } else {
        //原来没有新插入
        if ($config['type'] == 'abc') {
            $id = createId(5);    //随机生成5位小数字母+数字
            $id = checkId($arr, $id);
            //原来没有新插入
            $arr[$id] = $url;
        } elseif ($config['type'] == '123') {
            $arr[] = $url;
            $id = count($arr) - 1;

        }
        $a = '<?php' . PHP_EOL . 'return ' . var_export($arr, true) . ';';
        file_put_contents($saveFile, $a);
    }
    $message = "短网址已生成";
    $shortUrl = ($config['use_rewrite'] == 1) ? "{$config['site']}/{$id}" : "{$config['site']}/create.php?id={$id}";
    echo json_encode(array('code' => 200, 'message' => $message, 'shortUrl' => $shortUrl));
    exit;
}