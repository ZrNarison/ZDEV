$(document).ready(function(){
	
	$('#add-posteur').click(function(){
		const index =$("#ad_posteurs .form-group").length;
		const temp = $('#ad_posteurs').data('prototype').replace(/__name__/g,index);
		$('#ad_posteurs').append(temp);	
		handleDeleteButtons();	
	});	
	function handleDeleteButtons(){
		$('button[data-action="delete"]').click(function(){
			const target = this.dataset.target;
			$(target).remove();
		})
	}
	handleDeleteButtons();
	$('section').hide();
	$("footer").on('mouseenter',function(){
		$('section').show();
	});
	$("body"),$("nav").on("mouseenter",function(){
		$('section').hide();
	});
	let input = document.querySelector("#passwordlogin");
	let Btn = document.querySelector("#logineye");
	Btn.onclick=function(){
	   if(input.type === "password"){
		  input.type ="text";
		  Btn.classList.add("active");
	   }else{
		  input.type="password";
		  Btn.classList.remove("active");
	   }
	}
})
