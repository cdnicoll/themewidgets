<?php
interface IRssReader {
	public function createRssObject($feed);
	public function isUrlActive($url);
}
