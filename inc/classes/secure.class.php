<?php defined( 'SDOPATH' ) || exit;
/**
 * Secure Class
 *
 * @version 1.0.0
 * @since 1.0.0
 *
 */
if (!class_exists('SDO_Secure')) {
    class SDO_Secure {
        private $secretKey;
        public function __construct() {
            $this->secretKey = $this->generateSecretKey(64);
        }
        private function generateSecretKey($length = 32) {
            try {
                return bin2hex(random_bytes($length));
            } catch (Exception $e) {
                return false;
            }
        }
        private function encrypt($code) {
            return hash_hmac('sha256', $code, $this->secretKey);
        }
    }
}