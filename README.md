# xrdportal
This system just queues samples and manages files as well as notices. It has a public login page and a private dashboard. There are admin users and students. Students can only see their samples. Admins can add, edit and delete samples, users, notices. After admin uploads a .cif file in the sample for a student, the student can view the graphical model of the file by clicking on the file name on their dashboard. It will open a popup window that loads the JMol Java Applet. The applet might need to run in the sandbox. For Ubuntu Firefox users you will need the IcedTea plugin. Other browsers will need a Java plugin or need to have Java enabled. 

## Setup

**application/config/config.php**

`$config["base_url"]` must be set to your base url

`$config["encryption_key"]` should be changed

**application/config/database.php**

`$db["default"]["database"]` must be set

`$db["default"]["username"]` must be set

`$db["default"]["password"]` must be set

Change **images/logo.png**

Change **application/views/navigation/dashboard** ln 4 to your chosen name

A database dump can be found with this README called **xrdportal.sql**

Login with **admin@xrdportal.com** and password **123456**. Change this user to you and change the password.
