<?php
    class AppError extends ErrorHandler
    {
        function __construct($method, $messages) {
            App::import('Core', 'Sanitize');
            static $__previousError = null;

            if ($__previousError != array($method, $messages)) {
                $__previousError = array($method, $messages);
                $this->controller =& new CakeErrorController();
            } else {
                $this->controller =& new Controller();
                $this->controller->viewPath = 'errors';
            }

            $options = array('escape' => false);
            $messages = Sanitize::clean($messages, $options);

            if (!isset($messages[0])) {
                $messages = array($messages);
            }

            if (method_exists($this->controller, 'apperror')) {
                return $this->controller->appError($method, $messages);
            }

            if (!in_array(strtolower($method), array_map('strtolower', get_class_methods($this)))) {
                $method = 'error';
            }

            if ($method !== 'error') {
                if( Configure::read( 'debug' ) == 0 ) {
                    switch( $method ) {
                        case 'missingController':
                        case 'missingAction':
                        case 'missingView':
                        case 'privateAction':
                        case 'error404':
                            $method = 'error404';
                        break;
                        default:
                            $method = 'error500';
                        break;
                    }
                }
            }

            $this->dispatchMethod($method, $messages);
            $this->_stop();
        }

        function error500( $params ) {
            extract($params, EXTR_OVERWRITE);

            if (!isset($url)) {
                $url = $this->controller->here;
            }
            $url = Router::normalize($url);
            header("HTTP/1.0 500 Internal server error");
            $this->controller->set(array(
                'code' => '500',
                'name' => __('Internal server error', true),
                'message' => $url,
                'base' => $this->controller->base
            ));
            $this->_outputMessage('error500');
        }
    }
?>