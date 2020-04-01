<?php
/**
 * Created by PhpStorm.
 * User: lenovo
 * Date: 2016/12/4
 * Time: 20:52
 */
header('content-Type:text/html;charset=utf-8');
define("BIND_MODULE","Home");
//Ӧ�ó���Ŀ¼��λ��
define("APP_PATH","application/");
//��������ģʽ(Ĭ��false)
define("APP_DEBUG",true);
//�Ƿ���Ŀ¼��ȫ(Ĭ��true)
define("BUILD_DIR_SECURE",false);
//����ThinkPHP����ļ�
include_once 'library/ThinkPHP/ThinkPHP.php';