#! /usr/bin/python
#
# GXP Phone Watcher
# Michael Pilgermann (michael.pilgermann@gmx.de)
# Version 0.1 (2008-09-02)
#
# Program to be called by Syslog-ng deamon in case of incoming syslog events from a GXP 2000 SIP phone.
# A few evaluation will be made and a text message will be created, which is sent to the client program
# for GXP Phone Watcher using a TCP/IP socket.

import socket
import sys
import MySQLdb
import os
import os.path

PIPENAME = '/tmp/glx.pipe'

def extractMsg(data):
    f = open("/tmp/glxlog.log","a")
    try:
        delimiter = data.index(' ')
        time = data[0:delimiter]
        msg = data[delimiter+1:]
        f.write("LogEntry: " + time + " - " + msg)
        f.close()
        return time, msg
    except IndexError:
        f.write("Error\n")
        f.close()
        return None, None

def evalMsg(msg):
    try:
        indexSipMsg = msg.index('Received SIP message:')
        msgCode = int(msg[indexSipMsg+len("Received SIP message:"):])
        if msgCode == 2:
            return "New Incoming Call", 2
        if msgCode == 5:
            return "Other party hang up", 5
        return None, None
    except ValueError:
        try:
            indexNumber = msg.index('INVITE From') +len('INVITE From')+2
            indexNumberEnd = msg.index(" ", indexNumber)-1
            number = msg[indexNumber:indexNumberEnd]
            if number:
                name = getNameForNumber(number)
                if name:
                    return "New Incoming Call - " + name + " (" + number + ")", 10
                return "New Incoming Call - " + number, 10
        except ValueError:
            try:
                msg.index("@testlistener@")
                return "@STATUS@",  0
            except ValueError:
                return None, None

def getNameForNumber(number):
    conn = MySQLdb.connect (host = "localhost",
                            user = "gxp_user",
                            passwd = "gxp2000",
                            db = "gxp")
    cursor = conn.cursor ()
    cursor.execute ("SELECT first_name, last_name FROM gxp_phonebook where phone_number='" + number + "'")
    fullName = None
    while (1):
        row = cursor.fetchone ()
        if row == None:
            break
        fullName = row[0] + " " + row[1]
    return fullName

def sendOneMessage(time, msg, displayTime = 5):
    HOST = 'localhost'    # The remote host
    PORT = 9999              # The same port as used by the server
    try:
        s = socket.socket(socket.AF_INET, socket.SOCK_STREAM)
        s.connect((HOST, PORT))
        s.send(str(displayTime) + " " + time + ": " + msg)
        s.close()
    except socket.error:
        pass        # fine - no client availalbe - who cares


if __name__ == "__main__":    
    try:
        pid = os.fork()
        if pid:
            os._exit(0) # kill original
    except OSError, msg:
        print "Could not start listener as deamon. Error: %s" %msg
        sys.exit(1)
        
    if not os.path.exists(PIPENAME):
        os.mkfifo(PIPENAME)
    dataFile = open(PIPENAME, "r")

    while 1:
        data = dataFile.readline()
        time, msg = extractMsg(data)
        if msg:
            msg, displayTime = evalMsg(msg)
        if msg:
            sendOneMessage(time, msg, displayTime)
