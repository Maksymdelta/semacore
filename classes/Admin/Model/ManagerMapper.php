<?php

namespace Admin\Model;

class ManagerMapper extends \DB\Mongo\Mapper {
    
    public function setPassword($password) {
        $this->set('password', \Bcrypt::instance()->hash($password)); // @TODO improve hashing with salt, etc.
    }

    public function __construct() {
        parent::__construct( \Base::instance()->get('mongo'), 'Managers' );

        // beforeinsert was broken and fixed in @dev see https://github.com/bcosca/fatfree/pull/607

        $this->beforeupdate(function() {
            $this->updatedAt = time();
        });
    }

    public function insert() {
        $this->createdAt = time();
        $this->updatedAt = time();
        return parent::insert();
    }

    public function __set($property, $value) {
        $methodName = "set" . ucfirst($property);
        if ( method_exists($this, $methodName) ) {
            $this->$methodName($value);
        } else {
            $this->set($property, $value);
        }
    }
}

?>
