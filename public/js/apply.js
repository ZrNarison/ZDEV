$(document).ready(function(){
	var ch =$("#checkbox");
	var psw =$("#passwordlogin");
	
	ch.click(function(){
	   if(ch.prop("checked")){
		  psw.attr("type","text");
	   }else{
		  psw.attr("type","password");
	   }
	});
	$('#Submit').click(function(){
		const index = ('#ad_posteurs div.form-group').length;
		const temp = $('#ad_posteurs').data('prototype').replace(/_name_/g,index);
		// console.log(temp);
		$('#ad_posteurs').append(temp);	
		handleDeleteButtons();	
	});	
	function handleDeleteButtons(){
		$('button[data-action-"delete"]').click(function(){
			const target = this.dataset.target;
			console.log(target);
		})
	}
});
