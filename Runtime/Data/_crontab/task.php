<?php
return array (
  'hits' => 
  array (
    'crontab_status' => 1,
    'crontab_info' => '更新统计人气',
    'crontab_type' => 'hits',
    'crontab_week' => '1,2,3,4,5,6,0',
    'crontab_hour' => '00',
    'crontab_time' => '1548056682',
    'crontab_params' => '',
  ),
  'caiji' => 
  array (
    'crontab_status' => '1',
    'crontab_info' => '每小时采集3小时内更新的视频',
    'crontab_type' => 'caiji',
    'crontab_week' => '1,2,3,4,5,6,0',
    'crontab_hour' => '00,01,02,03,04,05,06,07,08,09,10,11,12,13,14,15,16,17,18,19,20,21,22,23',
    'crontab_time' => '1549863717',
    'crontab_params' => '1,3',
  ),
  'html' => 
  array (
    'crontab_status' => '1',
    'crontab_info' => '生成2小时内更新的数据',
    'crontab_type' => 'create',
    'crontab_week' => '1,2,3,4,5,6,0',
    'crontab_hour' => '00,02,04,06,08,10,12,14,16,18,19,20,22',
    'crontab_time' => '1549863749',
    'crontab_params' => '2',
  ),
  'news' => 
  array (
    'crontab_status' => '1',
    'crontab_info' => '每小时采集24小时内更新的资讯',
    'crontab_type' => 'caiji',
    'crontab_params' => '6,3',
    'crontab_week' => '1,2,3,4,5,6,0',
    'crontab_hour' => '00,01,02,03,04,05,06,07,08,09,10,11,12,13,14,15,16,17,18,19,20,21,22,23',
    'crontab_time' => '1549863680',
  ),
);
?>