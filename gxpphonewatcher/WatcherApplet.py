import gtk
import egg.trayicon 
import socket
import thread

class Gui:
    up = 0
    
    def __init__(self):
        self.up = 1
    
    def quitApp(self,  *args):
        gtk.main_quit ()
        self.up = 0

    def showAbout(*args):
        dialog = gtk.MessageDialog(parent=None,
        type=gtk.MESSAGE_INFO,
                            buttons=gtk.BUTTONS_CLOSE, flags=gtk.DIALOG_MODAL)
        dialog.set_markup("SIP Watcher")
        dialog.format_secondary_text("Version 0.1 - 2008-10-31\nby Michael Pilgermann\nhttp://www.kichkasch.de/project_gxp.html")
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
        self.tray.send_message(displayTime, text)
        tooltips = gtk.Tooltips()
        tooltips.set_tip(self.box, "(Last: " + text + ")")
