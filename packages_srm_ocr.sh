#!/bin/bash

#keep a list of packages required for the srm ocr backend server

#--------------------------------------------------------------
#check if we are running as root or sudo

        if [[ $UID -ne 0 ]];then
                echo "$0 must be run as root. Most of the packages requires root's privileges"
                exit 1
        fi
#---------------------------------------------------------------

        FLAG = ""
        if [ "$1" != "" ];then
                FLAG=$1
                echo "Using Flag " $FLAG
        fi

#update and upgrade

        apt-get update -y
        apt-get upgrade -y

#packages required 
        apt-get install $FLAG vim                               # text-editor for editing files
        apt-get install $FLAG apache2                           # apache server for django deployment
        apt-get install $FLAG screen                            # to use screen command
        apt-get install $FLAG iptables                          # administering tool for ipv4/6 packet filtering
        apt-get install $FLAG git                               # git for version control
	apt-get install $FLAG php5 
	apt-get install	$FLAG libapache2-mod-php5
	apt-get install $FLAG php5-mcrypt
	apt-get install $FLAG tesseract-ocr
	apt-get install $FLAG libicu-dev
	apt-get install $FLAG libpango1.0-dev
	apt-get install $FLAG libcairo2-dev
	apt-get install $FLAG autoconf automake libtool
	apt-get install $FLAG libpng12-dev
	apt-get install $FLAG libjpeg62-dev
	apt-get install $FLAG libtiff4-dev
	apt-get install $FLAG zlib1g-dev
	apt-get install $FLAG libicu-dev
	apt-get install $FLAG libpango1.0-dev
	apt-get install $FLAG libcairo2-dev
