<extend name="Common:demo" />
<block name='main'>
	<div class="row">
        <div class="col-md-12">
            <h1 class="page-header">
                Forms Page <small class='title'><?php echo  $title;?>编辑</small>
            </h1>
        </div>
    </div> 
	<div class="panel-body">
        <div class="alert alert-success" style="display:none">
          <strong>操作成功!</strong><em class='success_msg'> You successfully read this important alert message.</em>
        </div>
        <div class="alert alert-danger" style="display:none">
          <strong>操作失败!</strong><em class='error_msg'> Change a few things up and try submitting again.</em>
        </div>
		<form action='{:U()}' method="post">
		<input type="hidden" name="id" value="{$id}">
        <?php foreach($fields as $item):
            if($item['key']=='pri'){
                    continue;
                }
        ?>
        <?php if(strpos($item['comment'],'textarea')!==false):?>
            <div class="form-group">
                <label><?php echo  $item['name'];?>:</label>
                <textarea class="form-control" rows="3" name="<?php echo $item['field'];?>">{$<?php echo $item['field'];?>}</textarea>
            </div>
        <?php elseif(strpos($item['comment'],'text')!==false):?>
            <div class="form-group">
                <label><?php echo  $item['name'];?>:</label>
                <input class="form-control" placeholder="请输入<?php echo  $item['name'];?>" name='<?php echo $item['field'];?>' value="{$<?php echo $item['field'];?>}">
            </div>
        <?php elseif(strpos($item['comment'],'radio')!==false):
            $str  = ltrim(strstr($item['comment'],'|'),'|');  //1=是,0=否
            $arr  = str2arr($str);
        ?>
            <div class="form-group">
                <label><?php echo $item['name'];?>:</label>
                <?php foreach($arr as $a):
                    $values=str2arr($a,'=');
                ?>
                <label class="radio-inline">
                    <input type="radio" name="<?php echo $item['field'];?>" class="optionsRadiosInline1" value="<?php echo $values[0];?>"><?php echo $values[1];?>
                </label>
                <?php endforeach;?>
            </div>
        <?php elseif(strpos($item['comment'],'file')!==false):?>
            <div class="form-group">
                <label><?php echo $item['name'];?>:</label>
                <input type="file" name='<?php echo $item['field'];?>'>
            </div>
        <?php endif;?>
        <?php endforeach;?>
			<button type="submit" class="btn btn-default post_json">提交</button>
		</form>
	</div>
	<script type="text/javascript" src="__JS__/jquery-1.11.2.min.js"></script>
	<script type="text/javascript">
		$(function(){
			$('.optionsRadiosInline1').val([{$status|default=1}]);
		});
         //>>添加点击批量删除的响应
          $('.post_json').click(function(){
            var form_data=$(this).closest('form').serialize();
            var url=$(this).closest('form').attr('action');
            console.debug(form_data);
            $.post(url,form_data,function(data){
              console.debug(data);
              if(data.status==0){
                $('.error_msg').html(data.info);
                $('.alert-danger').show();
                setTimeout(function(){
                  $('.alert-danger').hide();
                },1500);
              }else{
                $('.success_msg').html(data.info);
                $('.alert-success').show();
                setTimeout(function(){
                  location.href=data.url;
                },1500)
              }
            });
            return false;
          });
	</script>
    <script type="text/javascript">
    //选中菜单栏
    var title=$('.title').html();
    var a=$("#main-menu a");
    // console.debug(title);  
    a.each(function(){
      if($(this).text()==title){
        // console.debug($(this).closest('ul').closest('li'));
        $(this).closest('ul').addClass('in');
        $(this).closest('ul').closest('li').addClass('active');
        $(this).addClass('active-menu');
      }
    });
  </script>
  </block>