import requests, re, json, sys
from HTMLParser import HTMLParser

courses = {}

def fixnames(data, cname):
	cstr = ''.join([i for i in cname.split(': ')[0][:-1] if not i.isdigit()])
	data = data.replace('course', cstr)
	data = data.replace('Biomolecular Engineering', 'BME')
	data = data.replace('Computational Media', 'CMPM')
	data = data.replace('Computer Engineering', 'CMPE')
	data = data.replace('Computer Science', 'CMPS')
	data = data.replace('Electrical Engineering', 'EE')
	data = data.replace('Games And Playable Media', 'GAME')
	data = data.replace('Games and Playable Media', 'GAME')
	data = data.replace('Games Playable Media', 'GAME')
	data = data.replace('Information Systems Management', 'ISM')
	data = data.replace('Technology and Information Management', 'TIM')
	data = data.replace('Technology And Information Management', 'TIM')
	data = data.replace('Technology Information Management', 'TIM')
	return data

if (len(sys.argv) < 2):
	year = input('Year (YYYY-YY): ')
else:
	year = sys.argv[1]

class MyIndexHTMLParser(HTMLParser):
	def __init__(self):
		self.level = 0
		self.divs = 0
		self.lastcname = ''
		HTMLParser.__init__(self)

	def handle_starttag(self, tag, attrs):
		attr = dict(attrs)
		if (tag == 'div' and 'id' in attr and attr['id'] == 'content' and self.level == 0):
			self.level += 1
		elif (tag == 'div' and 'class' in attr and attr['class'] == 'content' and self.level == 1):
			self.level += 1
		elif (tag == 'div' and self.level == 1):
			self.divs += 1
		elif (tag == 'li' and self.level == 2):
			self.level += 1
		elif (tag == 'a' and self.level == 3 and 'href' in attr):
			courses[attr['href'].split('/')[2]] = {'url' : 'https://courses.soe.ucsc.edu' + attr['href'], 'Fall' : 0, 'Winter' : 0, 'Spring': 0, 'Summer': 0, 'Prerequisites': [], 'Concurrents': []}
			self.level += 1

	def handle_endtag(self, tag):
		if (tag == 'div' and self.level == 1 and self.divs == 0):
			self.level -= 1
		elif (tag == 'div' and self.level == 1):
			self.divs -= 1
		elif (tag == 'div' and self.level == 2):
			self.level -= 1
		elif (tag == 'li' and self.level == 3):
			self.level -= 1
		elif (tag == 'a' and self.level == 4):
			self.level -= 1

	def handle_data(self, data):
		if (self.level == 4):
			cname = data.split(': ')
			if (cname[0].lower() in courses):
				self.lastcname = cname[0].lower()
				courses[self.lastcname]['Name'] = data
			else:
				courses[self.lastcname]['Name'] += "&" + str(data)

