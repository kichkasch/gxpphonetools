#! /usr/bin/python
#
# GXP Phone Watcher
# 
# Michael Pilgermann (michael.pilgermann@gmx.de)
# Version 0.1 (2008-09-02)
#
# GXP Phone Watcher is made up by two components - a program for syslog-ng calls and a GUI client application.
#
# This is the GUI part of the program. A network server (TCP/IP) is listening for incoming messages from the
# other part and showing them as a notification message in the system tray area.
#

import gtk
import egg.trayicon 
import socket
import thread
from syslog import *

global TEST_SEQUENCE
TEST_SEQUENCE = "@testlistener@"

class Gui:
    def quitApp(*args):
        gtk.main_quit ()

    def showAbout(*args):
        dialog = gtk.MessageDialog(parent=None,
        type=gtk.MESSAGE_INFO,
                            buttons=gtk.BUTTONS_CLOSE, flags=gtk.DIALOG_MODAL)
        dialog.set_markup("GXP Phone Watcher")
        dialog.format_secondary_text("Version 0.1 - 2008-09-07\nby Michael Pilgermann\nhttp://www.kichkasch.de/project_gxp.html")
        ret = dialog.run()
        dialog.destroy()

    def checkListenerStatus(*args):
        openlog ("GS_LOG", LOG_CONS, LOG_LOCAL3)
        syslog (LOG_NOTICE, TEST_SEQUENCE);
        
    def callbackListenerStatus(self):
        dialog = gtk.MessageDialog(parent=None,
        type=gtk.MESSAGE_INFO,
                            buttons=gtk.BUTTONS_CLOSE, flags=gtk.DIALOG_MODAL)
        dialog.set_markup("GXP Phone Watcher")
        dialog.format_secondary_text("Status of Listener: UP")
        ret = dialog.run()
        dialog.destroy()

    def callback(self,  widget, event):
        if event.button == 3 :
            self.menu.popup( None, None, None, 0, event.time );
        return;

    def initGui(self):
        tray = egg.trayicon.TrayIcon("TrayIcon")
        box = gtk.EventBox()
        image=gtk.Image() 
        image.set_from_file ("/usr/share/gxp/phone3.png") 
        box.add(image) 
        tray.add(box)
        tray.show_all()
        box.connect("button-press-event", self.callback)

        self.menu = gtk.Menu()
        menu_item = gtk.MenuItem( "Check Listener Status" );
        menu_item.connect( 'activate', self.checkListenerStatus );
        self.menu.add( menu_item );
        self.menu.add(gtk.SeparatorMenuItem())
        menu_item = gtk.MenuItem( "About" );
        menu_item.connect( 'activate', self.showAbout );
        self.menu.add( menu_item );
        self.menu.add(gtk.SeparatorMenuItem())
        menu_item = gtk.MenuItem( "Quit" );
        menu_item.connect( 'activate', self.quitApp );
        self.menu.add( menu_item );
        self.menu.show_all()

        self.tray = tray
        self.box = box
        gtk.main()
        
    def showEvent(self, text, displayTime):
        try:
            text.index("@STATUS@")
            self.callbackListenerStatus()
        except ValueError:
            self.tray.send_message(displayTime, text)

            tooltips = gtk.Tooltips()
            tooltips.set_tip(self.box, "(Last: " + text + ")")


def glsListen(gui):
    s = socket.socket(socket.AF_INET, socket.SOCK_STREAM)
    s.bind(('', 9999))
    s.listen(5)
    while 1:
        client,addr = s.accept()
        data = client.recv(1024)
        posSpace = data.index(" ")
        displayTime = int(data[:posSpace])
        data = data[posSpace:]
        gtk.gdk.threads_enter()
        try:
            gui.showEvent(data, displayTime)
        finally:
            gtk.gdk.threads_leave()
        client.close()

gtk.gdk.threads_init()
gui = Gui()
thread.start_new_thread(glsListen, (gui,))
gtk.gdk.threads_enter()
gui.initGui()
gtk.gdk.threads_leave()

