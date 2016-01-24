function handleMSG(returned, msg){
	if(returned != 1) {
		console.log(returned);
		if (returned.length > 140) {
			Materialize.toast("Unexpected error. Check console for more info",3000,'red');

		} else {
			Materialize.toast(""+returned, 3000, 'red');
		}
		
	} else {
		Materialize.toast(""+msg, 3000, 'green')
	}
}

function loadSnapshotMgr(uid) {
	var name = "Generic";
	console.log("Trying to fetch snapshots list for "+name+" ("+uid+")");
	$("#snap_btn").attr("onclick","newSnapshot(\""+uid+"\");");
	$.post( "ajax.php", { GET_SNAPS: uid }).done(function( data ) {
					 	console.log("Snapshot list is : "+data);
					  	$("#snap_list").html(data);
					  	$("#snap_name").val(name);
					  	$("#snapshot_modal").openModal();
					  });
	
}

function delSnapshot(snapid,uid) {
		$.post( "ajax.php", { DEL_SNAP: snapid,UID: uid }).done(function( data ) {
					 	handleMSG(data,"Snapshot deleted sucessfully !");
					  });
}

function newSnapshot(uid) {
	var snapshot_name = $("#snap_new_name").val();
	$("#snap_new_name").val("");
	if(snapshot_name != "") {

		$.post( "ajax.php", { ADD_SNAP: snapshot_name, UID: uid }).done(function( data ) {
						 	if(data != "") {
						 		loadSnapshotMgr(uid);
						 		handleMSG(data,"Snapshot created sucessfully !");
						 	} else {
						 		handleMSG("Error while creating snapshot",null);
						 	}
						  });
	} else {
		handleMSG("Please enter a snapshot name",null);
	}
}

function loadVMList() {

		if($("#vmlist").html() == "") {
			$("#vmlist").html('<div class="progress"><div class="indeterminate"></div></div>');
		}
		$("#vmlist").load("ajax.php?getvmlist=1");
}

function onVM(uid) {
	$("pre").load("ajax.php?pON="+uid, function( response, status, xhr ){
		handleMSG(response,"VM switched on !");
		loadVMList();
	});
}

function offVM(uid) {
	$("pre").load("ajax.php?pOFF="+uid, function( response, status, xhr ){
		handleMSG(response,"VM switched off !");
		loadVMList();
	});
}

function delVM(uid) {
		$("pre").load("ajax.php?pDEL="+uid, function( response, status, xhr ){
		handleMSG(response,"VM deleted !");
		loadVMList();
	});
}

function VRDE(act,uid) {
		$("pre").load("ajax.php?UID="+uid+"&VRDE="+act, function( response, status, xhr ){
		handleMSG(response,"VRDE set to "+act+" ! ");

		loadVMList();
	});
}
