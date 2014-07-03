PRPL TWITTER MODULE
===================

Installation
------------

To install the PRPL twitter module follow these steps:

1. Place the 'prpl_twitter' module directory in /system/expressionengine/third_party

2. Login to the the Expression Engine admin

3. Go to Add-ons -> Modules

4. Click install on the Prpl Twitter module

5. All tables and data will be automatically setup


SETUP
------------

On server create a cron job that regular makes a curl request towards this url:

http://URL.com?ACT=id

Id is the corresponding action id in the exp_actions table. Find class 'Prpl_twitter' and action 'cron'




