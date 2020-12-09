#!/usr/bin/python3

#
# NAME
#   measure.py - script to store sense hat measurements in SQL database
#
# SYNOPSIS
#   measure.py [-v] [-t interval]
#       -v: verbose
#       -t interval: sample every interval seconds
#
# DESCRIPTION
#   measures temperature data from the raspbery pi sense hat and
#   store data in a local SQL database
#

# import some modules
import sys
import getopt
import sense_hat
import time
import mysql.connector as mariadb
from mysql.connector import errorcode


# sensor name
sensor_name = 'Temperatuur'

# database connection configuration
dbconfig = {
    'user': 'temp',
    'password': '',
    'host': '192.168.137.1',
    'database': 'nerdygadgets',
    'raise_on_warnings': True,
}

# parse arguments
verbose = True
interval = 3  # second

# instantiate a sense-hat object
sh = sense_hat.SenseHat()



# infinite loop
try:
    while True:
        # instantiate a database connection
        try:
            mariadb_connection = mariadb.connect(**dbconfig)
            if verbose:
                print("Database connected")

        except mariadb.Error as err:
            if err.errno == errorcode.ER_ACCESS_DENIED_ERROR:
                print("Something is wrong with your user name or password")
            elif err.errno == errorcode.ER_BAD_DB_ERROR:
                print("Database does not exist")
            else:
                print("Error: {}".format(err))
            sys.exit(2)

        # create the database cursor for executing SQL queries
        cursor = mariadb_connection.cursor(buffered=True)

        # turn on autocommit
        #cursor.autocommit = True

        # set sensorID
        sensorID = 5
        
        # measure temperature
        temp =sh.get_temperature()
        #tempreture verlagen naar realiteit
        temp = temp -32
        temp = round(temp, 2)
        
        from datetime import datetime
        now = datetime.now()
        
        # dd/mm/YY H:M:S
        date = now.strftime("%Y/%m/%d %H:%M:%S")  
        
        dateTo = ("9999-12-31 23:59:59")
        
            

        # verbose
        if verbose:
            print("Temperature: %s C" % temp)

        # store measurement in database
        try:
            cursor.execute('INSERT INTO coldroomtemperatures (ColdRoomSensorNumber, RecordedWhen, Temperature, ValidFrom, ValidTo) VALUES (%s, %s, %s, %s, %s);', (sensorID, date, temp, date, dateTo))
            cursor.execute("SELECT @@IDENTITY AS ID;")
            lastID = (format(cursor.fetchone()[0]))
            print (lastID)
            lastID = int(lastID)-1 
            cursor.execute("Delete FROM coldroomtemperatures WHERE ColdRoomTemperatureID = %s ", (lastID,))
        except mariadb.Error as err:            print("Error: {}".format(err))

        else:
            # commit measurements
            mariadb_connection.commit()

            if verbose:
                print("Temperature committed")

            # close db connection
            cursor.close()
            mariadb_connection.close()
            time.sleep(interval)

except KeyboardInterrupt:
    pass
# close db connection
mariadb_connection.close()
# done
# done

