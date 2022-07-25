<?php
use Clsk\Elena\Router\StarNavigator;

StarNavigator::Get("/", function(){ Viewer("Hello"); });