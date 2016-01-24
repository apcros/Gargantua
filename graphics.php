<?php 

function HTMLheader() {
	echo '  <!DOCTYPE html>
  <html>
    <head>
      <link type="text/css" rel="stylesheet" href="css/materialize.min.css"  media="screen,projection"/>
      <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
      <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    </head>
    <body>
      <script type="text/javascript" src="https://code.jquery.com/jquery-2.1.1.min.js"></script>
      <script type="text/javascript" src="js/materialize.min.js"></script>
      <script type="text/javascript" src="js/gargantua-lib.js"></script>';
}

function HTMLsnapshotsmodal() {
  echo '    <div id="snapshot_modal" class="modal bottom-sheet">
      <div class="modal-content">
        <h4>Snapshots for <b id="snap_name"></b></h4>
              <ul class="collection" id="snap_list">
              </ul>
      </div>
      <div class="modal-footer">
       <input id="snap_new_name" class="col offset-s4 s4" placeholder="Snapshot name" type="text"/><a id="snap_btn" href="#!" id="snap_new" class="col s2 modal-action modal-close waves-effect waves-green btn"><i class="material-icons right">add</i>New snapshot</a>
      </div>
    </div>';
}

function HTMLsnapshotsli($snapshots, $vmid) {
    foreach ($snapshots as $key => $snapshot) {

        echo '<li class="collection-item"><div><b>'.$snapshot["name"].'</b><br>'.$snapshot["id"].'<a href="#!" class="secondary-content"><i class="material-icons">replay</i></a><a href="#!" onclick="delSnapshot(\''.$snapshot["id"].'\',\''.$vmid.'\');" class="secondary-content"><i class="material-icons">delete</i></a></div></li>';
    }
}

function HTMLcreatevm($isos,$ostypes) {

  foreach ($isos as $key => $value) {
    $OSs[]["DISPLAY_NAME"] = $value;
    $OSs[count($OSs)-1]["ID"] = $value;
  }

  echo ' <div class="row">
    <form method="POST" action="new-vm.php" class="col s12">
      <div class="row">
        <h5>&nbsp;</h5>
        <div class="input-field col s6">
          <b>Name</b>
          <input id="vm_name" name="vm_name" type="text" class="validate">
          
        </div>
        <div class="input-field col s6">
          <b>OS</b>
              <select id="vm_iso" type="text" name="vm_iso">
                <option value="" disabled selected>Choose your ISO</option>';
                foreach ($OSs as $key => $os) {
                  echo '<option value="'.$os["ID"].'">'.$os["DISPLAY_NAME"].'</option>';
                }
       echo '     </select>

              <select id="vm_os" type="text" name="vm_os">
                <option value="" disabled selected>What type of OS is that ?</option>';
                foreach ($ostypes as $key => $ost) {
                  echo '<option value="'.$ost.'">'.$ost.'</option>';
                }
       echo '     </select>

        </div>
      </div>
      <div class="row">
        <div class="input-field col s6">
           <b>Ram (MB)</b>
          <input id="vm_ram" name="vm_ram" type="number" min="1" max="16384" class="validate">
         
        </div>
        <div class="input-field col s6">
          <b>Disk space (GB)</b>
          <input id="vm_disk" name="vm_disk" type="number" class="validate" min="1" max="110">
        </div>
      </div>
      <button class="waves-effect waves-light btn"><i class="material-icons left">add</i>Create the vm</button>
    </form>
  </div>';
}

function HTMLsuccess($msg = "") {
    echo '
  <div class="row">
      <div class="col s12">
        <div class="card-panel light-blue darken-4">
          <span class="white-text">
            '.$msg.'
          </span>
        </div>
      </div>
    </div>';
}

function HTMLfail($msg = "") {
    echo '
  <div class="row">
      <div class="col s12">
        <div class="card-panel orange darken-4">
          <span class="white-text">
            '.$msg.'
          </span>
        </div>
      </div>
    </div>';
}

