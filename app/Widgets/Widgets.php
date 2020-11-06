<?php


namespace WowpiGuild\Widgets;


class Widgets {

	function init() {
		$recruitmentWidget = new Recruitment();
		register_widget($recruitmentWidget);

	}

}