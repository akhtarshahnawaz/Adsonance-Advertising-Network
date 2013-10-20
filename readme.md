Requirements
--------------


Apache Directive{
	1- allowEncodedSlashes  On
	2- need to have forward slash at end of url (in virtual host) when redirecting from simple to secure site
}



Apache Education{

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

		MORE EDUCATION
		--------------
			Level 1- Httpd.conf
			Level 2- sites-available/default
			Level 3- sites-available/{site name}
			Level 4- .htaccess file in site's directory

			For each level to override it's parent's config
			AllowOverride should be set All as shown above in it's parent config for the directory we want to override

			mod_rewrite module needs to be enabled to use it.
			To enable mod_rewrite  type [sudo a2enmod rewrite]
	}]
}