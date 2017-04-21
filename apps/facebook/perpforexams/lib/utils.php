<?php
	class CUtils
	{
		/*
		 * Generate and return a random string of specified length.
		 */
		static function GetRandString($length)
		{
			$str = "" ;
			
			for($index = 0; $index < $length; $index++)
			{
				$str .= chr(rand(33,255)) ;
			}
			
			return $str ;
		}
		
		/*
		 * Return percet of word match.
		 */
		static function CompWords($ary_in_srch, $ary_to_srch)
		{
			$index = 0;
			$count = count($ary_in_srch) ;

			foreach($ary_in_srch as $word_1)
			{
				foreach($ary_to_srch as $word_2)
				{
					if(strcmp($word_1, $word_2) == 0)
					{
						$index++ ;
					}
				}
			}

			return ($index*100/$count) ;
		}
		
		/*
		 * For input 3 it will give 3+2+1, for 4 => 4+3+2+1 etc.
		 */
		static function SumUpto($count)
		{
			$sum = 0 ;
			
			for(; $count > 0; $count--)
			{
				$sum += $count ;
			}
			
			return $sum ;
		}
		
		/*
		 * Find words (param:2 array) in string (param:1).
		 */
		static function FindAndHighLight($str, $arrToFind)
		{
			if(is_numeric($str))
			{
				foreach($arrToFind as $word)
				{
					$str =  preg_replace("/\b$word\b/i", "<FONT COLOR='#FF3300'>".ucwords(strtolower($word))."</FONT>", $str) ;
				}
				return $str ;
			}
			
			$metaphone_str = CUtils::GetMetaphone($str) ;
			
			foreach($arrToFind as $word)
			{
				if (true)//(!function_exists('str_ireplace')) 
				{
					//$str =  preg_replace("/\b$word\b/i", "<FONT COLOR='#FF3300'>".ucwords(strtolower($word))."</FONT>", $str) ;
					$metaphone_str =  preg_replace("/\b".metaphone($word)."\b/i", "##R007##", $metaphone_str) ;
				}
				else
				{
					$metaphone_str = str_ireplace($word, "<FONT COLOR='#FF3300'>".ucwords(strtolower($word))."</FONT>", $str);
				}
			}
			
			$meta_ary = str_word_count($metaphone_str, 1, "#07") ;
			$str_ary  = str_word_count($str, 1) ;
			
			$index = 0 ;
			$str = "" ;
			foreach ($meta_ary as $meta_word)
			{
				if(strcasecmp($meta_word, "##R007##") == 0)
				{
					$str .= "<FONT COLOR='#FF3300'>".$str_ary[$index]."</FONT> " ;
				}
				else 
				{
					$str .= $str_ary[$index]." " ;
				}
				$index++ ;
			}
			return $str ;
		}
		
		/* 
		 * Redirect to a different page. Path to file with reference to host (root) is required.
		 */
		static function Redirect($filepath, $direct = false)
		{
			$url = "";
			if(!$direct)
			{
			$host  = $_SERVER['HTTP_HOST'] ;
			$uri   = rtrim(dirname($_SERVER['PHP_SELF']), '/\\') ;
			
			$url = "http://".$host.$uri."/".$filepath;
			//header($url) ;
			}
			else 
			{
				$url = $filepath;
			}
			
			if(!headers_sent()) 
			{
		        header('Location: '.$url);
		    }
		    else
		    {
				printf("<script>window.location.replace('%s');</script>", $url);
		    }
		}
		
		/*
		 * Rertieve Current Page URL.
		 */
		static function curPageURL() 
		{
			$pageURL = 'http';
			if ($_SERVER["HTTPS"] == "on")
			{
				$pageURL .= "s";
			}
			$pageURL .= "://";
			if ($_SERVER["SERVER_PORT"] != "80") 
			{
				$pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
			}
			else
			{
				$pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
			}
			return $pageURL;
		}
		
		/**
		 * Generates a Universally Unique IDentifier, version 4.
		 *
		 * RFC 4122 (http://www.ietf.org/rfc/rfc4122.txt) defines a special type of Globally
		 * Unique IDentifiers (GUID), as well as several methods for producing them. One
		 * such method, described in section 4.4, is based on truly random or pseudo-random
		 * number generators, and is therefore implementable in a language like PHP.
		 *
		 * We choose to produce pseudo-random numbers with the Mersenne Twister, and to always
		 * limit single generated numbers to 16 bits (ie. the decimal value 65535). That is
		 * because, even on 32-bit systems, PHP's RAND_MAX will often be the maximum *signed*
		 * value, with only the equivalent of 31 significant bits. Producing two 16-bit random
		 * numbers to make up a 32-bit one is less efficient, but guarantees that all 32 bits
		 * are random.
		 *
		 * The algorithm for version 4 UUIDs (ie. those based on random number generators)
		 * states that all 128 bits separated into the various fields (32 bits, 16 bits, 16 bits,
		 * 8 bits and 8 bits, 48 bits) should be random, except : (a) the version number should
		 * be the last 4 bits in the 3rd field, and (b) bits 6 and 7 of the 4th field should
		 * be 01. We try to conform to that definition as efficiently as possible, generating
		 * smaller values where possible, and minimizing the number of base conversions.
		 *
		 * @copyright   Copyright (c) CFD Labs, 2006. This function may be used freely for
		 *              any purpose ; it is distributed without any form of warranty whatsoever.
		 * @author      David Holmes <dholmes@cfdsoftware.net>
		 *
		 * @return  string  A UUID, made up of 32 hex digits and 4 hyphens.
		 */
	
		static function uuid() 
		{
			// The field names refer to RFC 4122 section 4.1.2
			
			return sprintf('%04x%04x-%04x-%03x4-%04x-%04x%04x%04x',
			    mt_rand(0, 65535), mt_rand(0, 65535), // 32 bits for "time_low"
			    mt_rand(0, 65535), // 16 bits for "time_mid"
			    mt_rand(0, 4095),  // 12 bits before the 0100 of (version) 4 for "time_hi_and_version"
			    bindec(substr_replace(sprintf('%016b', mt_rand(0, 65535)), '01', 6, 2)),
			        // 8 bits, the last two of which (positions 6 and 7) are 01, for "clk_seq_hi_res"
			        // (hence, the 2nd hex digit after the 3rd hyphen can only be 1, 5, 9 or d)
			        // 8 bits for "clk_seq_low"
			    mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535) // 48 bits for "node" 
				); 
		}
		
		static function flushHard()
		{
			// echo an extra 256 byte to the browswer - Fix for IE.
			for($i=1;$i<=256;++$i)
			{
				echo ' ';
			}
			flush();
			ob_flush();
		}

		static function get_redirect_url($url)
		{
			$redirect_url = null; 
		 
			$url_parts = @parse_url($url);
			if (!$url_parts) return false;
			if (!isset($url_parts['host'])) return false; //can't process relative URLs
			if (!isset($url_parts['path'])) $url_parts['path'] = '/';
		 
			$sock = fsockopen($url_parts['host'], (isset($url_parts['port']) ? (int)$url_parts['port'] : 80), $errno, $errstr, 30);
			if (!$sock) return false;
		 
			$request = "HEAD " . $url_parts['path'] . (isset($url_parts['query']) ? '?'.$url_parts['query'] : '') . " HTTP/1.1\r\n"; 
			$request .= 'Host: ' . $url_parts['host'] . "\r\n"; 
			$request .= "Connection: Close\r\n\r\n"; 
			fwrite($sock, $request);
			$response = '';
			while(!feof($sock)) $response .= fread($sock, 8192);
			fclose($sock);
		 
			if (preg_match('/^Location: (.+?)$/m', $response, $matches))
			{
				if ( substr($matches[1], 0, 1) == "/" )
					return $url_parts['scheme'] . "://" . $url_parts['host'] . trim($matches[1]);
				else
					return trim($matches[1]);
		 
			} 
			else 
			{
				return false;
			}
			 
		}
		 
		/**
		 * get_all_redirects()
		 * Follows and collects all redirects, in order, for the given URL. 
		 *
		 * @param string $url
		 * @return array
		 */
		static function get_all_redirects($url)
		{
			$redirects = array();
			while ($newurl = CUtils::get_redirect_url($url))
			{
				if (in_array($newurl, $redirects))
				{
					break;
				}
				$redirects[] = $newurl;
				$url = $newurl;
			}
			return $redirects;
		}
		 
		/**
		 * get_final_url()
		 * Gets the address that the URL ultimately leads to. 
		 * Returns $url itself if it isn't a redirect.
		 *
		 * @param string $url
		 * @return string
		 */
		static function get_final_url($url)
		{
			$redirects = CUtils::get_all_redirects($url);
			if (count($redirects)>0)
			{
				return array_pop($redirects);
			} 
			else 
			{
				return $url;
			}
		}
		
		private static function createRandomString($string_length, $character_set)
		{
			$random_string = array();
			for ($i = 1; $i <= $string_length; $i++) 
			{
				$rand_character = $character_set[rand(0, strlen($character_set) - 1)];
				$random_string[] = $rand_character;
			}
			shuffle($random_string);
			
			return implode('', $random_string);
		}

		private static function validUniqueString($string_collection, $new_string, $existing_strings='') 
		{
			if (!strlen($string_collection) && !strlen($existing_strings))
				return true;
	
			$combined_strings = $string_collection . ", " . $existing_strings;
			
			return (strlen(strpos($combined_strings, $new_string))) ? false : true;
		}

		public static function createRandomStringCollection($string_length, $number_of_strings = 1, $existing_strings = '') 
		{
			$character_set = '23456789ABCDEFGHJKLMNPQRSTUVWXYZ';
			$string_collection = '';
			for ($i = 1; $i <= $number_of_strings; $i++) 
			{
				$random_string = self::createRandomString($string_length, $character_set);
				while (!self::validUniqueString($string_collection, $random_string, $existing_strings)) 
				{
					$random_string = self::createRandomString($string_length, $character_set);
				}
				$string_collection .= ( !strlen($string_collection)) ? $random_string : ", " . $random_string;
			}

			return $string_collection;
		}
		
		public static function getMimeType($data)
        {
            //File signatures with their associated mime type
            $Types = array(
            "474946383761"=>"image/gif",                        //GIF87a type gif
            "474946383961"=>"image/gif",                        //GIF89a type gif
            "89504E470D0A1A0A"=>"image/png",
            "FFD8FFE0"=>"image/jpeg",                           //JFIF jpeg
            "FFD8FFE1"=>"image/jpeg",                           //EXIF jpeg
            "FFD8FFE8"=>"image/jpeg",                           //SPIFF jpeg
            "25504446"=>"application/pdf",
            "377ABCAF271C"=>"application/zip",                  //7-Zip zip file
            "504B0304"=>"application/zip",                      //PK Zip file ( could also match other file types like docx, jar, etc )
            );
           
            $Signature = substr($data,0,60); //get first 60 bytes shouldnt need more then that to determine signature
            $Signature = array_shift(unpack("H*",$Signature)); //String representation of the hex values
           
            foreach($Types as $MagicNumber => $Mime)
            {
                if( stripos($Signature,$MagicNumber) === 0 )
                    return $Mime; 
            }
           
            //Return octet-stream (binary content type) if no signature is found
            return "application/octet-stream";
       
        }
	}
?>