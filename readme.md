Requirements
--------------


Apache Directive{
	1- allowEncodedSlashes  On
	2- need to have forward slash at end of url (in virtual host) when redirecting from simple to secure site
}


Php{
	
}



Javascript{
	
}

Dependencies{
	Facebook SDK Needs curl PHP extension
}


Authentication{
	[{  apache is running as www-data
		so we have to own directory by www-data as like this
		chown <-R for recursion> <username>:<usergroup> <folder>
		chown www-data:root /var/www/
	}]

	[{Allow Mod_rewrite by command
		"sudo a2enmod rewrite"
		and then change "AllowOverride none" to "AllowOverride all" in directory section of virtual hosts file
	}]
}