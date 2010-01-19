#! /usr/bin/python
#
# SIP Watcher
# 
# Michael Pilgermann (michael.pilgermann@gmx.de)
# Version 0.1 (2008-10-31)
#
#

#import sys
import pjsua as pj
import WatcherApplet
import threading
import gtk
import thread

# Globals
#current_call = None
acc = None
acc_cb = None
#gui = None


# Callback to receive events from account
class MyAccountCallback(pj.AccountCallback):
    global acc
    gui = None
    sem = None

    def __init__(self, account,  gui):
        pj.AccountCallback.__init__(self, account)
        self.gui = gui
#        print self.__dict__
        
    def wait(self):
        self.sem = threading.Semaphore(0)
        self.sem.acquire()
        
    def on_incoming_call(self, call):
        print "Incoming call from ", call.info().remote_uri
        self.gui.showEvent("Test", 10)
        if self.sem:
            self.sem.release()

    def on_reg_state(self):
        print "Status of account changed to ",  acc.info().reg_reason
        
def startupSipBackend(gui):
    global acc
    global acc_cb
    try:    
        lib = pj.Lib()
        lib.init(log_cfg = None)
        transport = lib.create_transport(pj.TransportType.UDP, pj.TransportConfig(5060))
        lib.start()
        
        acc_cfg = pj.AccountConfig("030.sip.arcor.de", "03053140698", "67631411")
        
        acc = lib.create_account(acc_cfg)
        acc_cb = MyAccountCallback(acc,  gui)
        acc.set_callback(acc_cb)
        acc.set_basic_status(1)
        
        while gui.up:
            acc_cb.wait()
        
        acc.delete()
        lib.destroy()
    except pj.Error, err:
        print 'Error creating account:', err

def shutdownSipBackend():
    acc_cb.sem.release()

gtk.gdk.threads_init()
gui = WatcherApplet.Gui()
thread.start_new_thread(startupSipBackend, (gui,))
gtk.gdk.threads_enter()
gui.initGui()
gtk.gdk.threads_leave()
shutdownSipBackend()
