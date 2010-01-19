# This Makefile is part of GXP Phone Watcher
#
# global parameters
TITLE=GXP Phone Watcher
URL=http://github.com/kichkasch/gxpphonetools
VERSION=0.1
PROGRAMNAME =gxpphonewatcher

clean:
	rm -f *.pyc 
	rm -rf build/template

sdist: clean
	tar cf build/tmp.tar *.py thirdparty/*.py COPYING README *howto* gxpphonewatcherrc build/control
	mkdir $(PROGRAMNAME)-$(VERSION)
	(cd $(PROGRAMNAME)-$(VERSION) && tar -xf ../build/tmp.tar)
	rm build/tmp.tar
	tar czf build/$(PROGRAMNAME)-src-$(VERSION).tar.gz $(PROGRAMNAME)-$(VERSION)
	rm -rf $(PROGRAMNAME)-$(VERSION)	
