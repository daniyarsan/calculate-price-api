#!/bin/bash

mysql -u root -p$MYSQL_PASSWORD $MYSQL_DATABASE < /var/dump.sql
