<?php /*a:2:{s:67:"D:\phpstudy_pro\WWW\dcweb\app\admin\view\printer\printer\lists.html";i:1676699056;s:53:"D:\phpstudy_pro\WWW\dcweb\app\admin\view\layout2.html";i:1676699056;}*/ ?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title><?php echo url(); ?></title>
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=0">
    <link rel="stylesheet" href="/static/lib/layui/css/layui.css?v=<?php echo htmlentities($front_version); ?>">
    <link rel="stylesheet" href="/static/admin/css/app.css?v=<?php echo htmlentities($front_version); ?>">
    <link rel="stylesheet" href="/static/admin/css/like.css?v=<?php echo htmlentities($front_version); ?>">
    <script src="/static/lib/layui/layui.js?v=<?php echo htmlentities($front_version); ?>"></script>
    <script src="/static/admin/js/app.js"></script>
</head>
<body>
<?php echo $js_code; ?>
<script src="/static/admin/js/jquery.min.js"></script>
<script src="/static/admin/js/function.js"></script>

<style>
  .layui-table-cell {
    height: auto;
  }
</style>
<div class="wrapper">
  <div class="layui-card">

    <!-- 操作提示 -->
    <div class="layui-card-body">
      <div class="layui-collapse" style="border:1px dashed #c4c4c4">
        <div class="layui-colla-item">
          <h2 class="layui-colla-title like-layui-colla-title">操作提示</h2>
          <div class="layui-colla-content layui-show">
            <p>*易联云打印机推荐购买K4、K6、W1等机型；</p>
            <p>*平台可以查看门店添加打印机；</p>
          </div>
        </div>
      </div>
    </div>

    <!-- 搜索-->
    <div class="layui-card-body layui-form">
      <div class="layui-form-item">
        <div class="layui-inline">
          <label class="layui-form-label">所属门店：</label>
          <div class="layui-input-inline">
            <input type="text" name="shop_name" id="shop_name" autocomplete="off" class="layui-input">
          </div>
        </div>
        <div class="layui-inline">
          <button class="layui-btn layui-btn-sm layui-btn-normal" lay-submit lay-filter="search">查询</button>
          <button class="layui-btn layui-btn-sm layui-btn-primary" lay-submit lay-filter="clear-search">重置</button>
        </div>
      </div>
    </div>

    <div class="layui-tab-content" style="padding: 0 15px 20px;">

      <table id="like-table-lists" lay-filter="like-table-lists"></table>
    </div>
  </div>
</div>

<script>
  layui.use(['table', 'form', 'element'], function(){
    var $ = layui.$
            ,form = layui.form
            ,table = layui.table
            ,element = layui.element;


    //监听搜索
    form.on('submit(search)', function(data){
      var field = data.field;
      //执行重载
      table.reload('like-table-lists', {
        where: field,
        page: {curr: 1}
      });
    });

    //清空查询
    form.on('submit(clear-search)', function(){
      $('#shop_name').val('');
      //刷新列表
      table.reload('like-table-lists', {
        where: [], page: {curr: 1}
      });
    });


    $('.layui-btn.layuiadmin-btn-printer').on('click', function(){
      var type = $(this).data('type');
      active[type] ? active[type].call(this) : '';
    });

    //获取列表
    getList(); // 初始加载获取销售中的商品
    function getList() {
      like.tableLists('#like-table-lists', '<?php echo url("printer.printer/lists"); ?>', [
        {field: 'name', title: '打印机名称'}
        ,{field: 'shop_name', title: '所属门店'}
        ,{field: 'type_name', title: '设备类型'}
        ,{field: 'machine_code', title: '终端号'}
        ,{field: 'status_desc', title: '打印状态'}
        ,{field: 'create_time', title: '创建时间'}
      ]);
    }

  });
</script>
</body>
</html>