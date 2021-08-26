<?php

	unset($CFG_CLICKVIEW);

	$CFG_CLICKVIEW = new stdClass();
	$CFG_CLICKVIEW->onlineHost = "//<~onlineHost~>";
	$CFG_CLICKVIEW->consumerKey = "<~consumerKey~>";
	$CFG_CLICKVIEW->pluginFrameUrl = $CFG_CLICKVIEW->onlineHost."/v3/plugins/base?consumerKey=".$CFG_CLICKVIEW->consumerKey;