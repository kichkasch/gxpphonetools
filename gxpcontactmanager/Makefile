# This Makefile is part of GXPContactManager
#
# global parameters
TITLE="GXP Contact Manager"
URL="http://github.com/kichkasch/gxpphonetools"
PACKAGE_NAME ="gxpcontactmanager"
VERSION="0.1"


# for UBUNTU Launchpad upload of deb package
PGP_KEYID ="1B09FB51"
BUILD_VERSION = "0ubuntu1"


clean:
	rm -f *~ sql/*~ sql/sql/*~ ldap/*~ build/*~ build/debian/*~
	rm -f build/$(PACKAGE_NAME)-$(VERSION).orig.tar.gz	
	rm -rf build/$(PACKAGE_NAME)-$(VERSION)
	
sdist: clean 
	tar cf build/tmp.tar COPYING README ldap/*.php sql/*.php sql/*.css sql/ressources/*.png sql/sql/*.sql
	mkdir $(PACKAGE_NAME)-$(VERSION)
	(cd $(PACKAGE_NAME)-$(VERSION) && tar -xf ../build/tmp.tar)
	rm build/tmp.tar
	tar czf build/$(PACKAGE_NAME)-src-$(VERSION).tar.gz $(PACKAGE_NAME)-$(VERSION)
	rm -rf $(PACKAGE_NAME)-$(VERSION)


# instructions for building Desktop packages
# 1. Ubuntu deb
#
# All up-to-date information must be applied to sub dir build/debian in advance
sdist_ubuntu: sdist
	export DEBFULLNAME="Michael Pilgermann"
	export DEBEMAIL="kichkasch@gmx.de"
	cp build/$(PACKAGE_NAME)-src-$(VERSION).tar.gz build/$(PACKAGE_NAME)-$(VERSION).orig.tar.gz
	(cd build && tar -xzf $(PACKAGE_NAME)-$(VERSION).orig.tar.gz)
	cp -r build/debian build/$(PACKAGE_NAME)-$(VERSION)/
	cp README build/$(PACKAGE_NAME)-$(VERSION)/debian/README.Debian
	dch -m -c build/$(PACKAGE_NAME)-$(VERSION)/debian/changelog
	cp build/$(PACKAGE_NAME)-$(VERSION)/debian/changelog build/debian
	(cd build/$(PACKAGE_NAME)-$(VERSION)/ && dpkg-buildpackage -S -k$(PGP_KEYID))

ppa_upload: sdist_ubuntu
	(cd build/ && dput --config dput.config kichkasch-ppa $(PACKAGE_NAME)_$(VERSION)-$(BUILD_VERSION)_source.changes)