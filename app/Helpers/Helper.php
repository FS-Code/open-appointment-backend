<?php

namespace App\Helpers;

use Exception;

class Helper
{
	/**
	 * @throws Exception
	 */
	public static function secondsToHumanReadable( int $duration ): string
	{
		if ( $duration < 0 ) {
			throw new Exception( 'Time cannot be lower than zero' );
		}

		$days    = floor( $duration / 86400 );
		$hours   = floor( ( $duration - $days * 86400 ) / 3600 );
		$minutes = floor( ($duration / 60 ) % 60 );

		$days = ( $days == 0 ? '' : ( $days > 1 ? $days . 'days ' : $days . 'd ' ) );
		$hours = ( $hours == 0 ? '' : ( $hours > 1 ? $hours . 'hrs ' : $hours . 'hr ' ));
		$minutes = ( $minutes == 0 ? '' : ( $minutes > 1 ? $minutes . 'mins' : $minutes . 'min' ) );

		if ( $days != 0 )
		{
			$result = $days . $hours . $minutes;
		}
		else if ( $hours != 0 )
		{
			$result = $hours . $minutes;
		}
		else
		{
			$result = $minutes;
		}


		return $result;
	}
}