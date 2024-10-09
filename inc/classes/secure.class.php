<?php defined( 'ABSPATH' ) || exit;
/**
 * Secure Class
 *
 * @version 1.0.0
 * @since 1.0.0
 *
 */
if (!class_exists('OPTNNO_Secure')) {
    class OPTNNO_Secure {
        private $secretKey;

        public function __construct() {
            $this->secretKey = get_option('optinno_secret_key');

            if (!$this->secretKey) {
                $this->secretKey = $this->generateSecretKey(64);
                if ($this->secretKey) {
                    update_option('optinno_secret_key', $this->secretKey);
                } else {
                    error_log('Failed to generate a secure key for OPTNNO_Secure');
                }
            }
        }

        private function generateSecretKey($length = 32) {
            try {
                return bin2hex(random_bytes($length));
            } catch (Exception $e) {
                error_log('Error generating secret key: ' . $e->getMessage());
                return false;
            }
        }

        public function encrypt($code) {
            return hash_hmac('sha256', $code, $this->secretKey);
        }

        public function encrypt_data($data) {
            $iv = openssl_random_pseudo_bytes(16);
            $encrypted = openssl_encrypt($data, 'aes-256-cbc', $this->secretKey, 0, $iv);
            return base64_encode($encrypted . '::' . $iv);
        }

        public function decrypt_data($encrypted) {
            list($encrypted_data, $iv) = explode('::', base64_decode($encrypted), 2);
            return openssl_decrypt($encrypted_data, 'aes-256-cbc', $this->secretKey, 0, $iv);
        }
    }
}
