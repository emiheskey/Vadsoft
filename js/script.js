$(document).ready(function(e) {

	$("#btn_register").on("click",function(e) {
		e.preventDefault();
		
		$.ajax({
			url:"login.php",
			type:"POST",
			data:$("#frm_register").serialize(),
			beforeSend: function() {
				$("#return_status_msg").html('<div>Please wait</div><br clear="all">');
			},
			success: function(data) {
				if (data==1) {
					location.href = "proceed";
				} else {
					$("#return_status_msg").html('<div style="+DivStyle+">'+data+'</div><br clear="all">');
				}
			}
		});
	});
	
	
	$("#btn_login").on("click",function(e) {
		e.preventDefault();
		
		$.ajax({
			url:"login.php?mode=login",
			type:"POST",
			data:$("#frm_login").serialize(),
			beforeSend: function() {
				$("#btn_login").html('<i class="fa fa-spinner fa-spin" style="font-size:20px; text-align:center;"></i>');
				$("#btn_login").prop('disabled', true);
			},
			success: function(data) {
				if (data==1) {
					location.href = "v/";
					$("#btn_login").html('login successful..');
				} else {
					$("#btn_login").html('Sign In');
					$("#btn_login").prop('disabled', false);
					$("#return_status_msg").html('<div class="alert alert-danger">'+data+'</div>');
				}
			}
		});
	});

	$("#btn_setup_login").on("click",function(e) {
		e.preventDefault();
		$.ajax({
			url:"login.php?mode=setup_login",
			type:"POST",
			data:$("#frm_setup_login").serialize(),
			beforeSend: function() {
				$("#btn_setup_login").html('<i class="fa fa-spinner fa-spin" style="font-size:20px; text-align:center;"></i>');
				$("#btn_setup_login").prop('disabled', true);
			},
			success: function(data) {
				if (data==1) {
					$("#btn_setup_login").html('login successful..');
					location.href = "v/";
				} else {
					$("#btn_setup_login").html('Sign In');
					$("#btn_setup_login").prop('disabled', false);
					$("#return_status_msg").html('<div class="alert alert-danger">'+data+'</div>');
				}
			}
		});
	});


	$("#btn_setup").on("click",function(e) {
		e.preventDefault();
		$.ajax({
			url:"verifycode.php?mode=setup_user",
			type:"POST",
			data:$("#frm_setup_org").serialize(),
			beforeSend: function() {
				$("#btn_setup").html('<i class="fa fa-spinner fa-spin" style="font-size:20px; text-align:center;"></i>');
				$("#btn_setup").prop('disabled', true);
			},
			success: function(data) {
				if (data==1) {
					location.href = "v/ ";
				} else {
					$("#btn_setup").html('Setup');
					$("#btn_setup").prop('disabled', false);
					$("#return_status_msg").html('<div class="alert alert-danger">'+data+'</div>');
				}
			} 
		});
	});

	
	$("#btn_logout").on("click",function(e) {
		e.preventDefault();
		location.href = "./";
	});
	
	
});





function addRow(tableID) {
	var table = document.getElementById(tableID);
	var rowCount = table.rows.length;
	var row = table.insertRow(rowCount);
	var colCount = table.rows[0].cells.length;
	for(var i=0; i<colCount; i++) {
		var newcell = row.insertCell(i);
		newcell.innerHTML = table.rows[0].cells[i].innerHTML;
		switch(newcell.childNodes[0].type) {
			case "text":
					newcell.childNodes[0].value = "";
					break;
			case "checkbox":
					newcell.childNodes[0].checked = false;
					break;
			case "select-one":
					newcell.childNodes[0].selectedIndex = 0;
					break;
		}
	}
}
 
function deleteRow(tableID) {
	try {
	var table = document.getElementById(tableID);
	var rowCount = table.rows.length;

	for(var i=0; i<rowCount; i++) {
		var row = table.rows[i];
		var chkbox = row.cells[0].childNodes[0];
		if(null != chkbox && true == chkbox.checked) {
			if(rowCount <= 1) {
				alert("Cannot delete all the rows.");
				break;
			}
			table.deleteRow(i);
			rowCount--;
			i--;
		}
	}
	}catch(e) {
		alert(e);
	}
}