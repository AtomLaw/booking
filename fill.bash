#!/usr/bin/bash

#Config values
datestart=20130207
dateend=20130207
#Times must be 24h starting with 0s
timestart=1600
timeend=1730
#Duration must be mins
duration=18
threshold=50
table="booking_cita"
#Start at id #
ap=50

#Convert time to mins
function htom {
	h=`echo $1 | cut -c1,2 | sed 's/^00//' | sed 's/^0//'`
	m=`echo $1 | cut -c3,4 | sed 's/^00//' | sed 's/^0//'`
	if [[ $m == '' ]]; then
		m=0
	fi
	let h=$h*60
	let h=$h+$m
	echo $h
}

function mtoh {
	m=$1
	let h=$m/60
	let mm=$m%60
	if [[ $mm -lt 10 ]]; then
		mm=0$mm
	fi
	if [[ $h -lt 10 ]]; then
		h=0$h
	fi
	h=$h$mm
	echo $h
}

timestartm=`htom $timestart`
timeendm=`htom $timeend`
let timeendm=$timeendm-$duration

apdate=$datestart
aptime=$timestart
aptimem=$timestartm

while [[ $apdate -le $dateend && $ap -le $threshold ]]; do
	echo "INSERT INTO $table VALUES($ap,STR_TO_DATE('$apdate$aptime','%Y%m%d%H%i'),NULL,0,NULL);"
	let ap=$ap+1
	let aptimem=$aptimem+$duration
	if [[ $aptimem -gt $timeendm ]]; then
		let apdate=$apdate+1
		aptimem=$timestartm
	fi
	aptime=`mtoh $aptimem`
done
