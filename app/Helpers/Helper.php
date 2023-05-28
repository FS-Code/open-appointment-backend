<?php

namespace App\Helpers;

use Exception;

class Helper
{
	/**
	 * @throws Exception
	 */
	public static function secondsToHumanReadable ( int $duration ) : string
	{
		if ( $duration < 0 )
		{
			throw new Exception( 'Time cannot be lower than zero' );
		}

		if ( $duration < 60 ) {
			$result = 'The time is lower than a minute';
		} else {
			$days    = floor( $duration / 86400 );
			$hours   = floor( ( $duration - $days * 86400 ) / 3600 );
			$minutes = floor( ( $duration / 60 ) % 60 );

			$days    = ( $days == 0 ? '' : $days . ( $days > 1 ? 'days ' : 'd ' ) );
			$hours   = ( $hours == 0 ? '' : $hours . ( $hours > 1 ? 'hrs ' : 'hr ' ) );
			$minutes = ( $minutes == 0 ? '' : $minutes . ( $minutes > 1 ? 'mins' : 'min' ) );

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
		}


		return $result;
	}
}