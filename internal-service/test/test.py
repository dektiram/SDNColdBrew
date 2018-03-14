import paste.fileapp
import paste.httpserver
import routes
import webob
import webob.dec
import webob.exc
import json
import os
import subprocess
import datetime
from datetime import timedelta
import psutil
import uuid
import time
import sys
import threading
import thread

print sys.argv[1]
def newThread(scriptFile):
	p = subprocess.Popen('/bin/bash '+scriptFile+'', stdout=subprocess.PIPE, shell=True)
	(output, err) = p.communicate()
	p_status = p.wait()

t1 = threading.Thread(target=newThread, args=[sys.argv[1]])
t1.daemon = True
t1.start()
#t1.join()
	
'''
scriptDir = os.path.dirname(os.path.realpath(__file__))+'/tmp-script/'
		strTimeStamp = datetime.datetime.now().strftime("%Y%m%d%H%M%S")
		scriptFile = scriptDir + strTimeStamp+'.'+uuid.uuid4().hex+'.sh'
		file = open(scriptFile,'w')
		for cmd in cmds:
			file.write(cmd)
		file.close()
		print cmd
		cdm2 = '/bin/bash '+scriptFile
		#if not wait:
		#	cmd2 = cmd2 + ' &'
		#print cmd2
		p = subprocess.Popen(cmd2, stdout=subprocess.PIPE, shell=True)
		output = None
		err = None
		p_status = None
		if wait:
			(output, err) = p.communicate()
			p_status = p.wait()
		return {'output':output, 'err':err, 'status':p_status}
'''