function HTMLvmcard($vmname, $os, $ramsize, $state,$vrde,$uid ,$vminfos) {
  if(strstr($vrde, "off")) { $act_vrde = "on"; } else { $act_vrde = "off"; }
  echo ' <div class="col s12 m6">
        <div class="card-panel center-align">
          <div class="row">
            <h5><b>'.$vmname.'</b> - '.$os.'</h5>

             <p class="center-align col s6"><i class="material-icons left">memory</i><b>RAM : </b>'.$ramsize.' MB </p>
             
             <p class="left-align col s12"><b>Memory usage : ['.$vminfos["RAM_USED"].' / '.$vminfos["RAM_TOTAL"].' MB]</b><div class="progress"><div class="determinate" style="width: '.$vminfos["RAM_LOAD"].'"></div></div></p>
             <p class="left-align col s12"><b>CPU usage : ['.($vminfos["CPU"]+0).' %]</b><div class="progress"><div class="determinate" style="width: '.$vminfos["CPU"].'"></div></div></p>
             <p><b>VRDE is '.$vrde.'</b></p>';
             if(strstr($state, "off")) {
                echo '<b class="red-text">VM is '.$state.'</b>';
                $snapshot = '<a class="waves-effect waves-light btn teal accent-4" onclick="loadSnapshotMgr(\''.$uid.'\');"><i class="material-icons">restore</i></a>';
                $power = '<a class="waves-effect waves-light btn green" onclick="onVM(\''.$uid.'\')"><i class="material-icons">power_settings_new</i></a>';
             } else {
                echo '<b class="green-text">VM is '.$state.'</b>';
                $snapshot = '<a class="waves-effect waves-light btn teal accent-4 disabled"><i class="material-icons">restore</i></a>';
                $power = '<a class="waves-effect waves-light btn red" onclick="offVM(\''.$uid.'\')"><i class="material-icons">power_settings_new</i></a>';
             }
   echo '        
          </div>
          <div class="divider"></div>
          <p>
            '.$power.'
            '.$snapshot.'
            <a class="waves-effect waves-light btn teal accent-4"><i class="material-icons">settings</i></a>
            <a class="waves-effect waves-light btn red" onclick="delVM(\''.$uid.'\')"><i class="material-icons">delete</i></a>
            <a class="waves-effect waves-light btn blue darken-3" onclick="VRDE(\''.$act_vrde.'\',\''.$uid.'\')"><i class="material-icons">dvr</i></a>
          </p>
        </div>
      </div>';
}

function HTMLnavbar($active) {
  $liH = ($active == 0 ? "class='active'" : ""); 
  $liA = ($active == 1 ? "class='active'" : ""); 
  $liS = ($active == 2 ? "class='active'" : ""); 
  $liO = ($active == 3 ? "class='active'" : ""); 
	echo '        <nav>
          <div class="nav-wrapper teal darken-1">
            <a href="#" class="brand-logo">&nbsp;Gargantua ('.$_SERVER['SERVER_ADDR'].')</a>
                            <ul class="right hide-on-med-and-down">
                  <li '.$liH.'><a href="index.php"><i class="material-icons left">view_list</i>My VMs</a></li>
                  <li '.$liA.'><a href="new-vm.php"><i class="material-icons left">library_add</i>Add VM</a></li>
                  <li '.$liS.'><a href="settings.php"><i class="material-icons left">settings</i>Settings</a></li>
                  <li '.$liO.'><a href="os-library.php"><i class="material-icons left">cloud_circle</i>ISO Library</a></li>
                </ul>
          </div>

       </nav>';
}

function HTMLisodownload() {
}

function HTMLisos($all_isos) {
  echo "<h4> Available images : </h4>";
  echo "<table>
        <thead>
          <tr>
              <th data-field='id'>File name</th>
          </tr>
        </thead>";

  foreach ($all_isos as $key => $iso) {
    echo  "<tr>
            <td>".$iso."</td>
          </tr>";
  }

  echo "        <tbody>
        </tbody>
      </table>";
}
function HTMLfooter() {
	echo ' </body>
  </html>';
}

function HTMLcontainerOpen() {
	echo '<div class="container">
<div class="row">';
}

function HTMLcontainerClose() {
	echo '</div></div>';
}


?>