<?php
namespace App\Admin\Controller;
use App\Model\AdminNode;
use App\Model\AdminRight;
use App\Model\AdminRoles;
use Kernel\Controller;
use App\Model\Menu;
use Service\File;

/**
 * 后台基础类
 * Class Base
 * @package App\Admin\Controller
 */
class Base extends Controller{
	# 构造函数
	function __init(){
	    # 判断是否已经登陆过
        if($_SESSION['admin']['user']['id']<1){
            # 重定向到登陆页面
            redirect('/Auth/login.html');
        }
        # 获取后台导航
        $this -> assign('menu_list',Menu::where(['pid'=>0])
//            -> remember(3600*24)
            -> get());
	    # 权限验证
        if(!$this -> __AuthCache()){
            $this -> error('权限不足');
        }

	}
	# 权限检查
	public function __AuthCache(){
        # 获取控制器
        $controller = CONTROLLER_NAME;
        # 获取操作
        $action = ACTION_NAME;

	    # 判断是否为超级管理员
        if($_SESSION['admin']['user']['roles']=='-1'){
            return true;
        }

        # 获取用户的角色
        $roles = AdminRoles::where(['id'=>$_SESSION['admin']['user']['roles']]) -> first();
        # 获取权限
        $right = explode(',',$roles -> right);
        # 获取结点
        $admin_right = AdminRight::whereIn('id',$right) -> pluck('node','id');
        # 获取结点
        if(AdminNode::where(['controller_name'=>$controller,'action_name'=>$action])
            -> whereIn('id',explode(',',$admin_right))
            -> first()){
            # 把权限结点更新
            ($node = AdminNode::whereIn('id',$admin_right) -> get()) && $node = $node -> toArray();
            $_SESSION['admin']['node'] = $node;
            return true;
        }else{
            return false;
        }
    }
    public function __clear_cache()
    {
        # 清空数据缓存
        File::remove_dir(CACHE_DATA);
        # 清空类映射缓存
        File::remove_dir(CLASS_PATH);
        # 清空日志
        File::remove_dir(CACHE_LOG);
        # 清空回话文件
        File::remove_dir(CACHE_SESSION);
        # 清空模板编译缓存
        File::remove_dir(CACHE_VIEW);
        # 提示完成
        $this -> success('清空缓存完成');
    }
    public function message($message,$href='history.go(-1);')
    {
        exit('<script type="text/javascript">alert(\''.$message.'\');'.($href=='history.go(-1);')?$href:'window.location="'.$href.'";</script>');
    }
    protected $icon_data = [
        '首页'=>'&#xe68e;',
        '赞'=>'&#xe6c6;',
        '踩'=>'&#xe6c5;',
        '男'=>'&#xe662;',
        '女'=>'&#xe661;',
        '空心相机'=>'&#xe660;',
        '实心相机'=>'&#xe65d;',
        '菜单水平'=>'&#xe65f;',
        '菜单竖直'=>'&#xe671;',
        '返回'=>'&#xe65c;',
        '热'=>'&#xe756;',
        '等级'=>'&#xe735;',
        '人民币'=>'&#xe65e;',
        '美元'=>'&#xe659;',
        '位置'=>'&#xe715;',
        '文档'=>'&#xe705;',
        '书'=>'&#xe705;',
        '检验'=>'&#xe6b2;',
        '笑脸'=>'&#xe6af;',
        '购物车-灰线'=>'&#xe698;',
        '购物车-黑线'=>'&#xe657;',
        '星'=>'&#xe658;',
        '上一页'=>'&#xe65a;',
        '下一页'=>'&#xe65b;',
        '上传空心'=>'&#xe681;',
        '上传实心'=>'&#xe67c;',
        '文件夹'=>'&#xe7a0;',
        '应用'=>'&#xe857;',
        '播放暂停'=>'&#xe651;',
        '音乐'=>'&#xe6fc;',
        '视频'=>'&#xe6ed;',
        '语音'=>'&#xe688;',
        '喇叭'=>'&#xe645;',
        '对话'=>'&#xe611;',
        '消息'=>'&#xe611;',
        '设置-黑'=>'&#xe614;',
        '修改'=>'&#xe614;',
        '隐身'=>'&#xe60f;',
        '搜索'=>'&#xe615;',
        '分享'=>'&#xe641;',
        '刷新'=>'&#x1002;',
        '加载中1'=>'&#xe63d;',
        '加载中2'=>'&#xe63e;',
        '设置-白'=>'&#xe620;',
        '引擎'=>'&#xe628;',
        '问卷错号'=>'&#x1006;',
        '错'=>'&#x1007;',
        'star'=>'&#xe600;',
        '圆点'=>'&#xe617;',
        '客服'=>'&#xe606;',
        '发布'=>'&#xe609;',
        '21cake_list'=>'&#xe60a;',
        '图表'=>'&#xe62c;',
        '正确'=>'&#x1005;',
        '换肤'=>'&#xe61b;',
        '在线'=>'&#xe610;',
        '右右'=>'&#xe602;',
        '左左'=>'&#xe603;',
        '表格'=>'&#xe62d;',
        '树'=>'&#xe62e;',
        '上传'=>'&#xe62f;',
        '添加1'=>'&#xe61f;',
        '下载'=>'&#xe601;',
        '选择模板'=>'&#xe630;',
        '工具'=>'&#xe631;',
        '添加2'=>'&#xe654;',
        '编辑'=>'&#xe642;',
        '删除'=>'&#xe640;',
        '向下'=>'&#xe61a;',
        '文件'=>'&#xe621;',
        '布局'=>'&#xe632;',
        '对勾'=>'&#xe618;',
        '添加3'=>'&#xe608;',
        '添加4'=>'&#xe608;',
        '翻页'=>'&#xe633;',
        '404'=>'&#xe61c;',
        '轮播组图'=>'&#xe634;',
        '帮助'=>'&#xe607;',
        '代码1'=>'&#xe635;',
        '进水'=>'&#xe636;',
        '关于'=>'&#xe60b;',
        '向上'=>'&#xe619;',
        '日期'=>'&#xe637;',
        '文件1'=>'&#xe637;',
        'top'=>'&#xe604;',
        '好友请求'=>'&#xe612;',
        '对'=>'&#xe605;',
        '窗口'=>'&#xe638;',
        '表情'=>'&#xe60c;',
        '正确1'=>'&#xe616;',
        '我的好友'=>'&#xe613;',
        '文件下载'=>'&#xe61e;',
        '图片'=>'&#xe60d;',
        '链接'=>'&#xe64c;',
        '记录'=>'&#xe60e;',
        '文件夹1'=>'&#xe622;',
        'font-strikethrough'=>'&#xe64f;',
        'unlink'=>'&#xe64d;',
        '编辑_文字'=>'&#xe639;',
        '三角'=>'&#xe623;',
        '单选框-候选'=>'&#xe63f;',
        '单选框-选中'=>'&#xe643;',
        '居中对齐'=>'&#xe647;',
        '右对齐'=>'&#xe648;',
        '左对齐'=>'&#xe649;',
        '勾选框（未打勾）'=>'&#xe626;',
        '勾选框（已打勾）'=>'&#xe627;',
        '加粗'=>'&#xe62b;',
        '聊天-对话'=>'&#xe63a;',
        '文件夹-反'=>'&#xe624;',
        '手机'=>'&#xe63b;',
        '表情1'=>'&#xe650;',
        'html'=>'&#xe64b;',
        '表单'=>'&#xe63c;',
        'tab'=>'&#xe62a;',
        'emw_代码'=>'&#xe64e;',
        '字体-下划线'=>'&#xe646;',
        '三角1'=>'&#xe625;',
        '图片1'=>'&#xe64a;',
        '斜体'=>'&#xe644;',
    ];
}
