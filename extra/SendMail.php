<?php

namespace Extra {
	class SendMail {
		public static function mail($to, $from, $subject, $body, $headers = null, $options = null) {
			if (\gettype($to) == 'string') {
				\mail($to,$subject,$body,$headers);
			} else if (\gettype($to) == 'array') {
				foreach($to as $n=>$email) {
					if  ($options == null) {
						\mail($email,$subject,$body,$headers);
					} else {
						\mail($email,$subject,$body,$headers);
					}
				}
			}
		}
	}
}
