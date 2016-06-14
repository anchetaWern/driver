<?php
use Carbon\Carbon;

class Date
{
	public static function format($date, $format = 'M j')
    {
		return Carbon::parse($date)->format($format);
	}
}