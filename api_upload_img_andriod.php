<?php
/* 
* @Author: yangjie-jerry
* @Date:   2017-04-27 17:41:15
* @Last Modified by:   yangjie-jerry
* @Last Modified time: 2017-04-28 08:59:12
* @安卓的上传图片
*/
define('IN_ECS', true);

require(dirname(dirname(__FILE__)) . '/includes/init_api.php');
require(dirname(__FILE__) . '/common/function.php');

$inputimage = new imageUpload('','');
$inputimage->stream2Image();

/** 
 * 图片类 
* @author http://blog.csdn.net/haiqiao_2010 
* @version 1.0 
* 
* PHP默认只识别application/x-www.form-urlencoded标准的数据类型。 
* 因此，对型如text/xml 或者 soap 或者 application/octet-stream 之类的内容无法解析，如果用$_POST数组来接收就会失败！ 
* 故保留原型，交给$GLOBALS['HTTP_RAW_POST_DATA'] 来接收。 
* 另外还有一项 php://input 也可以实现此这个功能 
* php://input 允许读取 POST 的原始数据。和 $HTTP_RAW_POST_DATA 比起来，它给内存带来的压力较小，并且不需要任何特殊的 php.ini 设置。php://input和 $HTTP_RAW_POST_DATA 不能用于 enctype="multipart/form-data"。 
*/  
class imageUpload {  
    const ROOT_PATH = '/data/images_new/';
    const FAIL_WRITE_DATA = 'Fail to write data';  
    //没有数据流  
    const NO_STREAM_DATA = 'The post data is empty';  
    //图片类型不正确  
    const NOT_CORRECT_TYPE = 'Not a correct image type';  
    //不能创建文件  
    const CAN_NOT_CREATE_FILE = 'Can not create file';  
    //上传图片名称  
    public $image_name;  
    //图片保存名称  
    public $save_name;  
    //图片保存路径  
    public $save_dir;  
    //目录+图片完整路径  
    public $save_fullpath;  
  
    /** 
     * 构造函数 
     * @param String $save_name 保存图片名称 
     * @param String $save_dir 保存路径名称 
     */  
    public function __construct($save_name, $save_dir) {  
        //set_error_handler ( $this->error_handler () );  
  
        //设置保存图片名称，若未设置，则随机产生一个唯一文件名  
        $this->save_name = $save_name ? $save_name :md5 (uniqid(rand (),true));  
        //设置保存图片路径，若未设置，则使用年/月/日格式进行目录存储  
        $this->n_dir =  self::ROOT_PATH.date ( 'Y/m/d/H/i/s/'); 
        //设置保存图片路径，若未设置，则使用年/月/日格式进行目录存储  
        $this->save_dir =  $save_dir ? (self::ROOT_PATH .$save_dir ):$_SERVER['DOCUMENT_ROOT'].$this->n_dir;
        $this->show_dir = $_SERVER['HTTP_HOST'].$this->n_dir.$this->save_name;
        $this->show_dir_n = $this->n_dir.$this->save_name;
        //echo $this->save_name;
        //echo $this->save_dir;exit;  
  
        //创建文件夹  
        @$this->create_dir ( $this->save_dir );  
        //设置目录+图片完整路径  
        $this->save_fullpath = $this->save_dir.$this->save_name; 
        
    }  
    //兼容PHP4  
    public function image($save_name) {  
        $this->__construct ( $save_name );  
    }  
  
    public function stream2Image() { 
        //二进制数据流 
       //file_put_contents('test.txt',$_REQUEST);exit;
        $data = base64_decode($_REQUEST['img']); 

        //$data_decode = base64_decode($data);
        //file_put_contents('test.txt',$data_decode);
        //exit;
        //print_r($data);exit; 
        //数据流不为空，则进行保存操作  
        if (! empty ( $data )) {  
            //创建并写入数据流，然后保存文件  
            //if (@$fp = fopen ( $this->save_fullpath,$this->save_name,'w+' )) {  
                //fwrite ( $fp, $data );  
                //fclose ( $fp ); 
            $path =  $this->save_fullpath.'.'.'jpg';
            if(file_put_contents($path,$data)){
                //$baseurl = "http://" . $_SERVER ["SERVER_NAME"] . ":" . $_SERVER ["SERVER_PORT"] . dirname ( $_SERVER ["SCRIPT_NAME"] ).substr($this->save_dir,1).$this->save_name;
                 //json_encode('222');
                //echo $baseurl;
                //var_dump($baseurl);exit
                $show_path = 'http://'.$this->show_dir.'.'.'jpg';
                if ( $this->getimageInfo ( $show_path )) {  
                     json('0',$this->show_dir_n.'.'.'jpg');  
                } else {  
                    json('-1',self::NOT_CORRECT_TYPE  );  
                }  
            } else {  
  
            }  
        } else {  
            //没有接收到数据流  
            json('-2',self::NO_STREAM_DATA );  
        }  
    }  
    /** 
     * 创建文件夹 
     * @param String $dirName 文件夹路径名 
     */  
    public function create_dir($dirName, $recursive = 1,$mode=0777) {  
        ! is_dir ( $dirName ) && mkdir ( $dirName,$mode,$recursive );  
    }  
    /** 
     * 获取图片信息，返回图片的宽、高、类型、大小、图片mine类型 
     * @param String $imageName 图片名称 
     */  
    public function getimageInfo($imageName = '') {  
        $imageInfo = getimagesize ( $imageName );  
        //print_r($imageInfo);exit;
        if ($imageInfo !== false) {  
            $imageType = strtolower ( substr ( image_type_to_extension ( $imageInfo [2] ), 1 ) );  
            $imageSize = filesize ( $imageInfo );
            //print_r($imageInfo);exit;  
            return $info = array ('width' => $imageInfo [0], 'height' => $imageInfo [1], 'type' => $imageType, 'size' => $imageSize, 'mine' => $imageInfo ['mine'] );  
        } else {  
            //不是合法的图片  
            return false;  
        }  
  
    }  
  
    /*private function error_handler($a, $b) { 
     echo $a, $b; 
    }*/  
  
}  

?>