class MyCourseHTMLParser(HTMLParser):
	def __init__(self, course):
		self.level = 0
		self.divs = 0
		self.p = False
		self.inyear = False
		self.course = course
		HTMLParser.__init__(self)

	def handle_starttag(self, tag, attrs):
		attr = dict(attrs)
		if (tag == 'div' and 'class' in attr and attr['class'] == 'content' and self.level == 0):
			self.level += 1
		elif (tag == 'div' and self.level == 0):
			self.divs += 1
		elif (tag == 'p' and self.level == 1):
			self.p = True
		elif (tag == 'td' and self.level == 1):
			self.level += 1
		elif (self.inyear and 'href' in attr):
			if ('Fall' in attr['href']):
				self.course['Fall'] = 1
			if ('Summer' in attr['href']):
				self.course['Summer'] = 1
			if ('Winter' in attr['href']):
				self.course['Winter'] = 1
			if ('Spring' in attr['href']):
				self.course['Spring'] = 1

	def handle_endtag(self, tag):
		if (tag == 'div' and self.level == 1 and self.divs == 0):
			self.level -= 1
		elif (tag == 'div' and self.level == 1):
			self.divs -= 1
		elif (tag == 'p' and self.level == 1 and self.p):
			self.p = False
		elif (tag == 'td' and self.level == 2):
			self.level -= 1
		elif (tag == 'tr' and self.inyear == True):
			self.inyear = False

	def handle_data(self, data):
		if (self.level == 2):
			if (year in str(data)):
				self.inyear = True
		if (self.level == 1 and self.p):
			if (data.endswith('Credits')):
				self.course['Units'] = int(filter(str.isdigit, str(data)))
			if ('Prerequisite(s): ' in data):
				prereqstrdirty = data.split('Prerequisite(s): ')[1].replace(' or ', ' | ')
				for andsection in re.findall('([^,|]+, )+and', prereqstrdirty):
					prereqstrdirty = prereqstrdirty.replace(andsection, andsection.replace(',', ' and'))
				prereqstrdirty = prereqstrdirty.replace(', ', ' | ')
				for andsection in re.findall('[|][^,]+and', prereqstrdirty):
					prereqstrdirty = prereqstrdirty.replace(andsection, andsection.replace('and', '|'))
				prereqstr = fixnames(re.sub('(^| and )([0-9]+[A-Z]?)', '\\1course \\2', prereqstrdirty), self.course['Name']).split('.')[0]
				prereqstrs = re.split(' and ', prereqstr)
				prereqs = []
				for prs in prereqstrs:
					if not prs:
						continue
					if (' | ' in prs):
						prs = re.split('[|]', prs)[0]
					prs = prs.replace(' ', '').upper()
					match = re.search('(AMS|BME|CMPM|CMPE|CMPS|EE|GAME|ISM|TIM)[0-9]+[A-Z]?', prs)
					if (match):
						prereqs.append(match.group(0))
				self.course['Prerequisites'] = prereqs
			if ('Concurrent enrollment in ' in data):
				concurstrdirty = data.split('Concurrent enrollment in ')[1].replace(' or ', ' | ')
				for andsection in re.findall('([^,|]+, )+and', concurstrdirty):
					concurstrdirty = concurstrdirty.replace(andsection, andsection.replace(',', ' and'))
				concurstrdirty = concurstrdirty.replace(', ', ' | ')
				for andsection in re.findall('[|][^,]+and', concurstrdirty):
					concurstrdirty = concurstrdirty.replace(andsection, andsection.replace('and', '|'))
				concurstr = fixnames(re.sub('(^| and )([0-9]+[A-Z]?)', '\\1course \\2', concurstrdirty), self.course['Name']).split(' required.')[0]
				concurstrs = re.split('(, )|( and )', concurstr)
				concurs = []
				for cs in concurstrs:
					if not cs:
						continue
					if (' | ' in cs):
						cs = re.split('[|]', cs)[0]
					cs = cs.replace(' ', '').upper()
					match = re.search('(AMS|BME|CMPM|CMPE|CMPS|EE|GAME|ISM|TIM)[0-9]+[A-Z]?', cs)
					if (match):
						concurs.append(match.group(0))
				self.course['Concurrents'] = concurs
				
				

r = requests.get('https://courses.soe.ucsc.edu')
parser = MyIndexHTMLParser()
parser.feed(r.text)

coursesbyid = {}

for course in sorted(list(courses.values()), key=lambda c: int(re.search('\d+', c['Name']).group(0))):
	r = requests.get(course['url'])
	parser = MyCourseHTMLParser(course)
	parser.feed(r.text)
	del course['url']
	course['Name'] = course['Name'][:50]
	if (course['Fall'] or course['Winter'] or course['Spring'] or course['Summer']):
		print json.dumps(course)
		r = requests.post('http://cle-app.herokuapp.com/course/upload', data=course)
		print r.text
		try:
			coursesbyid[int(r.text)] = course
		except ValueError:
			pass

for cid, course in coursesbyid.items():
	print 'http://cle-app.herokuapp.com/course/linkuploads/' + str(cid) + '   ' + json.dumps(course)
	course['Prerequisites'] = json.dumps(course['Prerequisites'])
	course['Concurrents'] = json.dumps(course['Concurrents'])
	r = requests.post('http://cle-app.herokuapp.com/course/linkuploads/' + str(cid), data=course)
