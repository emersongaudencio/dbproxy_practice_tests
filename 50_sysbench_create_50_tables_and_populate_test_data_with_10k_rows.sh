#!/bin/bash
# mandatory variables
VAR_HOST=${1}
VAR_PORT=${2}

if [ "${VAR_HOST}" == '' ] ; then
  echo "No host has been specified. Please have a look at README file for futher information!"
  exit 1
fi

if [ "${VAR_PORT}" == '' ] ; then
  echo "No Port has been specified. Please have a look at README file for futher information!"
  exit 1
fi
sysbench /usr/share/sysbench/oltp_read_write.lua --threads=4 --mysql-host=${VAR_HOST} --mysql-user=app_user --mysql-password=test123 --mysql-port=${VAR_PORT} --tables=50 --table-size=10000 prepare
