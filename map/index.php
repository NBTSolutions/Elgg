<?php
$app_env = getenv("APP_ENV");
$app_env = $app_env ? $app_env : "unstable";

header('Location: http://nbt-static.s3-website-us-east-1.amazonaws.com/weatherblur/map/'.$app_env.'/');
