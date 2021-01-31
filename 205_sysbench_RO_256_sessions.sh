#!/bin/bash
# mandatory variables
VAR_HOST=${1}
VAR_CONFIG_REPLICATION=${2}

if [ "${VAR_HOST}" == '' ] ; then
  echo "No host has been specified. Please have a look at README file for futher information!"
  exit 1
fi

if [ "${VAR_PORT}" == '' ] ; then
  echo "No Port has been specified. Please have a look at README file for futher information!"
  exit 1
fi

VAR_TABLE_COUNT=`mysql -uapp_user -ptest123 --host=${VAR_HOST} --port=${VAR_PORT} -e "SELECT cnt FROM (select count(1) as cnt from INFORMATION_SCHEMA.TABLES a where a.TABLE_SCHEMA = 'sbtest' and a.TABLE_TYPE = 'BASE TABLE') Sbquery;" | tr -d "| " | grep -v cnt`
if [ "${VAR_TABLE_COUNT}" == '' ] ; then
  echo "Table count has not been specified. Please have a look at README file for futher information!"
  exit 1
fi
sysbench /usr/share/sysbench/oltp_read_only.lua --threads=256 --events=0 --time=300 --mysql-host=${VAR_HOST} --mysql-user=app_user --mysql-password=test123 --mysql-port=${VAR_PORT} --tables=${VAR_TABLE_COUNT}  --table-size=10000 --db-ps-mode=disable --report-interval=1 --mysql-ignore-errors=all run
