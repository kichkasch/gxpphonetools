# This Makefile is part of GXPContactManager
#
# global parameters
TITLE="GXP Contact Manager"
URL="http://github.com/kichkasch/gxpphonetools"
PACKAGE_NAME ="gxpcontactmanager"
VERSION="0.1"

# NO leading slash!!!
WEBSERVER_DATAFOLDER="var/www/"
WEBSERVER_SUBFOLDER="gxpcontactmanager"

clean:
	rm -f *~ sql/*~ sql/sql/*~ ldap/*~ build/*~

sdist: clean 
	tar cf build/tmp.tar COPYING README img ldap/*.php sql/*.php sql/*.css sql/ressources/*.png sql/sql/*.sql
	mkdir $(PACKAGE_NAME)-$(VERSION)
	(cd $(PACKAGE_NAME)-$(VERSION) && tar -xf ../build/tmp.tar)
	rm build/tmp.tar
	tar czf build/$(PACKAGE_NAME)-src-$(VERSION).tar.gz $(PACKAGE_NAME)-$(VERSION)
	rm -rf $(PACKAGE_NAME)-$(VERSION)


# instructions for building Desktop packages
# 1. Ubuntu deb
# (install with: sudo dpkg -i gxpcontactmanager-$VERSION.deb)
dist: clean
	mkdir -p build/ubuntu/DEBIAN
	cp build/control build/ubuntu/DEBIAN/control
	mkdir -p build/ubuntu/$(WEBSERVER_DATAFOLDER)/$(WEBSERVER_SUBFOLDER)
	cp -r COPYING README ldap sql build/ubuntu/$(WEBSERVER_DATAFOLDER)/$(WEBSERVER_SUBFOLDER)
	sudo chown -R www-data:www-data build/ubuntu/DEBIAN
	(cd build && dpkg --build ubuntu/ $(PACKAGE_NAME)-$(VERSION).deb)
	sudo rm -rf build/ubuntu