#/bin;lk;k

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
import signal
import uuid
import time

HOST = '127.0.0.1'
PORT = 8989

class SdnColdBrewInternalService(object):
	map = routes.Mapper()	
	map.connect('startAll', '/startAll/{param}', method='uStartAll')
	map.connect('stopAll', '/stopAll', method='uStopAll')
	map.connect('infoAll', '/infoAll/{param}', method='uInfoAll')
	map.connect('mininetCmd', '/mininetCmd/{param}', method='uMininetCmd')
	map.connect('mininetCmdProcessList', '/mininetCmdProcessList', method='uMininetCmdProcessList')
	map.connect('mininetKillCmdProcess', '/mininetKillCmdProcess/{param}', method='uMininetKillCmdProcess')
	
	
	def shelExecAndGetOutput(self,cmds, wait=True):
		#print cmds
		scriptDir = os.path.dirname(os.path.realpath(__file__))+'/tmp-script/'
		strTimeStamp = datetime.datetime.now().strftime("%Y%m%d%H%M%S")
		scriptFile = scriptDir + strTimeStamp+'.'+uuid.uuid4().hex+'.sh'
		file = open(scriptFile,'w')
		for cmd in cmds:
			file.write(cmd)
		file.close()
		p = subprocess.Popen('/bin/bash '+scriptFile, stdout=subprocess.PIPE, shell=True)
		output = None
		err = None
		p_status = None
		if wait:
			(output, err) = p.communicate()
			#p_status = p.wait()
		#return {'output':output, 'err':err, 'status':p_status}
		return {'output':output, 'err':err}
	
	def kill_proc_tree(pid, sig=signal.SIGTERM, include_parent=True, timeout=None, on_terminate=None):
		"""Kill a process tree (including grandchildren) with signal
		"sig" and return a (gone, still_alive) tuple.
		"on_terminate", if specified, is a callabck function which is
		called as soon as a child terminates.
		"""
		if pid == os.getpid():
			raise RuntimeError("I refuse to kill myself")
		parent = psutil.Process(pid)
		children = parent.children(recursive=True)
		if include_parent:
			children.append(parent)
		for p in children:
			p.send_signal(sig)
		gone, alive = psutil.wait_procs(children, timeout=timeout,
										callback=on_terminate)
		return (gone, alive)
	
	def getRyuProcessInfo(self):
		returnData = {}
		for x1 in psutil.process_iter(attrs=['name']):
			if(x1.info['name'] == 'ryu-manager'):
				returnData['pid'] = x1.pid
				returnData['command'] = x1.cmdline()
				returnData['create_time'] = datetime.datetime.fromtimestamp(x1.create_time()).strftime("%Y-%m-%d %H:%M:%S")
		return returnData
	
	def isRyuRunning(self):
		x1 = self.getRyuProcessInfo()
		if 'pid' in x1:
			return True
		else:
			return False
	
	def startRyu(self, scripts, captureFile):
		returnData = {}
		if not self.isRyuRunning():
			strTimeStamp = datetime.datetime.now().strftime("%Y-%m-%d %H:%M:%S")
			cmd = 'echo "Time execution : '+strTimeStamp+'" &>> '+captureFile
			self.shelExecAndGetOutput([cmd])
			cmd = 'ryu-manager --observe-links'
			for script in scripts:
				cmd = cmd + ' ' + script
			cmd = cmd + ' &>> ' + captureFile + ' &'
			self.shelExecAndGetOutput([cmd], False)
			returnData = {'status':'SUCCESS', 'message':'Start command was executed.'}
		else:
			returnData = {'status':'FAIL', 'message':'ryu-manager is already running.'}
		return returnData
	
	def stopRyu(self):
		returnData = {}
		x1 = self.getRyuProcessInfo()
		if 'pid' in x1:
			cmd = 'kill -9 '+str(x1['pid'])
			self.shelExecAndGetOutput([cmd])
			if(not self.isRyuRunning()):
				returnData = {'status':'SUCCESS', 'message':'Ryu controller was stopped.'}
			else:
				returnData = {'status':'FAIL', 'message':'Ryu controller is still running.'}
		else:
			returnData = {'status':'SUCCESS', 'message':'No Ryu controller running.'}
		return returnData
	
	def getSflowrtProcessInfo(self):
		returnData = {}
		for x1 in psutil.process_iter(attrs=['name','cmdline']):
			#print x1
			#print x1.info
			if((x1.info['name'] == 'java')and('./lib/sflowrt.jar' in x1.info['cmdline'])):
				returnData['pid'] = x1.pid
				returnData['command'] = x1.cmdline()
				returnData['create_time'] = datetime.datetime.fromtimestamp(x1.create_time()).strftime("%Y-%m-%d %H:%M:%S")
		return returnData
	
	def isSflowrtRunning(self):
		x1 = self.getSflowrtProcessInfo()
		if 'pid' in x1:
			return True
		else:
			return False
	
	def startSflowrt(self, sflowrtPath, captureFile):
		returnData = {}
		if not self.isRyuRunning():
			strTimeStamp = datetime.datetime.now().strftime("%Y-%m-%d %H:%M:%S")
			cmd = 'echo "Time execution : '+strTimeStamp+'" &>> '+captureFile
			self.shelExecAndGetOutput([cmd])
			cmd = '/bin/bash '+sflowrtPath+'start.sh &>> ' + captureFile + ' &'
			self.shelExecAndGetOutput([cmd], False)
			returnData = {'status':'SUCCESS', 'message':'Start command was executed.'}
		else:
			returnData = {'status':'FAIL', 'message':'sflowrt is already running.'}
		return returnData
	
	def stopSflowrt(self):
		returnData = {}
		x1 = self.getSflowrtProcessInfo()
		if 'pid' in x1:
			cmd = 'kill -9 '+str(x1['pid'])
			self.shelExecAndGetOutput([cmd])
			if(not self.isSflowrtRunning()):
				returnData = {'status':'SUCCESS', 'message':'sflowrt was stopped.'}
			else:
				returnData = {'status':'FAIL', 'message':'sflowrt is still running.'}
		else:
			returnData = {'status':'SUCCESS', 'message':'No sflowrt running.'}
		return returnData
	
	def startAll(self, ryuScripts, ryuCaptureFile, sflowrtPath, sflowrtCaptureFile):
		self.stopAll()
		returnData = {}
		returnData['sflowrt'] = self.startSflowrt(sflowrtPath, sflowrtCaptureFile)
		time.sleep(4)
		returnData['ryu'] = self.startRyu(ryuScripts, ryuCaptureFile)
		return returnData
	def stopAll(self):
		returnData = {}
		returnData['sflowrt'] = self.stopSflowrt()
		time.sleep(4)
		returnData['ryu'] = self.stopRyu()
		return returnData
	def infoAll(self, ryuCaptureFile, sflowrtCaptureFile):
		returnData = {}
		returnData['ryu'] = self.getRyuProcessInfo()
		returnData['sflowrt'] = self.getSflowrtProcessInfo()
		returnData['ryu']['lastLog'] = self.shelExecAndGetOutput(['tail -100 '+ryuCaptureFile])['output']
		returnData['sflowrt']['lastLog'] = self.shelExecAndGetOutput(['tail -100 '+sflowrtCaptureFile])['output']
		return returnData
	
	def mininetCmd(self, mininetUtilPath, mininetCmd):
		returnData = {}
		cmd = mininetUtilPath+'m '+mininetCmd
		return self.shelExecAndGetOutput(cmd)
	
	def mininetCmdProcessList(self):
		returnData = []
		for x1 in psutil.process_iter(attrs=['name','cmdline']):
			if(x1.info['name'] == 'sudo'):
				if((x1.info['cmdline'][0] == 'sudo')and(x1.info['cmdline'][1] == 'mnexec')):
					returnData.append({
									'pid':x1.pid,
									'command':x1.cmdline(),
									'create_time':datetime.datetime.fromtimestamp(x1.create_time()).strftime("%Y-%m-%d %H:%M:%S")
									})
		return returnData
	
	def mininetKillCmdProcess(self, pids):
		returnData = []
		for pid in pids :
			isPidAda = False
			for x1 in psutil.process_iter(attrs=['name','cmdline']):
				if(x1.info['name'] == 'sudo'):
					if((x1.info['cmdline'][0] == 'sudo')and(x1.info['cmdline'][1] == 'mnexec')and(x1.pid == pid)):
						print pid
						#cmd = 'kill '+str(x1.pid)
						cmd = 'kill -- -$(ps -o pgid= '+str(x1.pid)+' | grep -o [0-9]*)'
						print cmd
						x2 = self.shelExecAndGetOutput([cmd])
						returnData.append({'pid':pid, 'status':'SUCCESS'})
						isPidAda = True
			if not isPidAda:
				returnData.append({'pid':pid, 'status':'FAIL', 'message':'No mininet process with pid '+str(pid)+'.'})
		return returnData
	
	@webob.dec.wsgify
	def __call__(self, req):
		results = self.map.routematch(environ=req.environ)
		if not results:
			oReply = {'responseCode':404}
			return json.dumps(oReply)
		try:
			match, route = results
			link = routes.URLGenerator(self.map, req.environ)
			req.urlvars = ((), match)
			kwargs = match.copy()
			method = kwargs.pop('method')
			req.link = link
			return getattr(self, method)(req, **kwargs)
		except Exception as e:
			oReply = {'responseCode':500, 'message':str(e)}
			return json.dumps(oReply)
	
	#def uStartAll(self, req, ryuScripts, ryuCaptureFile, mininetScript, mininetCaptureFile, sflowrtPath, sflowrtCaptureFile):
	def uStartAll(self, req, param):
		xparam = json.loads(param.decode('hex'))
		x1 = xparam['ryuScript']
		x2 = xparam['ryuShellCaptureFile']
		x3 = xparam['sflowrtPath']
		x4 = xparam['sflowrtShellCaptureFile']
		oReply = {'responseCode':200, 'data':self.startAll(x1, x2, x3, x4)}
		return json.dumps(oReply)
	
	def uStopAll(self, req):
		oReply = {'responseCode':200, 'data':self.stopAll()}
		return json.dumps(oReply)
	
	#def uInfoAll(self, req, ryuCaptureFile, mininetCaptureFile, sflowrtCaptureFile):
	def uInfoAll(self, req, param):
		xparam = json.loads(param.decode('hex'))
		x1 = xparam['ryuShellCaptureFile']
		x2 = xparam['sflowrtShellCaptureFile']
		oReply = {'responseCode':200, 'data':self.infoAll(x1, x2)}
		return json.dumps(oReply)
	
	def uMininetCmd(self, req, param):
		xparam = json.loads(param.decode('hex'))
		x1 = xparam['mininetUtilPath']
		x2 = xparam['mininetCmd']
		oReply = {'responseCode':200, 'data':self.mininetCmd(x1, x2)}
		return json.dumps(oReply)
	
	def uMininetCmdProcessList(self, req):
		oReply = {'responseCode':200, 'data':self.mininetCmdProcessList()}
		return json.dumps(oReply)
	
	def uMininetKillCmdProcess(self, req, param):
		xparam = json.loads(param.decode('hex'))
		x1 = xparam['pids']
		oReply = {'responseCode':200, 'data':self.mininetKillCmdProcess(x1)}
		return json.dumps(oReply)
	
def main():
	paste.httpserver.serve(SdnColdBrewInternalService(), host=HOST, port=PORT)

if __name__ == '__main__':
	main()