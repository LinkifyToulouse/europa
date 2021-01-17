<?php
namespace Extra {
	class FormChecker {
		const TEXT = 'text';
		const PHONE = 'phone';
		const EMAIL = 'email';
		const OPT_TEXT = 'opt_text';
		const CHECK = 'check';
		const SHORT_TEXT = 'short_text';
		
		public static function verify($string, $regtype) {
			if ($regtype == 'text') {
				if (!preg_match("#^([A-Za-z0-9 \_\-\.\,\'\&\n\r\;\:\!\?éèêçÈÉÊàùôÀÇ\(\)\"])+$#i", $string) || preg_match('#DELETE|INSERT|SET|WHERE|INTO|FROM|DROP|\=|\-\-|\'\;|\;|\##', $string)) {
					return TRUE;
				} else {
					return FALSE;
				}
			} else if ($regtype == 'phone') {
				if (!preg_match("#^([0-9]{2}\.* *){5}$#", $string) || !preg_match("#^(\+33)?( )?([0-9]{1,2}[\.| ]?){5}$#", $string)) {
					return TRUE;
				} else {
					return FALSE;
				}
			} else if ($regtype == 'email') {
				if (!preg_match("#^[A-Za-z0-9._-]+@[a-z0-9A-Z._-]{2,}\.[a-z]{2,8}$#", $string)) {
					return TRUE;
				} else {
					return FALSE;
				}
			} else if ($regtype == 'opt_text') {
				if (!preg_match("#^([A-Za-z0-9 \_\-\.\,\'\&\n\r\;\:\!\?éèêçÈÉÊàùôÀÇ\(\)\"\/])+$#i", $string) && !preg_match("#^$#", $string) || preg_match('#DELETE|INSERT|SET|WHERE|INTO|FROM|DROP|\=|\-\-|\'\;|\;|\##', $string)) {
					return TRUE;
				} else {
					return FALSE;
				}
			} else if ($regtype == 'check') {
				if (!preg_match('#^true$#', $string)) {
					return TRUE;
				} else {
					return FALSE;
				}
			} else if ($regtype == 'short_text') {
				if (!preg_match("#^([A-Za-z \-\'\.ÈÉÇÀÊùàéêËëè])+$#i", $string) ||     preg_match('#DELETE|INSERT|SET|WHERE|INTO|FROM|DROP|\=|\-\-|\'\;|\;|\##', $string)) {
					return TRUE;
				} else {
					return FALSE;
				}
			}
		}
	}
}
