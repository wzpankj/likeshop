<?php /*a:2:{s:66:"D:\phpstudy_pro\WWW\dcweb\app\shop\view\zhuohao\zhuohao\lists.html";i:1679116051;s:52:"D:\phpstudy_pro\WWW\dcweb\app\shop\view\layout1.html";i:1676699056;}*/ ?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title><?php echo url(); ?></title>
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=0">
    <link rel="stylesheet" href="/static/lib/layui/css/layui.css?v=<?php echo htmlentities($front_version); ?>">
    <link rel="stylesheet" href="/static/admin/css/app.css">
    <link rel="stylesheet" href="/static/admin/css/like.css">
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
            <p>桌子管理，用于生成小程序码</p>
            <p>编号0为店铺小程序码</p>
          </div>
        </div>
      </div>
    </div>

    <div class="layui-tab-content" style="padding: 0 15px 20px;">
      <div style="padding-bottom: 10px;padding-top: 20px;" class="add">
        <button class="layui-btn layui-btn-sm layuiadmin-btn-printer <?php echo htmlentities($view_theme_color); ?>" data-type="add">新增桌子</button>
      </div>

      <table id="like-table-lists" lay-filter="like-table-lists"></table>
      <!--会员信息-->
      <script type="text/html" id="code">
          <img src="/{{d.code}}" style="height:80px;width: 80px" class="image-show">
      </script>
      <script type="text/html" id="table-operation">
        <a class="layui-btn layui-btn-normal layui-btn-sm" lay-event="edit">编辑</a>
        <a class="layui-btn layui-btn-danger layui-btn-sm" lay-event="del">删除</a>
      </script>
    </div>
  </div>
</div>

<script>
  layui.use(['table', 'form', 'element'], function(){
    var $ = layui.$
            ,form = layui.form
            ,table = layui.table
            ,element = layui.element;

    $('.layui-btn.layuiadmin-btn-printer').on('click', function(){
      var type = $(this).data('type');
      active[type] ? active[type].call(this) : '';
    });

    //获取列表
    getList(); // 初始加载获取销售中的商品
    function getList() {
      like.tableLists('#like-table-lists', '<?php echo url("zhuohao.zhuohao/lists"); ?>', [
        {field: 'name', title: '名称'}
        ,{field: 'number', title: '编号'}
        ,{field: 'code', title: '小程序码',templet:'#code',}
        ,{field: 'create_time', title: '创建时间'}
        ,{fixed: 'right', title: '操作', align: 'center', width:300, toolbar: '#table-operation'}
      ]);
    }

    //事件
    var active = {
      //新增打印机
      add: function(){
        var index = layer.open({
          type: 2
          ,title: '新增桌子'
          ,content: '<?php echo url("zhuohao.zhuohao/add"); ?>'
          ,area: ['90%', '90%']
          ,btn: ['保存', '取消']
          ,maxmin: true
          ,yes: function(index, layero){
            var iframeWindow = window['layui-layer-iframe'+ index]
                    ,submitID = 'add-submit'
                    ,submit = layero.find('iframe').contents().find('#'+ submitID);
            //监听提交
            iframeWindow.layui.form.on('submit('+ submitID +')', function(data){
              console.log(data)
              var field = data.field;

              like.ajax({
                url:'<?php echo url("zhuohao.zhuohao/add"); ?>',
                data:field,
                type:"post",
                success:function(res)
                {
                  if(res.code == 1)
                  {
                    layui.layer.msg(res.msg, {
                      offset: '15px'
                      , icon: 1
                      , time: 1000
                    },function () {
                      window.location.href = window.location.href;
                    });

                  }
                }
              });
            });

            submit.trigger('click');
          }
        });
      },
    };




    //监听工具条
    table.on('tool(like-table-lists)', function(obj){
      var id = obj.data.id;
      var name = obj.data.name;

      //编辑打印机
      if(obj.event === 'edit') {
        layer.open({
          type: 2
          ,title: '编辑桌子'
          ,content: '<?php echo url("zhuohao.zhuohao/edit"); ?>?id='+id
          ,area: ['90%', '90%']
          ,btn: ['保存', '取消']
          ,maxmin: true
          ,yes: function(index, layero){
            var iframeWindow = window['layui-layer-iframe'+ index]
                    ,submitID = 'edit-submit'
                    ,submit = layero.find('iframe').contents().find('#'+ submitID);
            //监听提交
            iframeWindow.layui.form.on('submit('+ submitID +')', function(data){
              var field = data.field;
              like.ajax({
                url:'<?php echo url("zhuohao.zhuohao/edit"); ?>',
                data:field,
                type:"post",
                success:function(res)
                {
                  if(res.code == 1)
                  {
                    layui.layer.msg(res.msg, {
                      offset: '15px'
                      , icon: 1
                      , time: 1000
                    },function () {
                      window.location.href = window.location.href;
                    });

                  }
                }
              });
            });
            submit.trigger('click');
          }
        });
      }
      //删除
      if(obj.event === 'del') {
        layer.confirm('确定删除桌子:'+'<span style="color: red">'+name+'</span>', function(index){
          like.ajax({
            url:'<?php echo url("zhuohao.zhuohao/del"); ?>',
            data:{"id":id},
            type:"post",
            success:function(res)
            {
              if(res.code === 1) {
                layui.layer.msg(res.msg, {offset:'15px', icon:1 ,time: 1000});
                table.reload('like-table-lists', { where: [] });
              } else {
                layui.layer.msg(res.msg, {offset:'15px', icon:2 ,time: 1000});
              }
            }
          });
        })
      }
      //测试打印
      if(obj.event === 'test_print') {
        layer.confirm('确定要测试:'+'<span style="color: red">'+name+'</span>'+'的打印吗?', function(index){
          like.ajax({
            url:'<?php echo url("printer.printer/testPrint"); ?>',
            data:{"id":id},
            type:"post",
            success:function(res)
            {
              if(res.code === 1) {
                layui.layer.msg(res.msg, {offset:'15px', icon:1 ,time: 1000});
                table.reload('like-table-lists', { where: [] });
              } else {
                layui.layer.msg(res.msg, {offset:'15px', icon:2 ,time: 1000});
              }
            }
          });
        })
      }
    });

  });
</script>
</body>
</html>