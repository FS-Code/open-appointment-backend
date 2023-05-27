<?php 
  
 namespace App\Helpers; 
  
 class DateFormatHelper { 
    
     public static function secondsToHumanReadable(int $duration): string 
     { 
         $hours = floor($duration / 3600); 
         $minutes = floor(($duration % 3600) / 60); 
         $seconds = $duration % 60; 
          
         $output = ''; 
          
         if ($hours > 0) { 
             $output .= $hours . 'hr'; 
         } 
          
         if ($minutes > 0) { 
             if ($output !== '') { 
                 $output .= ' '; 
             } 
             $output .= $minutes . 'min'; 
         } 
          
         if ($output === '') { 
             $output = $seconds . 'sec'; 
         } 
          
         return $output; 
     } 
 }
