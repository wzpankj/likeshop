<?php /*a:2:{s:66:"D:\phpstudy_pro\WWW\dcweb\app\admin\view\printer\config\lists.html";i:1676699056;s:53:"D:\phpstudy_pro\WWW\dcweb\app\admin\view\layout2.html";i:1676699056;}*/ ?>
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

    <div class="layui-tab-content" style="padding: 20px 15px;">

      <div class="layui-form">
        <table class="layui-table">
          <colgroup>
            <col>
            <col>
          </colgroup>
          <thead>
          <tr>
            <th>设备类型</th>
            <th>操作</th>
          </tr>
          </thead>
          <tbody>
          <tr>
            <td>易联云</td>
            <td><button class="layui-btn layui-btn-sm layuiadmin-btn-config <?php echo htmlentities($view_theme_color); ?>" data-type="edit">设置</button></td>
          </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<script>
  layui.use(['table', 'form', 'element'], function(){
    var $ = layui.$
            ,form = layui.form
            ,table = layui.table
            ,element = layui.element;

    $('.layui-btn.layuiadmin-btn-config').on('click', function(){
      var type = $(this).data('type');
      active[type] ? active[type].call(this) : '';
    });

    var active = {
      //打印设置
      edit: function(){
        var index = layer.open({
          type: 2
          ,title: '打印设置'
          ,content: '<?php echo url("printer.config/edit"); ?>'
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
                url:'<?php echo url("printer.config/edit"); ?>',
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



    table.on('tool(like-table-lists)', function(obj)
    {
      //编辑模板
      if(obj.event === 'edit') {
        layer.open({
          type: 2
          ,title: '编辑模板'
          ,content: '<?php echo url("printer.config/edit"); ?>'
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
                url:'<?php echo url("printer.config/edit"); ?>',
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
    });

  });
</script>
</body>
</html>