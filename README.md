# Gargantua
#### Current Version : [Alpha 0.1]
This is a layer to create/manage and monitor VirtualBox VMs. 
It's using PHP5, MaterializeCSS and jQuery

It should work straight out of the box. Just be sure you have VirtualBox installed along with the extension pack (https://www.virtualbox.org/wiki/Downloads) 

It's only compatible with the linux version of VirtualBox. 

This is still highly a WIP and there's lots of bugs, security issues and lack of functionnality. 

### Known issues :
If the VRDE is not working as expected, you'll probably want to run this : 
`su www-data -s /bin/bash && VBoxManage setproperty vrdeextpack "Oracle VM VirtualBox Extension Pack"`

### Interface 
![Interface](http://img.apcros.fr/30145366530728.png)
