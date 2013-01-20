<?php
/**
 * User: ALeontiev
 * Date: 20.01.13
 * Time: 16:15
 */

class parser {
    // ================= Class constructor ============================
    private function __construct() {
    }

    private function __clone() {
    }

    // ================= Class properties ============================
    private $timeout = 30; //timeout get page
    private $errors = array(); //List errors
    private $errorsList = array(
        404 => array(
            'cat' => 'general',
            'type' => 'fatal',
            'method' => 'getContent',
            'name' => 'Failed to get content'
        ),
        101 => array(
            'cat' => 'handle',
            'type' => 'notice',
            'method' => 'Exception->add',
            'name' => 'Item already exists'
        ),
        102 => array(
            'cat' => 'handle',
            'type' => 'notice',
            'method' => 'Exception->add',
            'name' => 'Added element is empty'
        )
    );
    private $exceptionList = array();

    // Default properties value
    private $defaultException = array(
        'script',
        'title',
        'meta',
        'link',
        'style',
    );

    // ================= Class methods ============================
    public function getPage($pagesList) {
        $res = false;

        if (is_array($pagesList) && !empty($pagesList)) {
            foreach ($pagesList as $key=>&$linkPages) {
                if ($linkPages != '' && $pageContents = file_get_contents((string)$linkPages)) {
                    $res = $this->treeForming($pageContents);
                } else {
                    $code = 404;
                    $this->errorHandler($code);
                }
            }
        }

        return $res;
    }

    private function treeForming($content) {
        $res = false;

        if ($content != '') {
            //TODO implementation generate tree
        }
        return $res;
    }

    private function exceptionCode() { //TODO analyse need methods
        $res = false;

        if (isset($this->exceptionList) && !empty($this->exceptionList)) {
            //TODO implementation generate tree
        }

        return $res;
    }

    public function setTimeout($timeout) {
        $res = false;

        if ($timeout > 0) {
            $this->timeout = $timeout;
            $res = true;
        }

        return $res;
    }

    public function setException($exceptionList = array(), $action = 0) {
        $res = false;

        if ($action === 0) {
            if (is_array($exceptionList) && !empty($exceptionList)) {
                foreach($exceptionList as &$el) {
                    $this->addException($el);
                }
            } else {
                $this->addException($exceptionList);
            }
        } elseif ($exceptionList != '') {
            if (is_array($exceptionList) && !empty($exceptionList)) {
                $this->exceptionList = $exceptionList;
            } else {
                $this->exceptionList[] = $exceptionList;
            }
        }

        return $res;
    }

    private function addException ($el) {
        $res = false;
        $code = -1;

        if ($el != '') {
            if (!in_array($el, $this->exceptionList)) {
                $this->exceptionList[] = $el;
            } else {
                $code = 101;
            }

            $res = true;
        } else {
            $code = 102;
        }
        $this->errorHandler($code);

        return $res;
    }

    private function setSituation() {

    }

    public function getErrors() {
        return $this->errors;
    }

    private function errorHandler($code) {
        $res = false;

        if (($code > -1) && isset($this->errorsList[$code]) && is_array($this->errorsList[$code])) {
            $err = $this->errorsList[$code];
            $this->errors[$err['cat']][$code]['descr'] = $err['name'];
            $this->errors[$err['cat']][$code]['method'] = $err['method'];
            $res = true;
        }

        return $res;
    }
}