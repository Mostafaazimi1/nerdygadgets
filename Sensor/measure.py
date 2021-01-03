#!/usr/bin/python3
import sys
import getopt
import sense_hat
import time
import mysql.connector as mariadb
from mysql.connector import errorcode

sensor_name = 'Temperatuur'

dbconfig = {
    'user': 'temp',
    'password': '',
    'host': '192.168.137.1',
    'database': 'nerdygadgets',
    'raise_on_warnings': True,
}

verbose = True
interval = 3
sh = sense_hat.SenseHat()

try:
    while True:
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

        cursor = mariadb_connection.cursor(buffered=True)
        sensorID = 5
        temp =sh.get_temperature()
        temp = temp -32
        temp = round(temp, 2)
        
        from datetime import datetime
        now = datetime.now()
        date = now.strftime("%Y/%m/%d %H:%M:%S")
        dateTo = ("9999-12-31 23:59:59")
        if verbose:
            print("Temperature: %s C" % temp)

        try:
            cursor.execute('INSERT INTO coldroomtemperatures (ColdRoomSensorNumber, RecordedWhen, Temperature, ValidFrom, ValidTo) VALUES (%s, %s, %s, %s, %s);', (sensorID, date, temp, date, dateTo))
            cursor.execute("SELECT @@IDENTITY AS ID;")
            lastID = (format(cursor.fetchone()[0]))
            print (lastID)
            lastID = int(lastID)-1 
            cursor.execute("Delete FROM coldroomtemperatures WHERE ColdRoomTemperatureID = %s ", (lastID,))
        except mariadb.Error as err:            print("Error: {}".format(err))
        else:
            mariadb_connection.commit()
            if verbose:
                print("Temperature committed")
            cursor.close()
            mariadb_connection.close()
            time.sleep(interval)

except KeyboardInterrupt:
    pass
mariadb_connection.close()
# done
# done

