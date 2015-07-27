$(function(){
	$('#login').on('focus',function(){if(this.value=='请输入用户名'||this.value=='enter_your_username'){this.value='';}}).on('blur',function(){if(this.value==''){if($('form p:first').find('label').html()=='username:'){this.value='enter_your_username'}else{this.value='请输入用户名'}}});
	$('#password').on('focus',function(){if(this.value=='your_password'){this.value='';}}).on('blur',function(){if(this.value==''){this.value='your_password';}});
	$('#verify').on('focus',function(){if(this.value=='请输入验证码'||this.value=='code'){this.value='';}}).on('blur',function(){if(this.value==''){this.value='code';}});
});