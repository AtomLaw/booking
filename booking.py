#!/usr/bin/python

from xml.etree import ElementTree
from datetime import datetime

CONFIG = 'conf.xml'

class Appointment:
	def __init__(self):
		data = open(CONFIG, 'r')
		xmldata = data.read()
		data.close()
		tree = ElementTree.fromstring(xmldata)
		db = tree.find('db')
		dbhost = db.attrib['host']
		dbuser = db.attrib['user']
		dbpassword = db.attrib['password']
		dbtable = db.attrib['table']

		appts = tree.find('appts')
		number = appts.attrib['number']
		for child in appts.getchildren():
			if child.tag == 'date':
				try:
					if datestart > datetime.strptime(child.attrib['start'], '%Y%m%d'):
						datestart = datetime.strptime(child.attrib['start'], '%Y%m%d')
				except UnboundLocalError:
					datestart = datetime.strptime(child.attrib['start'], '%Y%m%d')
				try:
                                        if dateend > datetime.strptime(child.attrib['end'], '%Y%m%d'):
                                                dateend = datetime.strptime(child.attrib['end'], '%Y%m%d')
                                except UnboundLocalError:
                                        dateend = datetime.strptime(child.attrib['end'], '%Y%m%d')
				for subchild in child.getchildren():
					if subchild.tag == 'block':
						try:
							if timestart > datetime.strptime(subchild.attrib['start'], '%H%M%S'):
		                                                timestart = datetime.strptime(subchild.attrib['start'], '%H%M%S')
		                                except UnboundLocalError:
		                                        timestart = datetime.strptime(subchild.attrib['start'], '%H%M%S')
		                                try:
		                                        if timeend > datetime.strptime(subchild.attrib['end'], '%H%M%S'):
		                                                timeend = datetime.strptime(subchild.attrib['end'], '%H%M%S')
		                                except UnboundLocalError:
		                                        timeend = datetime.strptime(subchild.attrib['end'], '%H%M%S')
		days = dateend - datestart
		dayssecs = days.days * 86400
		print dayssecs
appointment = Appointment()
