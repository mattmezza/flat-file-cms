<?php

namespace FlatFileCMS\Plugins;

interface Plugin {

	public function onPluginEnabled();

	public function onPluginDisabled();

}